<?php

namespace App\Actions\Profile;

use App\Models\User;
use App\Helpers\LogActivityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateProfileAction
{
    /**
     * Update user profile.
     *
     * @param User $user
     * @param Request $request
     * @return User
     */
    public function execute(User $user, Request $request): User
    {
        // Update user fields (only fillable ones)
        $fields = ['email', 'name'];
        foreach ($fields as $field) {
            if ($request->filled($field)) {
                $user->{$field} = $request->input($field);
            }
        }

        // Handle profile photo deletion or upload
        if ($this->wantsToDeletePhoto($request)) {
            $this->deleteProfilePhoto($user);
            $user->profile_photo_path = null;
        } elseif ($request->hasFile('profile_photo_url')) {
            $this->deleteProfilePhoto($user);
            $user->profile_photo_path = $this->storeProfilePhoto($request);
        }

        $user->save();

        if ($user->hasRole('Penyewa') || $user->tenant) {
            $tenantData = $request->only([
                'phone',
                'address',
                'id_card_number',
                'birth_date',
                'gender',
                'emergency_contact',
                'is_moved'
            ]);

            if ($request->hasFile('file_ktp')) {
                if ($user->tenant?->file_ktp) {
                    Storage::disk('public')->delete($user->tenant->file_ktp);
                }
                $tenantData['file_ktp'] = $request->file('file_ktp')->store('ktp', 'public');
            }

            if (!empty($tenantData)) {
                $user->tenant()->updateOrCreate(['user_id' => $user->id], $tenantData);
                $user->load('tenant'); // Refresh the relationship
            }
        }

        LogActivityHelper::addToLog('Memperbarui profil: ' . $user->name, [
            'id' => $user->id,
            'name' => $user->name,
        ]);

        return $user->fresh();
    }

    /**
     * Determine if the user wants to delete their profile photo.
     *
     * @param Request $request
     * @return bool
     */
    protected function wantsToDeletePhoto(Request $request): bool
    {
        $val = $request->input('delete_photo');
        return in_array($val, [true, 'true', 1, '1'], true);
    }

    /**
     * Delete the user's existing profile photo from storage.
     *
     * @param User $user
     * @return void
     */
    protected function deleteProfilePhoto(User $user): void
    {
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
    }

    /**
     * Store a new profile photo and return its path.
     *
     * @param Request $request
     * @return string
     */
    protected function storeProfilePhoto(Request $request): string
    {
        return $request->file('profile_photo_url')->store('profile-photos', 'public');
    }
}
