<?php

namespace Database\Seeders;

use App\Models\Cluster;
use Illuminate\Database\Seeder;

class ClusterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clusters = [
            [
                'name' => 'Cibubur',
                'description' => 'Kawasan Cibubur dengan akses mudah ke berbagai kampus dan perkantoran di Jakarta Timur',
                'address' => 'Cibubur, Jakarta Timur',
            ],
            [
                'name' => 'Depok',
                'description' => 'Area Depok yang dekat dengan kampus-kampus ternama seperti UI, UIKA, dan lainnya',
                'address' => 'Depok, Jawa Barat',
            ],
            [
                'name' => 'Tangerang',
                'description' => 'Lokasi strategis di Tangerang dengan akses mudah ke Jakarta dan bandara',
                'address' => 'Tangerang, Banten',
            ],
            [
                'name' => 'Bekasi',
                'description' => 'Kawasan Bekasi dengan banyak pilihan kos dekat kampus dan pusat kota',
                'address' => 'Bekasi, Jawa Barat',
            ],
            [
                'name' => 'Jakarta Selatan',
                'description' => 'Area Jakarta Selatan dengan berbagai pilihan kos eksklusif dan modern',
                'address' => 'Jakarta Selatan, DKI Jakarta',
            ],
            [
                'name' => 'Bandung',
                'description' => 'Kota Bandung dengan suasana sejuk dan dekat berbagai universitas terkemuka',
                'address' => 'Bandung, Jawa Barat',
            ],
        ];

        foreach ($clusters as $cluster) {
            Cluster::firstOrCreate(
                ['name' => $cluster['name']],
                $cluster
            );
        }
    }
}
