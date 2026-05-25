<?php

namespace App\Http\Controllers;

use App\Actions\Setting\EnsureDefaultSettingsAction;
use App\Actions\Setting\GetSettingsAction;
use App\Actions\Setting\UpdateMultipleSettingsAction;
use App\Http\Requests\UpdateMultipleSettingsRequest;
use App\Http\Resources\SettingResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class SettingController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth:sanctum',
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_unless(Gate::allows('settings.view'), 403, 'Anda tidak memiliki akses untuk melihat pengaturan');

        // Get or create default settings
        app(EnsureDefaultSettingsAction::class)->execute();

        // Get settings grouped by group
        $settings = app(GetSettingsAction::class)->execute();

        // Transform settings using resource and resolve to array for Inertia
        $transformedSettings = $settings->map(function ($group) {
            return $group->map(function ($setting) {
                return (new SettingResource($setting))->resolve();
            });
        });

        return Inertia::render('Admin/Settings/Index', [
            'settings' => $transformedSettings,
        ]);
    }

    /**
     * Update multiple settings at once
     */
    public function updateMultiple(UpdateMultipleSettingsRequest $request)
    {
        abort_unless(Gate::allows('settings.edit'), 403, 'Anda tidak memiliki akses untuk mengubah pengaturan');

        $settingsData = $request->input('settings', []);

        app(UpdateMultipleSettingsAction::class)->execute($settingsData, $request);

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil diperbarui');
    }
}
