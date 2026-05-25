<?php

namespace Tests\Feature\Admin;

use App\Models\BoardingHouse;
use App\Models\Cluster;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class BatchRoomTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $boardingHouse;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Gate::before(fn () => true);
        $cluster = Cluster::create(['name' => 'Test Cluster']);
        $this->boardingHouse = BoardingHouse::create([
            'owner_id' => $this->user->id,
            'name' => 'Test House',
            'cluster_id' => $cluster->id,
            'number' => 1,
            'address' => 'Test Address',
            'gender' => 'L',
        ]);
    }

    public function test_can_batch_store_rooms_without_prices()
    {
        $response = $this->actingAs($this->user)
            ->post(route('boarding-houses.rooms.batch-store', $this->boardingHouse->id), [
                'count' => 5,
                'start_number' => 10,
                'capacity' => 2,
                'description' => 'Test Description',
            ]);

        $response->assertRedirect();
        $this->assertEquals(5, Room::where('boarding_house_id', $this->boardingHouse->id)->count());

        $firstRoom = Room::where('number', 10)->first();
        $this->assertNotNull($firstRoom);
        $this->assertEquals('Test Cluster-01-10', $firstRoom->name);

        $lastRoom = Room::where('number', 14)->first();
        $this->assertNotNull($lastRoom);
        $this->assertEquals('Test Cluster-01-14', $lastRoom->name);
    }

    public function test_can_batch_store_rooms_with_prices()
    {
        $response = $this->actingAs($this->user)
            ->post(route('boarding-houses.rooms.batch-store', $this->boardingHouse->id), [
                'count' => 2,
                'start_number' => 1,
                'prices' => [
                    ['duration' => 1, 'price' => 1000000],
                    ['duration' => 6, 'price' => 5500000],
                ],
            ]);

        $response->assertRedirect();
        $this->assertEquals(2, Room::where('boarding_house_id', $this->boardingHouse->id)->count());

        $rooms = Room::where('boarding_house_id', $this->boardingHouse->id)->get();
        foreach ($rooms as $room) {
            $this->assertEquals(2, $room->prices()->count());
            $this->assertEquals(1000000, $room->getPriceForDuration(1));
        }
    }

    public function test_batch_store_auto_increments_from_last_room()
    {
        Room::create([
            'boarding_house_id' => $this->boardingHouse->id,
            'number' => 5,
            'name' => 'Existing-05',
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('boarding-houses.rooms.batch-store', $this->boardingHouse->id), [
                'count' => 3,
            ]);

        $response->assertRedirect();
        $this->assertNotNull(Room::where('number', 6)->first());
        $this->assertNotNull(Room::where('number', 7)->first());
        $this->assertNotNull(Room::where('number', 7)->first());
        $this->assertNotNull(Room::where('number', 8)->first());
    }

    public function test_batch_store_validates_unique_numbers()
    {
        Room::create([
            'boarding_house_id' => $this->boardingHouse->id,
            'number' => 5,
            'name' => 'Existing-05',
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('boarding-houses.rooms.batch-store', $this->boardingHouse->id), [
                'count' => 3,
                'start_number' => 4, // Range 4, 5, 6 -> 5 conflicts
            ]);

        $response->assertSessionHasErrors(['start_number']);
        $this->assertEquals(1, Room::where('boarding_house_id', $this->boardingHouse->id)->count());
    }
}
