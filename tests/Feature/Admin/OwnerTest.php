<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Owner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OwnerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles if they don't exist (assuming Spatie Permission)
        if (!Role::where('name', 'Admin')->exists()) {
            Role::create(['name' => 'Admin']);
        }
        if (!Role::where('name', 'Pemilik')->exists()) {
            Role::create(['name' => 'Pemilik']);
        }
    }

    public function test_admin_can_view_owner_list()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->actingAs($admin)->get(route('admin.owners.index'));

        $response->assertStatus(200);
        $response->assertInertia(
            fn($page) => $page
                ->component('Admin/Owners/Index')
                ->has('owners')
        );
    }

    public function test_admin_can_create_owner()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $ownerData = [
            'name' => 'Test Owner',
            'username' => 'testowner',
            'email' => 'owner@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Test Owner Account',
        ];

        $response = $this->actingAs($admin)->post(route('admin.owners.store'), $ownerData);

        $response->assertRedirect(route('admin.owners.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'owner@example.com',
            'username' => 'testowner',
        ]);

        $user = User::where('email', 'owner@example.com')->first();
        $this->assertTrue($user->hasRole('Pemilik'));

        $this->assertDatabaseHas('owners', [
            'user_id' => $user->id,
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
        ]);
    }

    public function test_admin_can_update_owner()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $ownerUser = User::factory()->create();
        $ownerUser->assignRole('Pemilik');
        $ownerUser->owner()->create([
            'bank_name' => 'Old Bank',
            'bank_account_number' => '000',
            'bank_account_name' => 'Old Name',
        ]);

        $updateData = [
            'name' => 'Updated Owner Name',
            'username' => $ownerUser->username,
            'email' => $ownerUser->email,
            'bank_name' => 'New Bank',
            'bank_account_number' => '111',
            'bank_account_name' => 'New Name',
        ];

        $response = $this->actingAs($admin)->put(route('admin.owners.update', $ownerUser->id), $updateData);

        $response->assertRedirect(route('admin.owners.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $ownerUser->id,
            'name' => 'Updated Owner Name',
        ]);

        $this->assertDatabaseHas('owners', [
            'user_id' => $ownerUser->id,
            'bank_name' => 'New Bank',
        ]);
    }

    public function test_validation_errors()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->actingAs($admin)->post(route('admin.owners.store'), [
            'name' => '', // Required
            'email' => 'invalid-email',
            'password' => 'short',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }
}
