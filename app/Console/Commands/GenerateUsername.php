<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Str;

class GenerateUsername extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-username';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate username for users with empty or null username';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNull('username')->orWhere('username', '')->get();

        if ($users->isEmpty()) {
            $this->info('No users found without a username.');
            return;
        }

        $this->info("Found {$users->count()} users without a username. Generating...");

        $this->withProgressBar($users, function ($user) {
            $baseUsername = Str::slug($user->name);
            if (empty($baseUsername)) {
                $baseUsername = $user->email ? explode('@', $user->email)[0] : 'user';
                $baseUsername = Str::slug($baseUsername) ?: 'user';
            }

            $username = $baseUsername;
            $counter = 1;

            // Periksa ketersediaan username di DB, kecualikan id user ini
            while (User::where('username', $username)->where('id', '!=', $user->id)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            $user->username = $username;
            $user->save();
        });

        $this->newLine();
        $this->info("Username generation completed successfully.");
    }
}
