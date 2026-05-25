<?php

namespace App\Http\Controllers;

use App\Models\BoardingHouse;
use App\Models\Expense;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth:sanctum',
        ];
    }

    /**
     * Display a listing of expenses for a room.
     */
    public function index(Request $request, BoardingHouse $boardingHouse, Room $room)
    {
        abort_unless(Gate::allows('rooms.view'), 403, 'Anda tidak memiliki akses untuk melihat expense');

        $expenses = $room->expenses()
            ->orderBy('expense_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($expenses);
    }

    /**
     * Store a newly created expense.
     */
    public function store(Request $request, BoardingHouse $boardingHouse, Room $room)
    {
        abort_unless(Gate::allows('rooms.edit'), 403, 'Anda tidak memiliki akses untuk menambah expense');

        $validator = Validator::make($request->all(), [
            'category' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'expense_date' => ['required', 'date'],
            'receipt' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'category.required' => 'Kategori wajib diisi.',
            'amount.required' => 'Jumlah wajib diisi.',
            'amount.numeric' => 'Jumlah harus berupa angka.',
            'amount.min' => 'Jumlah minimal 0.',
            'expense_date.required' => 'Tanggal pengeluaran wajib diisi.',
            'expense_date.date' => 'Tanggal pengeluaran harus berupa tanggal yang valid.',
            'receipt.image' => 'File harus berupa gambar.',
            'receipt.mimes' => 'Format gambar yang didukung: jpeg, png, jpg, gif, webp.',
            'receipt.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['boarding_house_id'] = $boardingHouse->id;
        $data['room_id'] = $room->id;

        if ($request->hasFile('receipt')) {
            $data['receipt_path'] = $request->file('receipt')->store('expenses/receipts', 'public');
        }

        Expense::create($data);

        return redirect()->back()->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    /**
     * Update the specified expense.
     */
    public function update(Request $request, BoardingHouse $boardingHouse, Room $room, Expense $expense)
    {
        abort_unless(Gate::allows('rooms.edit'), 403, 'Anda tidak memiliki akses untuk mengubah expense');

        // Verify expense belongs to room
        if ($expense->room_id !== $room->id || $expense->boarding_house_id !== $boardingHouse->id) {
            abort(403, 'Expense tidak terkait dengan kamar ini');
        }

        $validator = Validator::make($request->all(), [
            'category' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'expense_date' => ['required', 'date'],
            'receipt' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'category.required' => 'Kategori wajib diisi.',
            'amount.required' => 'Jumlah wajib diisi.',
            'amount.numeric' => 'Jumlah harus berupa angka.',
            'amount.min' => 'Jumlah minimal 0.',
            'expense_date.required' => 'Tanggal pengeluaran wajib diisi.',
            'expense_date.date' => 'Tanggal pengeluaran harus berupa tanggal yang valid.',
            'receipt.image' => 'File harus berupa gambar.',
            'receipt.mimes' => 'Format gambar yang didukung: jpeg, png, jpg, gif, webp.',
            'receipt.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Handle receipt update
        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            $data['receipt_path'] = $request->file('receipt')->store('expenses/receipts', 'public');
        } elseif ($request->input('remove_receipt') === '1') {
            // Remove receipt if requested
            if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            $data['receipt_path'] = null;
        }

        $expense->update($data);

        return redirect()->back()->with('success', 'Pengeluaran berhasil diperbarui');
    }

    /**
     * Remove the specified expense.
     */
    public function destroy(BoardingHouse $boardingHouse, Room $room, Expense $expense)
    {
        abort_unless(Gate::allows('rooms.edit'), 403, 'Anda tidak memiliki akses untuk menghapus expense');

        // Verify expense belongs to room
        if ($expense->room_id !== $room->id || $expense->boarding_house_id !== $boardingHouse->id) {
            abort(403, 'Expense tidak terkait dengan kamar ini');
        }

        // Delete receipt file if exists
        if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        return redirect()->back()->with('success', 'Pengeluaran berhasil dihapus');
    }
}
