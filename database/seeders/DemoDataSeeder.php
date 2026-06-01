<?php

namespace Database\Seeders;

use App\Models\BoardingHouse;
use App\Models\Cluster;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------------------------------------
        // Pemilik (owner of the physical property)
        // -------------------------------------------------------
        $pemilik = User::find(3); // existing Pemilik user

        // -------------------------------------------------------
        // Pengelola 1 (existing ID=4)
        // -------------------------------------------------------
        $pengelola1 = User::find(4);

        // -------------------------------------------------------
        // Pengelola 2 (create new)
        // -------------------------------------------------------
        $pengelola2 = User::firstOrCreate(
            ['email' => 'pengelola2@kossmates.com'],
            [
                'name'     => 'Pengelola Dua',
                'username' => 'pengelola2',
                'password' => Hash::make('password'),
            ]
        );
        if (!$pengelola2->hasRole('Pengelola')) {
            $pengelola2->assignRole('Pengelola');
        }

        // -------------------------------------------------------
        // Cluster & boarding-house definitions per pengelola
        // -------------------------------------------------------
        $clusterDefs = [
            $pengelola1->id => [
                [
                    'cluster' => [
                        'name'        => 'Cluster Bougainvillea',
                        'description' => 'Kompleks kos eksklusif di pusat kota dengan desain modern tropis.',
                        'address'     => 'Jl. Bougainvillea No. 12, Bandung',
                    ],
                    'houses' => [
                        [
                            'name'              => 'Kos Bougainvillea A',
                            'number'            => 'BGA',
                            'description'       => 'Kos putra berlantai 2, dekat kampus dan pusat perbelanjaan.',
                            'address'           => 'Jl. Bougainvillea No. 12A, Bandung',
                            'gender'            => 'L',
                            'persentasi_pemilik'=> 30,
                            'rooms' => [
                                ['name' => 'Kamar 101', 'number' => '101', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['AC', 'Kasur', 'Lemari', 'WiFi'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 1200000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 3300000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 6000000],
                                 ]],
                                ['name' => 'Kamar 102', 'number' => '102', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['AC', 'Kasur', 'Lemari', 'WiFi', 'Meja Belajar'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 1300000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 3600000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 6600000],
                                 ]],
                            ],
                        ],
                        [
                            'name'              => 'Kos Bougainvillea B',
                            'number'            => 'BGB',
                            'description'       => 'Kos putri nyaman dengan taman hijau dan parkir luas.',
                            'address'           => 'Jl. Bougainvillea No. 12B, Bandung',
                            'gender'            => 'P',
                            'persentasi_pemilik'=> 30,
                            'rooms' => [
                                ['name' => 'Kamar 201', 'number' => '201', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['AC', 'Kasur', 'Lemari', 'WiFi', 'Kamar Mandi Dalam'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 1500000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 4200000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 7800000],
                                 ]],
                                ['name' => 'Kamar 202', 'number' => '202', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['AC', 'Kasur', 'Lemari', 'WiFi', 'Kamar Mandi Dalam', 'Balkon'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 1700000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 4800000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 9000000],
                                 ]],
                            ],
                        ],
                    ],
                ],
                [
                    'cluster' => [
                        'name'        => 'Cluster Anggrek Mas',
                        'description' => 'Hunian strategis dekat stasiun kereta dan kampus ternama.',
                        'address'     => 'Jl. Anggrek Mas Raya No. 5, Bandung',
                    ],
                    'houses' => [
                        [
                            'name'              => 'Kos Anggrek Premium',
                            'number'            => 'ANP',
                            'description'       => 'Kos campur premium dengan fasilitas lengkap dan keamanan 24 jam.',
                            'address'           => 'Jl. Anggrek Mas Raya No. 5A, Bandung',
                            'gender'            => 'C',
                            'persentasi_pemilik'=> 30,
                            'rooms' => [
                                ['name' => 'Kamar Standard', 'number' => 'S-01', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['AC', 'Kasur', 'Lemari', 'WiFi'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 1400000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 3900000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 7200000],
                                 ]],
                                ['name' => 'Kamar Deluxe', 'number' => 'D-01', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['AC', 'Kasur', 'Lemari', 'WiFi', 'Kamar Mandi Dalam', 'TV'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 1900000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 5400000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 10200000],
                                 ]],
                            ],
                        ],
                        [
                            'name'              => 'Kos Anggrek Ekonomi',
                            'number'            => 'ANE',
                            'description'       => 'Pilihan terjangkau untuk mahasiswa dengan lokasi strategis.',
                            'address'           => 'Jl. Anggrek Mas Raya No. 5B, Bandung',
                            'gender'            => 'C',
                            'persentasi_pemilik'=> 30,
                            'rooms' => [
                                ['name' => 'Kamar Ekonomi A', 'number' => 'EA-01', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['Kipas Angin', 'Kasur', 'Lemari', 'WiFi'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 700000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 1950000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 3600000],
                                 ]],
                                ['name' => 'Kamar Ekonomi B', 'number' => 'EB-01', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['Kipas Angin', 'Kasur', 'Lemari', 'WiFi'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 700000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 1950000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 3600000],
                                 ]],
                            ],
                        ],
                    ],
                ],
            ],

            $pengelola2->id => [
                [
                    'cluster' => [
                        'name'        => 'Cluster Mawar Indah',
                        'description' => 'Kawasan kos asri di pinggiran kota dengan udara sejuk.',
                        'address'     => 'Jl. Mawar Indah No. 8, Cimahi',
                    ],
                    'houses' => [
                        [
                            'name'              => 'Kos Mawar Putra',
                            'number'            => 'MWP',
                            'description'       => 'Kos putra 3 lantai dengan rooftop dan area parkir motor.',
                            'address'           => 'Jl. Mawar Indah No. 8A, Cimahi',
                            'gender'            => 'L',
                            'persentasi_pemilik'=> 30,
                            'rooms' => [
                                ['name' => 'Kamar A1', 'number' => 'A1', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['AC', 'Kasur', 'Lemari', 'WiFi', 'Dapur Bersama'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 1100000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 3000000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 5400000],
                                 ]],
                                ['name' => 'Kamar A2', 'number' => 'A2', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['AC', 'Kasur', 'Lemari', 'WiFi', 'Dapur Bersama'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 1100000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 3000000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 5400000],
                                 ]],
                            ],
                        ],
                        [
                            'name'              => 'Kos Mawar Putri',
                            'number'            => 'MWU',
                            'description'       => 'Kos putri dengan sistem keamanan kartu akses dan CCTV.',
                            'address'           => 'Jl. Mawar Indah No. 8B, Cimahi',
                            'gender'            => 'P',
                            'persentasi_pemilik'=> 30,
                            'rooms' => [
                                ['name' => 'Kamar B1', 'number' => 'B1', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['AC', 'Kasur', 'Lemari', 'WiFi', 'Kamar Mandi Dalam'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 1250000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 3450000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 6300000],
                                 ]],
                                ['name' => 'Kamar B2', 'number' => 'B2', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['AC', 'Kasur', 'Lemari', 'WiFi', 'Kamar Mandi Dalam', 'Meja Rias'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 1350000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 3750000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 6900000],
                                 ]],
                            ],
                        ],
                    ],
                ],
                [
                    'cluster' => [
                        'name'        => 'Cluster Melati Residence',
                        'description' => 'Hunian modern dekat kawasan industri dan universitas swasta.',
                        'address'     => 'Jl. Melati Residence No. 22, Cimahi',
                    ],
                    'houses' => [
                        [
                            'name'              => 'Kos Melati Studio',
                            'number'            => 'MLS',
                            'description'       => 'Kos tipe studio dengan dapur pribadi di setiap kamar.',
                            'address'           => 'Jl. Melati Residence No. 22A, Cimahi',
                            'gender'            => 'C',
                            'persentasi_pemilik'=> 30,
                            'rooms' => [
                                ['name' => 'Studio 1', 'number' => 'STD-01', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['AC', 'Kasur King', 'Lemari Besar', 'WiFi', 'Dapur Mini', 'Kulkas'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 2200000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 6300000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 12000000],
                                 ]],
                                ['name' => 'Studio 2', 'number' => 'STD-02', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['AC', 'Kasur King', 'Lemari Besar', 'WiFi', 'Dapur Mini', 'Kulkas'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 2200000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 6300000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 12000000],
                                 ]],
                            ],
                        ],
                        [
                            'name'              => 'Kos Melati Standar',
                            'number'            => 'MLT',
                            'description'       => 'Kos standar harga bersahabat dengan fasilitas memadai.',
                            'address'           => 'Jl. Melati Residence No. 22B, Cimahi',
                            'gender'            => 'C',
                            'persentasi_pemilik'=> 30,
                            'rooms' => [
                                ['name' => 'Kamar C1', 'number' => 'C1', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['Kipas Angin', 'Kasur', 'Lemari', 'WiFi'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 800000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 2250000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 4200000],
                                 ]],
                                ['name' => 'Kamar C2', 'number' => 'C2', 'capacity' => 1, 'status' => 'available',
                                 'facilities' => ['AC', 'Kasur', 'Lemari', 'WiFi'],
                                 'prices' => [
                                     ['name' => 'Bulanan', 'duration' => 1, 'price' => 1050000],
                                     ['name' => 'Triwulan', 'duration' => 3, 'price' => 2925000],
                                     ['name' => 'Semesteran', 'duration' => 6, 'price' => 5400000],
                                 ]],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // -------------------------------------------------------
        // Create everything
        // -------------------------------------------------------
        foreach ($clusterDefs as $pengelolaId => $clusters) {
            foreach ($clusters as $clusterDef) {
                $cluster = Cluster::create([
                    'pengelola_id' => $pengelolaId,
                    'name'         => $clusterDef['cluster']['name'],
                    'description'  => $clusterDef['cluster']['description'],
                    'address'      => $clusterDef['cluster']['address'],
                ]);

                foreach ($clusterDef['houses'] as $houseDef) {
                    $house = BoardingHouse::create([
                        'pengelola_id'       => $pengelolaId,
                        'cluster_id'         => $cluster->id,
                        'owner_id'           => $pemilik->id,
                        'name'               => $houseDef['name'],
                        'number'             => $houseDef['number'],
                        'description'        => $houseDef['description'],
                        'address'            => $houseDef['address'],
                        'gender'             => $houseDef['gender'],
                        'persentasi_pemilik' => $houseDef['persentasi_pemilik'],
                    ]);

                    foreach ($houseDef['rooms'] as $roomDef) {
                        $room = Room::create([
                            'boarding_house_id' => $house->id,
                            'name'              => $roomDef['name'],
                            'number'            => $roomDef['number'],
                            'description'       => 'Kamar ' . $roomDef['number'] . ' di ' . $houseDef['name'],
                            'capacity'          => $roomDef['capacity'],
                            'status'            => $roomDef['status'],
                            'facilities'        => $roomDef['facilities'],
                        ]);

                        foreach ($roomDef['prices'] as $priceDef) {
                            RoomPrice::create([
                                'room_id'  => $room->id,
                                'name'     => $priceDef['name'],
                                'duration' => $priceDef['duration'],
                                'price'    => $priceDef['price'],
                            ]);
                        }
                    }
                }
            }
        }

        $this->command->info('Demo data seeded successfully!');
        $this->command->table(
            ['Entity', 'Count'],
            [
                ['Pengelola', User::role('Pengelola')->count()],
                ['Cluster', Cluster::count()],
                ['Boarding Houses', BoardingHouse::count()],
                ['Rooms', Room::count()],
                ['Room Prices', RoomPrice::count()],
            ]
        );
    }
}
