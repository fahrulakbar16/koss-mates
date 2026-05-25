<?php

namespace App\Actions\Setting;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\LogActivityHelper;

class UpdateMultipleSettingsAction
{
    /**
     * Update multiple settings at once.
     *
     * @param array<int, array<string, mixed>> $settingsData
     * @param Request $request
     */
    public function execute(array $settingsData, Request $request): void
    {
        foreach ($settingsData as $index => $settingData) {
            $key = $settingData['key'] ?? null;
            if (!$key) {
                continue;
            }

            $setting = Setting::where('key', $key)->first();

            if (!$setting) {
                // Determine type and group based on key
                $type = str_starts_with($key, 'logo_') ? 'image' : 'text';
                $group = str_starts_with($key, 'logo_') ? 'logo'
                    : (str_starts_with($key, 'contact_') ? 'contact'
                        : (str_starts_with($key, 'color_') ? 'color'
                            : 'general'));

                $setting = Setting::create([
                    'key' => $key,
                    'type' => $type,
                    'group' => $group,
                ]);
            }

            $value = $this->processSettingValue($setting, $settingData, $index, $request);

            $setting->update(['value' => $value]);
        }

        LogActivityHelper::addToLog('Memperbarui pengaturan sistem', [
            'updated_keys' => collect($settingsData)->pluck('key')->toArray(),
        ]);
    }

    /**
     * Process setting value based on type.
     *
     * @param Setting $setting
     * @param array<string, mixed> $settingData
     * @param int $index
     * @param Request $request
     * @return string|null
     */
    private function processSettingValue(Setting $setting, array $settingData, int $index, Request $request): ?string
    {
        // Handle image upload
        if ($setting->type === 'image') {
            // Check if it's a file upload
            if ($request->hasFile("settings.{$index}.value")) {
                $file = $request->file("settings.{$index}.value");

                // Delete old image if exists
                if ($setting->value) {
                    Storage::disk('public')->delete($setting->value);
                }

                $path = $file->store('settings', 'public');
                return $path;
            } else {
                // Check if it's an existing path (from database)
                $inputValue = $settingData['value'] ?? null;
                if ($inputValue && (str_starts_with($inputValue, 'settings/') || str_starts_with($inputValue, '/storage/settings/'))) {
                    // Keep the existing path
                    return str_replace('/storage/', '', $inputValue);
                } else {
                    // Keep existing value if no new file uploaded and no valid path provided
                    return $setting->value;
                }
            }
        }

        // Text field
        return $settingData['value'] ?? $setting->value;
    }
}
