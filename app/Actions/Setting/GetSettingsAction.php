<?php

namespace App\Actions\Setting;

use App\Models\Setting;
use Illuminate\Support\Collection;

class GetSettingsAction
{
    /**
     * Get all settings grouped by group.
     */
    public function execute(): Collection
    {
        return Setting::orderBy('group')
            ->orderBy('key')
            ->get()
            ->groupBy('group');
    }
}

