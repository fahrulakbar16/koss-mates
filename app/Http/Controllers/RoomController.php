<?php

namespace App\Http\Controllers;

use App\Actions\Room\BatchStoreRoomAction;
use App\Actions\Room\DeleteRoomAction;
use App\Actions\Room\GetRoomsAction;
use App\Actions\Room\StoreRoomAction;
use App\Actions\Room\StoreRoomPricesAction;
use App\Actions\Room\UpdateRoomAction;
use App\Http\Requests\Room\BatchStoreRoomRequest;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use App\Models\BoardingHouse;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\payment;
use App\Models\UserRooms;
use App\Helpers\LogActivityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Inertia\Inertia;

class RoomController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth:sanctum',
        ];
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request, BoardingHouse $boardingHouse)
    {
        abort_unless(Gate::allows('rooms.create'), 403, 'Anda tidak memiliki akses untuk menambah kamar');

        $data = $request->validated();
        $data['boarding_house_id'] = $boardingHouse->id;

        app(StoreRoomAction::class)->execute($data);

        LogActivityHelper::addToLog('Menambah kamar: ' . $request->number . ' di ' . $boardingHouse->name, $data);

        return redirect()->back()->with('success', 'Kamar berhasil ditambahkan');
    }

    /**
     * Store multiple newly created resources in storage.
     */
    public function batchStore(BatchStoreRoomRequest $request, BoardingHouse $boardingHouse)
    {
        abort_unless(Gate::allows('rooms.create'), 403, 'Anda tidak memiliki akses untuk menambah kamar');

        app(BatchStoreRoomAction::class)->execute($boardingHouse, $request->validated());

        LogActivityHelper::addToLog('Menambah batch kamar di ' . $boardingHouse->name, $request->validated());

        return redirect()->back()->with('success', 'Batch kamar berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, BoardingHouse $boardingHouse, Room $room)
    {
        abort_unless(Gate::allows('rooms.edit'), 403, 'Anda tidak memiliki akses untuk mengubah kamar');

        app(UpdateRoomAction::class)->execute($room, $request->validated());

        LogActivityHelper::addToLog('Mengubah kamar: ' . $room->name . ' di ' . $boardingHouse->name, $request->validated());

        return redirect()->back()->with('success', 'Kamar berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoardingHouse $boardingHouse, Room $room)
    {
        abort_unless(Gate::allows('rooms.delete'), 403, 'Anda tidak memiliki akses untuk menghapus kamar');

        try {
            $roomName = $room->name;
            app(DeleteRoomAction::class)->execute($room);

            LogActivityHelper::addToLog('Menghapus kamar: ' . $roomName . ' dari ' . $boardingHouse->name);

            return redirect()->back()->with('success', 'Kamar berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus kamar: ' . $e->getMessage());
        }
    }

    /**
     * Show the page to edit room prices.
     */
    public function editPrices(BoardingHouse $boardingHouse, Room $room)
    {
        abort_unless(Gate::allows('rooms.edit'), 403, 'Anda tidak memiliki akses untuk mengatur harga kamar');

        // Verify room belongs to boarding house
        if ($room->boarding_house_id !== $boardingHouse->id) {
            abort(403, 'Kamar tidak terkait dengan kos ini');
        }

        $room->load(['boardingHouse', 'prices']);

        return Inertia::render('Admin/Rooms/Prices', [
            'boardingHouse' => $boardingHouse,
            'room' => $room,
        ]);
    }

    /**
     * Store or update room prices based on duration.
     */
    public function storePrices(Request $request, BoardingHouse $boardingHouse, Room $room)
    {
        abort_unless(Gate::allows('rooms.edit'), 403, 'Anda tidak memiliki akses untuk mengatur harga kamar');

        $validator = Validator::make($request->all(), [
            'prices' => ['required', 'array'],
            'prices.*.duration' => ['required', 'integer', 'min:1'],
            'prices.*.price' => ['required', 'numeric', 'min:0'],
        ], [
            'prices.required' => 'Data harga wajib diisi.',
            'prices.*.duration.required' => 'Durasi wajib diisi.',
            'prices.*.duration.integer' => 'Durasi harus berupa angka bulat.',
            'prices.*.duration.min' => 'Durasi minimal 1 bulan.',
            'prices.*.price.required' => 'Harga wajib diisi.',
            'prices.*.price.numeric' => 'Harga harus berupa angka.',
            'prices.*.price.min' => 'Harga minimal 0.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        app(StoreRoomPricesAction::class)->execute($room, $request->input('prices', []));

        LogActivityHelper::addToLog('Mengatur harga kamar: ' . $room->name . ' di ' . $boardingHouse->name, $request->input('prices', []));

        return redirect()->back()->with('success', 'Harga kamar berhasil diperbarui');
    }

    /**
     * Show room detail with expenses.
     */
    public function show(Request $request, BoardingHouse $boardingHouse, Room $room)
    {
        // abort_unless(Gate::allows('rooms.view'), 403, 'Anda tidak memiliki akses untuk melihat detail kamar');

        // Verify room belongs to boarding house
        if ($room->boarding_house_id !== $boardingHouse->id) {
            abort(403, 'Kamar tidak terkait dengan kos ini');
        }

        $room = Room::with([
            'prices',
            'boardingHouse',
            'expenses' => function ($query) {
                $query->orderBy('expense_date', 'desc')->orderBy('created_at', 'desc');
            }
        ])->findOrFail($room->id);

        $activeUserRoom = \App\Models\UserRooms::with(['user.tenant', 'plan'])
            ->where('room_id', $room->id)
            ->whereIn('status', ['checked_in', 'checkin_open', 'booked'])
            ->first();

        // Override status to 'occupied' or 'booked' if there is an active tenant,
        // to keep it consistent with GetRoomsAction logic and DB discrepancies.
        if ($activeUserRoom) {
            if (in_array($activeUserRoom->status, ['checked_in', 'checkin_open'])) {
                $room->status = 'occupied';
            } elseif ($activeUserRoom->status === 'booked') {
                $room->status = 'booked';
            }
        }

        $transactions = Transaction::with(['payments', 'user.tenant', 'roomPrice'])
            ->where('room_id', $room->id)
            ->latest()
            ->get();

        return Inertia::render('Admin/Rooms/Show', [
            'boardingHouse' => $boardingHouse,
            'room' => $room,
            'activeUserRoom' => $activeUserRoom,
            'transactions' => $transactions,
        ]);
    }

    /**
     * Cancel room booking.
     */
    public function cancelBooking(BoardingHouse $boardingHouse, Room $room)
    {
        try {
            app(\App\Actions\Room\CancelBookingAction::class)->execute($boardingHouse, $room);

            return redirect()->back()->with('success', 'Booking berhasil dibatalkan dan kamar sekarang tersedia');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Check out tenant from room.
     */
    public function checkout(BoardingHouse $boardingHouse, Room $room)
    {
        try {
            app(\App\Actions\Room\CheckoutAction::class)->execute($boardingHouse, $room);

            return redirect()->back()->with('success', 'Penyewa berhasil check-out dan kamar sekarang tersedia');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat check-out: ' . $e->getMessage());
        }
    }

    /**
     * Update check-in date of active tenant.
     */
    public function updateCheckin(Request $request, BoardingHouse $boardingHouse, Room $room)
    {
        $request->validate([
            'start_date' => 'required|date',
            'user_room_id' => 'required|exists:user_rooms,id'
        ]);

        $userRoom = \App\Models\UserRooms::where('room_id', $room->id)
            ->where('id', $request->user_room_id)
            ->firstOrFail();

        $userRoom->update([
            'start_date' => $request->start_date
        ]);

        return redirect()->back()->with('success', 'Tanggal check-in berhasil diperbarui');
    }

    /**
     * Assign a tenant manually from admin dashboard.
     */
    public function assignTenant(Request $request, BoardingHouse $boardingHouse, Room $room)
    {
        abort_unless(Gate::allows('rooms.edit'), 403, 'Anda tidak memiliki akses untuk menambah penyewa');

        $request->validate([
            'is_new_tenant' => 'required|boolean',
            'room_price_id' => 'required|exists:rooms_price,id',
            'planned_checkin_date' => 'required|date',
            'has_payment' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            if ($request->is_new_tenant) {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'username' => 'required|string|max:255|unique:users',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:8',
                    'phone' => 'required|string|max:20',
                    'gender' => 'required|in:L,P,Male,Female',
                    'address' => 'nullable|string',
                    'id_card_number' => 'nullable|string|max:50',
                    'birth_date' => 'nullable|date',
                    'emergency_contact' => 'nullable|string|max:50',
                    'tempat_kuliah_kerja' => 'nullable|string|max:255',
                ]);
                $user = app(\App\Actions\Tenant\CreateTenantAccount::class)->execute($request->all());
                $user->update([
                    'email_verified_at' => now(),
                ]);
            } else {
                $request->validate([
                    'tenant_id' => 'required|exists:users,id',
                ]);
                $user = \App\Models\User::findOrFail($request->tenant_id);
            }

            // Create Transaction
            $data = [
                'boarding_house_id' => $boardingHouse->id,
                'room_id' => $room->id,
                'room_price_id' => $request->room_price_id,
                'planned_checkin_date' => $request->planned_checkin_date,
                'payment_scheme' => 'full',
            ];

            $transaction = app(\App\Actions\Transaction\CreateTransaction::class)->execute($user, $data, Transaction::TYPE_BOOKED);

            if ($request->has_payment) {
                $request->validate([
                    'payment_amount' => 'required|numeric|min:1',
                ]);

                $paymentAmount = $request->payment_amount;

                \App\Models\payment::create([
                    'transaction_id' => $transaction->id,
                    'payment_sequence' => 1,
                    'amount' => $paymentAmount,
                    'payment_method' => 'cash',
                    'payment_status' => 'success',
                    'payment_date' => now(),
                ]);

                if ($paymentAmount >= $transaction->total_price) {
                    $transaction->update(['status' => Transaction::STATUS_COMPLETED]);

                    $transaction->userRoom->update([
                        'status' => 'checkin_open'
                    ]);
                } else {

                    $transaction->userRoom->update([
                        'status' => 'checkin_open'
                    ]);
                    $transaction->update(['status' => Transaction::STATUS_INCOMPLETE]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Penyewa berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan penyewa: ' . $e->getMessage());
        }
    }

    /**
     * Update payment details and status for a room transaction.
     */
    public function updatePayment(Request $request, BoardingHouse $boardingHouse, Room $room, payment $payment)
    {
        // Verify payment belongs to room transaction
        $transaction = $payment->transaction;
        if (!$transaction || $transaction->room_id !== $room->id) {
            abort(400, 'Pembayaran tidak terkait dengan kamar ini');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0|max:' . $transaction->total_price,
            'payment_status' => 'required|in:pending,success,failed',
            'payment_method' => 'required|in:cash,gateway',
            'payment_date' => 'required|date',
            'proof' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'amount.required' => 'Jumlah pembayaran wajib diisi.',
            'amount.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'amount.min' => 'Jumlah pembayaran minimal 0.',
            'amount.max' => 'Jumlah pembayaran tidak boleh melebihi jumlah tagihan (Rp ' . number_format($transaction->total_price, 0, ',', '.') . ').',
            'payment_status.required' => 'Status pembayaran wajib diisi.',
            'payment_status.in' => 'Status pembayaran tidak valid.',
            'payment_method.required' => 'Metode pembayaran wajib diisi.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'payment_date.required' => 'Tanggal pembayaran wajib diisi.',
            'payment_date.date' => 'Tanggal pembayaran harus berupa tanggal yang valid.',
            'proof.image' => 'File bukti pembayaran harus berupa gambar.',
            'proof.mimes' => 'Format gambar yang didukung: jpeg, png, jpg, gif, webp.',
            'proof.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        DB::beginTransaction();
        try {
            $payment->amount = $request->amount;
            $payment->payment_method = $request->payment_method;
            $payment->payment_date = Carbon::parse($request->payment_date);

            if ($request->hasFile('proof')) {
                // Delete old proof if exists
                if ($payment->proof && Storage::disk('public')->exists($payment->proof)) {
                    Storage::disk('public')->delete($payment->proof);
                }
                $payment->proof = $request->file('proof')->store('payment-proofs', 'public');
            } elseif ($request->input('remove_proof') === '1' || $request->input('remove_proof') === true) {
                if ($payment->proof && Storage::disk('public')->exists($payment->proof)) {
                    Storage::disk('public')->delete($payment->proof);
                }
                $payment->proof = null;
            }

            $oldStatus = $payment->payment_status;
            $newStatus = $request->payment_status;

            if ($newStatus === 'success' && $oldStatus !== 'success') {
                $payment->save(); // Save details first
                app(\App\Actions\Payment\ProcessPaymentSuccess::class)->execute($payment);
            } else {
                $payment->payment_status = $newStatus;
                $payment->save();

                // Recalculate transaction status
                $totalPaid = $transaction->payments()
                    ->where('payment_status', 'success')
                    ->sum('amount');

                if ($totalPaid >= $transaction->total_price) {
                    $transaction->update(['status' => 'completed']);
                } else {
                    $transaction->update(['status' => 'incomplete']);
                }

                // If rekap history exists, update it
                $roomPrice = $transaction->roomPrice;
                if ($roomPrice) {
                    $duration = $roomPrice->duration;
                    $paymentDate = $payment->payment_date ?: now();

                    for ($i = 0; $i < $duration; $i++) {
                        $currentDate = Carbon::parse($paymentDate)->addMonthsNoOverflow($i);
                        $transaction->userRoom->rekapHistories()->where([
                            'month' => $currentDate->month,
                            'year' => $currentDate->year,
                        ])->update([
                            'total_payment' => $totalPaid,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pembayaran berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created transaction/bill for a room.
     */
    public function storeTransaction(Request $request, BoardingHouse $boardingHouse, Room $room)
    {
        $activeUserRoom = \App\Models\UserRooms::where('room_id', $room->id)
            ->whereIn('status', ['checked_in', 'checkin_open', 'booked'])
            ->first();

        if (!$activeUserRoom) {
            return redirect()->back()->with('error', 'Kamar ini tidak memiliki penyewa aktif.');
        }

        $request->validate([
            'room_price_id' => 'required|exists:rooms_price,id',
            'total_price' => 'required|numeric|min:0',
            'payment_scheme' => 'required|in:full,installment',
            'type' => 'required|in:booked,extended',
            'jatuh_tempo' => 'required|date',
        ], [
            'room_price_id.required' => 'Paket harga kamar wajib dipilih.',
            'room_price_id.exists' => 'Paket harga kamar tidak valid.',
            'total_price.required' => 'Total tagihan wajib diisi.',
            'total_price.numeric' => 'Total tagihan harus berupa angka.',
            'total_price.min' => 'Total tagihan minimal 0.',
            'payment_scheme.required' => 'Skema pembayaran wajib dipilih.',
            'payment_scheme.in' => 'Skema pembayaran tidak valid.',
            'type.required' => 'Tipe tagihan wajib dipilih.',
            'type.in' => 'Tipe tagihan tidak valid.',
            'jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi.',
            'jatuh_tempo.date' => 'Tanggal jatuh tempo harus berupa tanggal yang valid.',
        ]);

        Transaction::create([
            'user_id' => $activeUserRoom->user_id,
            'room_id' => $room->id,
            'user_room_id' => $activeUserRoom->id,
            'room_price_id' => $request->room_price_id,
            'total_price' => $request->total_price,
            'payment_scheme' => $request->payment_scheme,
            'type' => $request->type,
            'status' => 'pending',
            'jatuh_tempo' => Carbon::parse($request->jatuh_tempo),
        ]);

        return redirect()->back()->with('success', 'Tagihan sewa berhasil ditambahkan.');
    }

    /**
     * Update an existing transaction/bill.
     */
    public function updateTransaction(Request $request, BoardingHouse $boardingHouse, Room $room, Transaction $transaction)
    {
        if ($transaction->room_id !== $room->id) {
            abort(400, 'Transaksi tidak terkait dengan kamar ini.');
        }

        $request->validate([
            'room_price_id' => 'required|exists:rooms_price,id',
            'total_price' => 'required|numeric|min:0',
            'payment_scheme' => 'required|in:full,installment',
            'type' => 'required|in:booked,extended',
            'jatuh_tempo' => 'required|date',
            'status' => 'required|in:pending,completed,incomplete,cancelled',
        ], [
            'room_price_id.required' => 'Paket harga kamar wajib dipilih.',
            'room_price_id.exists' => 'Paket harga kamar tidak valid.',
            'total_price.required' => 'Total tagihan wajib diisi.',
            'total_price.numeric' => 'Total tagihan harus berupa angka.',
            'total_price.min' => 'Total tagihan minimal 0.',
            'payment_scheme.required' => 'Skema pembayaran wajib dipilih.',
            'payment_scheme.in' => 'Skema pembayaran tidak valid.',
            'type.required' => 'Tipe tagihan wajib dipilih.',
            'type.in' => 'Tipe tagihan tidak valid.',
            'jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi.',
            'jatuh_tempo.date' => 'Tanggal jatuh tempo harus berupa tanggal yang valid.',
            'status.required' => 'Status transaksi wajib diisi.',
            'status.in' => 'Status transaksi tidak valid.',
        ]);

        $status = $request->status;

        // If not manually cancelled, automatically calculate status based on payments
        if ($status !== 'cancelled') {
            $totalPaid = $transaction->payments()
                ->where('payment_status', 'success')
                ->sum('amount');

            if ($totalPaid >= $request->total_price) {
                $status = 'completed';
            } elseif ($totalPaid > 0) {
                $status = 'incomplete';
            } else {
                $status = 'pending';
            }
        }

        $transaction->update([
            'room_price_id' => $request->room_price_id,
            'total_price' => $request->total_price,
            'payment_scheme' => $request->payment_scheme,
            'type' => $request->type,
            'jatuh_tempo' => Carbon::parse($request->jatuh_tempo),
            'status' => $status,
        ]);

        return redirect()->back()->with('success', 'Tagihan transaksi berhasil diperbarui.');
    }

    /**
     * Delete a transaction/bill.
     */
    public function destroyTransaction(BoardingHouse $boardingHouse, Room $room, Transaction $transaction)
    {
        if ($transaction->room_id !== $room->id) {
            abort(400, 'Transaksi tidak terkait dengan kamar ini.');
        }

        $transaction->delete();

        return redirect()->back()->with('success', 'Tagihan transaksi berhasil dihapus.');
    }

    /**
     * Store a newly created payment for a transaction.
     */
    public function storePayment(Request $request, BoardingHouse $boardingHouse, Room $room, Transaction $transaction)
    {
        if ($transaction->room_id !== $room->id) {
            abort(400, 'Transaksi tidak terkait dengan kamar ini.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0|max:' . $transaction->total_price,
            'payment_status' => 'required|in:pending,success,failed',
            'payment_method' => 'required|in:cash,gateway',
            'payment_date' => 'required|date',
            'proof' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'amount.required' => 'Jumlah pembayaran wajib diisi.',
            'amount.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'amount.min' => 'Jumlah pembayaran minimal 0.',
            'amount.max' => 'Jumlah pembayaran tidak boleh melebihi total tagihan (Rp ' . number_format($transaction->total_price, 0, ',', '.') . ').',
            'payment_status.required' => 'Status pembayaran wajib diisi.',
            'payment_status.in' => 'Status pembayaran tidak valid.',
            'payment_method.required' => 'Metode pembayaran wajib diisi.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'payment_date.required' => 'Tanggal pembayaran wajib diisi.',
            'payment_date.date' => 'Tanggal pembayaran harus berupa tanggal yang valid.',
            'proof.image' => 'File bukti pembayaran harus berupa gambar.',
            'proof.mimes' => 'Format gambar yang didukung: jpeg, png, jpg, gif, webp.',
            'proof.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        DB::beginTransaction();
        try {
            $proofPath = null;
            if ($request->hasFile('proof')) {
                $proofPath = $request->file('proof')->store('payment-proofs', 'public');
            }

            $payment = payment::create([
                'transaction_id' => $transaction->id,
                'payment_sequence' => $transaction->payment_scheme === 'installment' ? 'installment' : 'full',
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'payment_date' => Carbon::parse($request->payment_date),
                'proof' => $proofPath,
            ]);

            if ($request->payment_status === 'success') {
                app(\App\Actions\Payment\ProcessPaymentSuccess::class)->execute($payment);
            } else {
                $payment->update(['payment_status' => $request->payment_status]);

                // Recalculate transaction status
                $totalPaid = $transaction->payments()
                    ->where('payment_status', 'success')
                    ->sum('amount');

                if ($totalPaid >= $transaction->total_price) {
                    $transaction->update(['status' => 'completed']);
                } else {
                    $transaction->update(['status' => 'incomplete']);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete a payment.
     */
    public function destroyPayment(BoardingHouse $boardingHouse, Room $room, payment $payment)
    {
        $transaction = $payment->transaction;
        if (!$transaction || $transaction->room_id !== $room->id) {
            abort(400, 'Pembayaran tidak terkait dengan kamar ini.');
        }

        DB::beginTransaction();
        try {
            if ($payment->proof && Storage::disk('public')->exists($payment->proof)) {
                Storage::disk('public')->delete($payment->proof);
            }

            $payment->delete();

            // Recalculate transaction status
            $totalPaid = $transaction->payments()
                ->where('payment_status', 'success')
                ->sum('amount');

            if ($totalPaid >= $transaction->total_price) {
                $transaction->update(['status' => 'completed']);
            } elseif ($totalPaid > 0) {
                $transaction->update(['status' => 'incomplete']);
            } else {
                $transaction->update(['status' => 'pending']);
            }

            // If rekap history exists, update it
            $roomPrice = $transaction->roomPrice;
            if ($roomPrice) {
                $duration = $roomPrice->duration;
                $paymentDate = $payment->payment_date ?: now();

                for ($i = 0; $i < $duration; $i++) {
                    $currentDate = Carbon::parse($paymentDate)->addMonthsNoOverflow($i);
                    $transaction->userRoom->rekapHistories()->where([
                        'month' => $currentDate->month,
                        'year' => $currentDate->year,
                    ])->update([
                        'total_payment' => $totalPaid,
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
