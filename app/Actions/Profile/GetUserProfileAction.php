<?php

namespace App\Actions\Profile;

use Illuminate\Support\Facades\Auth;

class GetUserProfileAction
{
    /**
     * Get authenticated user profile.
     */
    public function execute()
    {
        return Auth::user();
    }
}

