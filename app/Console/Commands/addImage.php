<?php

namespace App\Console\Commands;

use App\Models\BoardingHouse;
use App\Models\BoardingHouseImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class addImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tambahkan thumbnail dan gambar ke boarding house yang belum memiliki gambar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $boardingHouses = BoardingHouse::all();

        if ($boardingHouses->isEmpty()) {
            $this->warn('Tidak ada data boarding house yang ditemukan.');
            return;
        }

        $this->info('Memproses ' . $boardingHouses->count() . ' boarding house...');

        foreach ($boardingHouses as $index => $boardingHouse) {
            // Tambahkan thumbnail jika belum ada
            if (!$boardingHouse->thumbnail) {
                $this->info("[{$boardingHouse->name}] Mengunduh thumbnail...");

                try {
                    $imageUrl = 'https://loremflickr.com/800/600/bedroom,interior?lock=' . $index;

                    $response = Http::timeout(15)
                        ->withOptions(['allow_redirects' => true])
                        ->get($imageUrl);

                    if ($response->successful()) {
                        $filename = 'boarding_houses/' . Str::uuid() . '.jpg';
                        Storage::disk('public')->put($filename, $response->body());

                        $boardingHouse->update(['thumbnail' => $filename]);

                        $this->info("[{$boardingHouse->name}] Thumbnail berhasil ditambahkan: {$filename}");
                    } else {
                        $this->warn("[{$boardingHouse->name}] Gagal mengunduh thumbnail (HTTP {$response->status()})");
                    }
                } catch (\Exception $e) {
                    $this->warn("[{$boardingHouse->name}] Gagal mengunduh thumbnail: " . $e->getMessage());
                }
            } else {
                $this->line("[{$boardingHouse->name}] Thumbnail sudah ada, dilewati.");
            }

            // Tambahkan images jika belum ada
            if ($boardingHouse->images()->count() === 0) {
                $this->info("[{$boardingHouse->name}] Mengunduh 4 gambar tambahan...");

                for ($i = 1; $i <= 4; $i++) {
                    try {
                        $lockId = ($index * 10) + $i;
                        $imageUrl = 'https://loremflickr.com/800/600/bedroom,interior?lock=' . $lockId;

                        $response = Http::timeout(15)
                            ->withOptions(['allow_redirects' => true])
                            ->get($imageUrl);

                        if ($response->successful()) {
                            $imgFilename = 'boarding_houses/' . Str::uuid() . '.jpg';
                            Storage::disk('public')->put($imgFilename, $response->body());

                            BoardingHouseImage::create([
                                'boarding_house_id' => $boardingHouse->id,
                                'image' => $imgFilename,
                            ]);

                            $this->info("[{$boardingHouse->name}] Gambar {$i} berhasil ditambahkan.");
                        } else {
                            $this->warn("[{$boardingHouse->name}] Gagal mengunduh gambar {$i} (HTTP {$response->status()})");
                        }
                    } catch (\Exception $e) {
                        $this->warn("[{$boardingHouse->name}] Gagal mengunduh gambar {$i}: " . $e->getMessage());
                    }
                }
            } else {
                $this->line("[{$boardingHouse->name}] Gambar sudah ada ({$boardingHouse->images()->count()} gambar), dilewati.");
            }
        }

        $this->info('Selesai!');
    }
}

