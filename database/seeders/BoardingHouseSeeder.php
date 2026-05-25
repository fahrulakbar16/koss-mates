<?php

namespace Database\Seeders;

use App\Models\BoardingHouse;
use App\Models\Cluster;
use App\Models\User;
use Illuminate\Database\Seeder;

class BoardingHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get pemilik user
        $pemilik = User::whereHas('roles', function ($query) {
            $query->where('name', 'Pemilik');
        })->first();

        if (!$pemilik) {
            $this->command->warn('Pemilik user not found. Please run UserSeeder first.');
            return;
        }

        // Get clusters
        $clusters = Cluster::all();
        if ($clusters->isEmpty()) {
            $this->command->warn('Clusters not found. Please run ClusterSeeder first.');
            return;
        }

        $boardingHouses = [
            [
                'name' => 'Kos Mawar Indah',
                'description' => 'Kos nyaman dengan fasilitas lengkap, dekat kampus dan pusat perbelanjaan. Tersedia kamar AC dan non-AC dengan harga terjangkau.',
                'address' => 'Jl. Raya Cibubur No. 123, Cibubur, Jakarta Timur',
                'latitude' => -6.367614,
                'longitude' => 106.901436,
                'cluster_name' => 'Cibubur',
            ],
            [
                'name' => 'Kost Harmoni Residence',
                'description' => 'Kost modern dengan kamar ber-AC, WiFi gratis, parkir luas, dan area dapur bersama. Cocok untuk mahasiswa dan pekerja.',
                'address' => 'Jl. Margonda Raya No. 456, Depok, Jawa Barat',
                'latitude' => -6.367887,
                'longitude' => 106.832526,
                'cluster_name' => 'Depok',
            ],
            [
                'name' => 'Kos Sejahtera',
                'description' => 'Kos keluarga dengan lingkungan aman dan nyaman. Dekat dengan fasilitas umum seperti pasar, masjid, dan transportasi umum.',
                'address' => 'Jl. Ahmad Yani No. 789, Tangerang, Banten',
                'latitude' => -6.179118,
                'longitude' => 106.629663,
                'cluster_name' => 'Tangerang',
            ],
            [
                'name' => 'Kost Rizki Abadi',
                'description' => 'Kost strategis dengan akses mudah ke tol dan pusat kota. Tersedia berbagai tipe kamar sesuai kebutuhan.',
                'address' => 'Jl. Jenderal Sudirman No. 321, Bekasi, Jawa Barat',
                'latitude' => -6.234910,
                'longitude' => 107.000782,
                'cluster_name' => 'Bekasi',
            ],
            [
                'name' => 'Kos Elit Premium',
                'description' => 'Kos premium dengan fasilitas mewah. Setiap kamar dilengkapi AC, TV, lemari es, dan WiFi high speed. Cocok untuk profesional muda.',
                'address' => 'Jl. Kemang Raya No. 10, Jakarta Selatan',
                'latitude' => -6.260659,
                'longitude' => 106.805830,
                'cluster_name' => 'Jakarta Selatan',
            ],
            [
                'name' => 'Kost Nyaman Lestari',
                'description' => 'Kost dengan konsep ramah lingkungan, suasana sejuk, dan harga terjangkau. Dekat dengan berbagai kampus di Bandung.',
                'address' => 'Jl. Dago No. 55, Bandung, Jawa Barat',
                'latitude' => -6.903889,
                'longitude' => 107.618607,
                'cluster_name' => 'Bandung',
            ],
            [
                'name' => 'Kos Sederhana Asri',
                'description' => 'Kos sederhana namun nyaman dengan harga ekonomis. Cocok untuk mahasiswa dengan budget terbatas.',
                'address' => 'Jl. Alternatif Cibubur No. 88, Cibubur, Jakarta Timur',
                'latitude' => -6.362839,
                'longitude' => 106.907833,
                'cluster_name' => 'Cibubur',
            ],
            [
                'name' => 'Kost Griya Mandiri',
                'description' => 'Kost dengan kamar luas dan fasilitas lengkap. Area parkir luas, ruang tamu bersama, dan lingkungan yang tenang.',
                'address' => 'Jl. Universitas Indonesia No. 12, Depok, Jawa Barat',
                'latitude' => -6.361387,
                'longitude' => 106.824153,
                'cluster_name' => 'Depok',
            ],
        ];

        $clusterCounters = [];

        $thumbnails = [
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=2000&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1502672260266-1c1e52409818?q=80&w=2000&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=2000&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1505691938895-1758d7def511?q=80&w=2000&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?q=80&w=2000&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?q=80&w=2000&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1513694203232-719a280e022f?q=80&w=2000&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1598928506311-c55dd1217e4f?q=80&w=2000&auto=format&fit=crop',
        ];

        foreach ($boardingHouses as $index => $boardingHouseData) {
            $clusterName = $boardingHouseData['cluster_name'];
            $cluster = $clusters->firstWhere('name', $clusterName);

            if (!$cluster) {
                continue;
            }

            if (!isset($clusterCounters[$clusterName])) {
                $clusterCounters[$clusterName] = 1;
            } else {
                $clusterCounters[$clusterName]++;
            }

            $number = (string)$clusterCounters[$clusterName];
            $formattedName = $cluster->name . '-' . $number;

            // Download thumbnail (kamar kos)
            $filename = null;
            try {
                // Using loremflickr to get specific "bedroom" or "interior" images
                $imageUrl = 'https://loremflickr.com/800/600/bedroom,interior?lock=' . $index;

                // Allow redirects since loremflickr redirects to the actual image URL
                $response = \Illuminate\Support\Facades\Http::timeout(15)
                    ->withOptions(['allow_redirects' => true])
                    ->get($imageUrl);

                if ($response->successful()) {
                    $filename = 'boarding_houses/' . \Illuminate\Support\Str::uuid() . '.jpg';
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $response->body());
                } else {
                    $this->command->warn('Gagal mengunduh gambar kamar untuk ' . $formattedName . ' (HTTP ' . $response->status() . ')');
                }
            } catch (\Exception $e) {
                // Ignore download failure, will fall back to placeholder
                $this->command->warn('Gagal mengunduh gambar kamar untuk ' . $formattedName . ': ' . $e->getMessage());
            }

            unset($boardingHouseData['cluster_name']);
            unset($boardingHouseData['name']);

            $boardingHouse = BoardingHouse::firstOrCreate(
                [
                    'name' => $formattedName,
                    'address' => $boardingHouseData['address'],
                ],
                array_merge($boardingHouseData, [
                    'name' => $formattedName,
                    'number' => $number,
                    'thumbnail' => $filename,
                    'owner_id' => $pemilik->id,
                    'cluster_id' => $cluster->id,
                ])
            );

            // Generate 4 additional images for each boarding house
            if ($boardingHouse->images()->count() === 0) {
                for ($i = 1; $i <= 4; $i++) {
                    $imgFilename = null;
                    try {
                        // Unique lock for each image to ensure variety
                        $lockId = ($index * 2) + $i;
                        $imageUrl = 'https://loremflickr.com/800/600/bedroom,interior?lock=' . $lockId;

                        $response = \Illuminate\Support\Facades\Http::timeout(15)
                            ->withOptions(['allow_redirects' => true])
                            ->get($imageUrl);

                        if ($response->successful()) {
                            $imgFilename = 'boarding_houses/' . \Illuminate\Support\Str::uuid() . '.jpg';
                            \Illuminate\Support\Facades\Storage::disk('public')->put($imgFilename, $response->body());

                            \App\Models\BoardingHouseImage::create([
                                'boarding_house_id' => $boardingHouse->id,
                                'image' => $imgFilename,
                            ]);
                        } else {
                            $this->command->warn('Gagal mengunduh gambar tambahan ' . $i . ' untuk ' . $formattedName . ' (HTTP ' . $response->status() . ')');
                        }
                    } catch (\Exception $e) {
                        $this->command->warn('Gagal mengunduh gambar tambahan ' . $i . ' untuk ' . $formattedName . ': ' . $e->getMessage());
                    }
                }
            }
        }
    }
}
