<?php

namespace App\Http\Middleware;

use App\Models\Cluster;
use Inertia\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\UserRooms;
use App\Models\RoomTransfer;
use App\Models\DamageReport;
use App\Models\Refund;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Set the root template to use for Inertia SSR.
     *
     * @see https://inertiajs.com/server-side-rendering
     */
    protected $ssrView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Get website settings
        $logoMain = Setting::get('logo_main');
        $logoFavicon = Setting::get('logo_favicon');

        $settings = [
            'logo_main' => $logoMain,
            'logo_favicon' => $logoFavicon,
            'site_name' => Setting::get('site_name', 'Tharahub'),
            'site_description' => Setting::get('site_description', 'Manajemen kos online'),
            'contact_email' => Setting::get('contact_email'),
            'contact_phone' => Setting::get('contact_phone'),
            'contact_address' => Setting::get('contact_address'),
            'color_primary' => Setting::get('color_primary', '#3B82F6'),
            'color_secondary' => Setting::get('color_secondary', '#8B5CF6'),
            'color_accent' => Setting::get('color_accent', '#10B981'),
            'color_success' => Setting::get('color_success', '#10B981'),
            'color_warning' => Setting::get('color_warning', '#F59E0B'),
            'color_error' => Setting::get('color_error', '#EF4444'),
            'color_info' => Setting::get('color_info', '#3B82F6'),
        ];

        // Format logo URLs
        if ($logoMain && is_string($logoMain)) {
            $settings['logo_main_url'] = str_starts_with($logoMain, 'http')
                ? $logoMain
                : asset('storage/' . $logoMain);
        } else {
            $settings['logo_main_url'] = '/images/logo/Tharahub.png';
        }

        if ($logoFavicon && is_string($logoFavicon)) {
            $settings['logo_favicon_url'] = str_starts_with($logoFavicon, 'http')
                ? $logoFavicon
                : asset('storage/' . $logoFavicon);
        } else {
            $settings['logo_favicon_url'] = '/images/logo/Tharahub.png';
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => Auth::check() ? (new \App\Http\Resources\UserResource(Auth::user()->load('tenant')))->resolve() : null,
                'has_tenant' => Auth::check() ? Auth::user()->tenant()->exists() : false,
                'roles' => Auth::check() ? Auth::user()->getRoleNames() : [],
                'permissions' => Auth::check() ? Auth::user()->getAllPermissions()->pluck('name') : [],
            ],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error'   => fn() => $request->session()->get('error'),
                'duplicates' => fn() => $request->session()->get('duplicates'),
                'import_results' => fn() => $request->session()->get('import_results'),
            ],
            'errors' => fn() => $request->session()->get('errors')
                ? $request->session()->get('errors')->toArray()
                : (object)[],
            'settings' => $settings,
            'midtrans_client_key' => config('services.midtrans.client_key'),
            'midtrans_is_production' => (bool) config('services.midtrans.is_production'),
            'clusters' => Auth::check() && Auth::user()->hasAnyRole(['Superadmin', 'Pengelola', 'Pemilik'])
                ? Cluster::select('id', 'name')->when(Auth::user()->hasRole('Pemilik'), function ($query) {
                    return $query->whereHas('boardingHouses', function ($query) {
                        $query->where('owner_id', Auth::id());
                    });
                })->get()
                : [],
            'pendingCounts' => $this->getPendingCounts($request),
        ]);
    }

    /**
     * Get pending request counts for administrators.
     */
    protected function getPendingCounts(Request $request): array
    {
        $counts = [
            'checkin_requests' => 0,
            'room_transfers' => 0,
            'damage_reports' => 0,
            'refunds' => 0,
            'unpaid_bills' => 0,
            'unpaid_tenants' => 0,
        ];

        if (!Auth::check()) {
            return $counts;
        }

        $user = Auth::user();

        // Count unpaid bills for tenants
        if ($user->hasRole('Penyewa')) {
            $counts['unpaid_bills'] = \App\Models\Transaction::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'incomplete'])
                ->count();
        }

        if (!$user->hasAnyRole(['Superadmin', 'Pengelola', 'Pemilik'])) {
            return $counts;
        }

        $clusterId = $request->query('cluster_id');
        // ... existing admin counts ...

        // Check-in Requests
        $counts['checkin_requests'] = UserRooms::where('status', 'checkin_open')
            ->whereNotNull('foto_kamar')
            ->where('verifikasi_admin', 0)
            ->whereHas('room.boardingHouse', function ($q) use ($user) {
                if ($user->hasRole('Pemilik')) {
                    $q->where('owner_id', $user->id);
                }
            })->count();

        // Room Transfer Requests
        $counts['room_transfers'] = RoomTransfer::where('status', 'pending')
            ->whereHas('room.boardingHouse', function ($q) use ($clusterId, $user) {
                if ($user->hasRole('Pemilik')) {
                    $q->where('owner_id', $user->id);
                }
            })->count();

        // Damage Reports
        $counts['damage_reports'] = DamageReport::where('status', 'pending')
            ->whereHas('userRoom.room.boardingHouse', function ($q) use ($clusterId, $user) {

                if ($user->hasRole('Pemilik')) {
                    $q->where('owner_id', $user->id);
                }
            })->count();

        // Refunds
        $counts['refunds'] = Refund::whereIn('status', ['pending', 'process', 'menunggu konfirmasi'])
            ->whereHas('boardingHouse', function ($q) use ($clusterId, $user) {

                if ($user->hasRole('Pemilik')) {
                    $q->where('owner_id', $user->id);
                }
            })->count();

        // Unpaid Tenants
        $counts['unpaid_tenants'] = \App\Models\Transaction::whereIn('status', ['pending', 'incomplete'])
            ->whereHas('userRoom.boardingHouse', function ($q) use ($clusterId, $user) {
                if ($user->hasRole('Pemilik')) {
                    $q->where('owner_id', $user->id);
                }
            })
            ->distinct('user_id')
            ->count('user_id');


        return $counts;
    }
}
