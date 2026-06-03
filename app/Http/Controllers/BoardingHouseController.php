<?php

namespace App\Http\Controllers;

use App\Actions\BoardingHouse\DeleteBoardingHouseAction;
use App\Actions\BoardingHouse\GetBoardingHousePaginate;
use App\Actions\BoardingHouse\StoreBoardingHouseAction;
use App\Actions\BoardingHouse\UpdateBoardingHouseAction;
use App\Http\Requests\BoardingHouse\StoreBoardingHouseRequest;
use App\Http\Requests\BoardingHouse\UpdateBoardingHouseRequest;
use App\Models\BoardingHouse;
use App\Models\BoardingHouseImage;
use App\Models\Cluster;
use App\Models\User;
use App\Helpers\LogActivityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BoardingHouseController extends Controller
{
    /**
     * Clusters visible to the current user.
     */
    private function scopedClusters()
    {
        $user = Auth::user();
        return Cluster::select('id', 'name')
            ->when($user->hasRole('Pengelola'), fn($q) => $q->where('pengelola_id', $user->id))
            ->when($user->hasRole('Pemilik'), fn($q) => $q->whereHas('boardingHouses', fn($q2) => $q2->where('owner_id', $user->id)))
            ->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // abort_unless(Gate::allows('boarding_houses.view'), 403, 'Anda tidak memiliki akses untuk melihat data boarding house');

        $search = $request->input('search');
        $clusterId = $request->input('cluster_id');
        $gender = $request->input('gender');

        $boardingHouses = app(GetBoardingHousePaginate::class)->execute($request->all() + ['paginate' => true], Auth::user());

        $clusters = $this->scopedClusters();
        $owners = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'Penyewa');
        })->get();

        return Inertia::render('Admin/BoardingHouses/Index', [
            'boardingHouses' => $boardingHouses,
            'clusters' => $clusters,
            'owners' => $owners,
            'search' => $search,
            'cluster_id' => $clusterId,
            'gender' => $gender,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // abort_unless(Gate::allows('boarding_houses.create'), 403, 'Anda tidak memiliki akses untuk menambah boarding house');

        $clusters = $this->scopedClusters();
        $owners = User::whereHas('roles', fn($q) => $q->where('name', 'Pemilik'))->get(['id', 'name']);
        $pengelolas = User::whereHas('roles', fn($q) => $q->where('name', 'Pengelola'))->get(['id', 'name']);
        $clusterId = $request->input('cluster_id');

        return Inertia::render('Admin/BoardingHouses/Create', [
            'clusters' => $clusters,
            'owners' => $owners,
            'pengelolas' => $pengelolas,
            'cluster_id' => $clusterId,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BoardingHouse $boardingHouse)
    {
        // abort_unless(Gate::allows('boarding_houses.edit'), 403, 'Anda tidak memiliki akses untuk mengubah boarding house');

        $boardingHouse->load('cluster', 'owner');
        $clusters = $this->scopedClusters();
        $owners = User::whereHas('roles', fn($q) => $q->where('name', '!=', 'Penyewa'))->get(['id', 'name']);
        $pengelolas = User::whereHas('roles', fn($q) => $q->where('name', 'Pengelola'))->get(['id', 'name']);

        return Inertia::render('Admin/BoardingHouses/Edit', [
            'boardingHouse' => $boardingHouse,
            'clusters' => $clusters,
            'owners' => $owners,
            'pengelolas' => $pengelolas,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoardingHouseRequest $request)
    {
        // abort_unless(Gate::allows('boarding_houses.create'), 403, 'Anda tidak memiliki akses untuk menambah boarding house');

        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('boarding-houses', 'public');
        }

        $boardingHouse = app(StoreBoardingHouseAction::class)->execute($data, Auth::user());

        LogActivityHelper::addToLog('Menambah kos: ' . $boardingHouse->name, $data);

        return redirect()->route('boarding-houses.index')->with('success', 'Kos berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBoardingHouseRequest $request, BoardingHouse $boardingHouse)
    {
        // abort_unless(Gate::allows('boarding_houses.edit'), 403, 'Anda tidak memiliki akses untuk mengubah boarding house');

        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('boarding-houses', 'public');
        } elseif ($request->boolean('remove_thumbnail') === true) {
            $data['remove_thumbnail'] = true;
        }

        app(UpdateBoardingHouseAction::class)->execute($boardingHouse, $data, Auth::user());

        LogActivityHelper::addToLog('Mengubah kos: ' . $boardingHouse->name, $data);

        return redirect()->route('boarding-houses.index')->with('success', 'Kos berhasil diperbarui');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, BoardingHouse $boardingHouse)
    {
        // abort_unless(Gate::allows('boarding_houses.view'), 403, 'Anda tidak memiliki akses untuk melihat detail boarding house');

        $result = app(\App\Actions\Room\GetRoomsAction::class)->execute($request, $boardingHouse);
        $rooms = $result['rooms'];

        $boardingHouse->load('cluster', 'owner', 'images', 'penyewa');

        // dd($boardingHouse->toArray());
        // Get expenses for this boarding house with room name
        $expenses = $boardingHouse->expenses()
            ->with('room:id,name')
            ->orderBy('expense_date', 'desc')
            ->get()
            ->map(function ($expense) {
                return [
                    'id' => $expense->id,
                    'description' => $expense->description,
                    'amount' => $expense->amount,
                    'date' => $expense->expense_date->format('Y-m-d'),
                    'room_id' => $expense->room_id,
                    'room_name' => $expense->room ? $expense->room->name : null,
                    'receipt_path' => $expense->receipt_path,
                ];
            });

        // dd($rooms->toArray());

        return Inertia::render('Admin/BoardingHouses/Show', [
            'boardingHouse' => $boardingHouse,
            'rooms' => $rooms,
            'expenses' => $expenses,
            'search' => $request->input('search'),
            'gender' => $request->input('gender'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoardingHouse $boardingHouse)
    {
        abort_unless(Gate::allows('boarding_houses.delete'), 403, 'Anda tidak memiliki akses untuk menghapus boarding house');

        try {
            $bhName = $boardingHouse->name;
            app(DeleteBoardingHouseAction::class)->execute($boardingHouse);

            LogActivityHelper::addToLog('Menghapus kos: ' . $bhName);

            return redirect()->back()->with('success', 'Boarding house berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus boarding house: ' . $e->getMessage());
        }
    }

    /**
     * Store multiple images for a boarding house
     */
    public function storeImages(Request $request, BoardingHouse $boardingHouse)
    {
        abort_unless(Gate::allows('boarding_houses.edit'), 403, 'Anda tidak memiliki akses untuk menambah gambar');

        $request->validate([
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $file) {
            $path = $file->store('boarding-houses/images', 'public');

            $image = BoardingHouseImage::create([
                'boarding_house_id' => $boardingHouse->id,
                'image' => $path,
            ]);

            $uploadedImages[] = $image;
        }

        return redirect()->back()->with('success', count($uploadedImages) . ' gambar berhasil diupload');
    }

    /**
     * Delete an image from a boarding house
     */
    public function destroyImage(BoardingHouse $boardingHouse, BoardingHouseImage $boardingHouseImage)
    {
        abort_unless(Gate::allows('boarding_houses.edit'), 403, 'Anda tidak memiliki akses untuk menghapus gambar');

        // Verify the image belongs to the boarding house
        if ($boardingHouseImage->boarding_house_id !== $boardingHouse->id) {
            abort(403, 'Gambar tidak terkait dengan kos ini');
        }

        // Delete file from storage
        if ($boardingHouseImage->image && Storage::disk('public')->exists($boardingHouseImage->image)) {
            Storage::disk('public')->delete($boardingHouseImage->image);
        }

        // Delete record from database
        $boardingHouseImage->delete();

        return redirect()->back()->with('success', 'Gambar berhasil dihapus');
    }
}
