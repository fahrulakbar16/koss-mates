<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardingHouse;
use App\Models\Cluster;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserRooms;
use App\Models\payment as Payment;
use App\Models\RekapHistory;
use App\Models\TransactionLog;
use App\Models\Transaction;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ImportController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Import/FullMigration');
    }

    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'type' => 'required|string|in:migration,expense',
        ]);

        $type = $request->input('type');
        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');
        $header = fgetcsv($handle);

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            $rows[] = $data;
        }
        fclose($handle);

        $results = [
            'success' => 0,
            'error' => 0,
            'messages' => [],
        ];

        if ($type === 'migration') {
            return $this->processMigration($rows, $results);
        } else {
            return $this->processExpense($rows, $results);
        }
    }

    private function processMigration($rows, $results)
    {
        // Get default owner (for new boarding houses)
        $owner = User::role('Pemilik')->first();
        if (!$owner) {
            return back()->with('error', 'Default owner (Pemilik) not found. Please create one first.');
        }

        foreach ($rows as $index => $row) {
            if (count($row) < 11) {
                $results['error']++;
                $results['messages'][] = "Row " . ($index + 2) . ": Insufficient columns (expected 11)";
                continue;
            }

            try {
                DB::transaction(function () use ($row, $owner, &$results) {
                    $clusterName = trim($row[0]);
                    $kosName = trim($row[1]);
                    $roomNo = trim($row[2]);
                    $tenantName = trim($row[3]);
                    $university = trim($row[4]); // kuliah
                    $origin = trim($row[5]); // asal
                    $phone = trim($row[6]);
                    $plan = trim($row[7]); // e.g. "3 bulan"
                    $totalPrice = (int) str_replace(['.', ','], '', $row[8]);
                    $startDate = trim($row[9]);
                    $finishDate = trim($row[10]);

                    // 1. Cluster
                    $cluster = Cluster::firstOrCreate(['name' => $clusterName]);

                    // 2. Boarding House (Kos)
                    $bh = BoardingHouse::firstOrCreate(
                        ['name' => $kosName, 'cluster_id' => $cluster->id],
                        [
                            'owner_id' => $owner->id,
                            'pengelola_id' => 4,
                            'address' => 'Imported Address',
                            'gender' => 'C', // Default to Campur
                        ]
                    );

                    // 3. Room
                    // Jika data penyewa kosong, kamar dibuat dengan status available
                    $isTenantDataEmpty = empty($tenantName) && empty($phone) && empty($plan)
                        && empty($startDate) && empty($finishDate);

                    $room = Room::firstOrCreate(
                        ['boarding_house_id' => $bh->id, 'number' => $roomNo],
                        ['name' => 'Kamar ' . $roomNo, 'capacity' => 1, 'status' => $isTenantDataEmpty ? 'available' : 'occupied']
                    );

                    // Jika data penyewa kosong, pastikan status kamar available lalu selesai
                    if ($isTenantDataEmpty) {
                        $room->update(['status' => 'available']);
                        $results['success']++;
                        return;
                    }

                    // 4. Room Price
                    // Extract duration from plan (e.g., "3 bulan" -> 3)
                    preg_match('/\d+/', $plan, $matches);
                    $duration = isset($matches[0]) ? (int)$matches[0] : 1;

                    $roomPrice = RoomPrice::firstOrCreate(
                        ['room_id' => $room->id, 'duration' => $duration, 'price' => $totalPrice],
                        ['name' => $plan]
                    );

                    // 5. User & Tenant
                    $email = Str::slug($tenantName, '.') . '@koskita.id';
                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name' => $tenantName,
                            'username' => Str::slug($tenantName, '_'),
                            'password' => Hash::make('password'),
                            'email_verified_at' => now(),
                        ]
                    );

                    if (!$user->hasRole('penyewa')) {
                        $user->assignRole('penyewa');
                    }

                    $tenant = Tenant::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'phone' => $phone,
                            'address' => $origin . ($university ? " | Kuliah: $university" : ""),
                            'gender' => 'female', // Assume Putri
                        ]
                    );

                    // 6. UserRooms (Booking)
                    $startDateFormatted = $this->formatDate($startDate);
                    $finishDateFormatted = $this->formatDate($finishDate);

                    // Logic status:
                    // 1. Jika start_date kosong -> booked
                    // 2. Jika end_date < 2 bulan yang lalu -> checked_out
                    // 3. Lainnya -> checked_in
                    $status = 'checked_in';
                    if (empty($startDate)) {
                        $status = 'booked';
                    } elseif (!empty($finishDate)) {
                        $twoMonthsAgo = now()->subMonths(2);
                        $finishTime = strtotime($finishDateFormatted);
                        if ($finishTime < $twoMonthsAgo->timestamp) {
                            $status = 'checked_out';
                        }
                    }

                    $userRoom = UserRooms::create([
                        'user_id' => $user->id,
                        'boarding_house_id' => $bh->id,
                        'room_id' => $room->id,
                        'room_price_id' => $roomPrice->id,
                        'status' => $status,
                        'start_date' => $startDateFormatted,
                        'end_date' => $finishDateFormatted,
                        'planned_checkin_date' => $startDateFormatted,
                        'verifikasi_admin' => true,
                    ]);

                    // Update room status based on booking status
                    $roomStatus = 'occupied';
                    if ($status === 'checked_out') {
                        $roomStatus = 'available';
                    } elseif ($status === 'booked') {
                        $roomStatus = 'booked';
                    }
                    $room->update(['status' => $roomStatus]);

                    // 7. Transaction
                    $transaction = Transaction::create([
                        'user_id' => $user->id,
                        'room_id' => $room->id,
                        'room_price_id' => $roomPrice->id,
                        'total_price' => $totalPrice,
                        'status' => 'completed',
                        'type' => 'booked',
                        'payment_scheme' => 'full',
                        'user_room_id' => $userRoom->id,
                        'planned_checkin_date' => $startDateFormatted,
                        'jatuh_tempo' => $finishDateFormatted,
                    ]);

                    // 7b. Payment — rekap tanggal pembayaran
                    Payment::create([
                        'transaction_id'   => $transaction->id,
                        'payment_sequence' => 1,
                        'amount'           => $totalPrice,
                        'payment_method'   => 'cash',
                        'payment_status'   => 'success',
                        'payment_date'     => $startDateFormatted ?? now(),
                    ]);

                    // 8. RekapHistory
                    // Create records for each month based on duration
                    $startDateTime = strtotime($startDateFormatted);
                    $pricePerMonth = $duration > 0 ? ($totalPrice / $duration) : $totalPrice;

                    for ($i = 0; $i < $duration; $i++) {
                        $currentMonthTime = strtotime("+$i month", $startDateTime);
                        $monthNum = date('n', $currentMonthTime);
                        $yearNum = date('Y', $currentMonthTime);

                        RekapHistory::create([
                            'user_room_id' => $userRoom->id,
                            'month' => $monthNum,
                            'year' => $yearNum,
                            'total_price' => $pricePerMonth,
                            'total_payment' => $pricePerMonth,
                            'payment_date' => $startDateFormatted ?? now(),
                            'status' => 1,
                        ]);
                    }

                    // 9. TransactionLog
                    TransactionLog::create([
                        'transaction_id' => $transaction->id,
                        'room_id' => $room->id,
                        'boarding_house_id' => $bh->id,
                        'type' => 'payment',
                        'amount' => $totalPrice,
                        'status' => 'completed',
                        'description' => 'Imported migration record for ' . $tenantName,
                        'payment_method' => 'cash',
                        'transaction_date' => now(),
                    ]);

                    $results['success']++;
                });
            } catch (\Exception $e) {
                $results['error']++;
                $results['messages'][] = "Row " . ($index + 2) . ": " . $e->getMessage();
                Log::error("Import error at row " . ($index + 2) . ": " . $e->getMessage());
            }
        }

        return back()->with('import_results', $results);
    }

    private function processExpense($rows, $results)
    {
        // Get pengelola/admin to link as creator
        $admin = auth()->user();

        foreach ($rows as $index => $row) {
            if (count($row) < 6) {
                $results['error']++;
                $results['messages'][] = "Row " . ($index + 2) . ": Insufficient columns (expected 7)";
                continue;
            }

            try {
                DB::transaction(function () use ($row, $admin, &$results) {
                    $clusterName = trim($row[0]);
                    $kosName = trim($row[1]);
                    $roomNo = trim($row[2]);
                    $category = trim($row[3]);
                    $description = trim($row[4]);
                    $amount = (float) str_replace(['.', ','], '', $row[5]);
                    $date = trim($row[6]);

                    // 1. Find Cluster
                    $cluster = Cluster::where('name', $clusterName)->first();
                    if (!$cluster) {
                        throw new \Exception("Cluster '$clusterName' not found.");
                    }

                    // 2. Find Boarding House
                    $bh = BoardingHouse::where('name', $kosName)
                        ->where('cluster_id', $cluster->id)
                        ->first();
                    if (!$bh) {
                        throw new \Exception("Kos '$kosName' in cluster '$clusterName' not found.");
                    }

                    // 2. Find Room (optional)
                    $room = null;
                    if (!empty($roomNo)) {
                        $room = Room::where('boarding_house_id', $bh->id)->where('number', $roomNo)->first();
                    }

                    // 3. Format Date
                    $expenseDate = $this->formatDate($date) ?: now()->format('Y-m-d');

                    // 4. Create Expense
                    $expense = Expense::create([
                        'boarding_house_id' => $bh->id,
                        'room_id' => $room ? $room->id : null,
                        'user_id' => $admin->id,
                        'category' => $category,
                        'description' => $description,
                        'amount' => $amount,
                        'expense_date' => $expenseDate,
                        'status' => 'selesai',
                    ]);

                    // 5. Add to TransactionLog for reporting
                    TransactionLog::create([
                        'room_id' => $room ? $room->id : null,
                        'boarding_house_id' => $bh->id,
                        'type' => 'expense',
                        'amount' => $amount,
                        'status' => 'completed',
                        'description' => "[IMPORT] $category: $description",
                        'payment_method' => 'cash',
                        'transaction_date' => $expenseDate,
                    ]);

                    $results['success']++;
                });
            } catch (\Exception $e) {
                $results['error']++;
                $results['messages'][] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        return back()->with('import_results', $results);
    }

    private function formatDate($date)
    {
        if (!$date) return null;

        // Try d/m/Y first (Excel format)
        $d = \DateTime::createFromFormat('d/m/Y', $date);
        if ($d && $d->format('d/m/Y') === $date) {
            return $d->format('Y-m-d');
        }

        try {
            return date('Y-m-d', strtotime($date));
        } catch (\Exception $e) {
            return null;
        }
    }
}
