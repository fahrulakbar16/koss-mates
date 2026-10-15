<?php

namespace App\Observers;

use App\Models\TransactionLog;

class TransactionLogObserver
{
    public function created(TransactionLog $log): void
    {
        $request = request();
        $route = $request->route();
        $routeName = $route?->getName() ?? '';
        $actor = $request->user();
        $source = match (true) {
            $routeName === 'midtrans.callback' => 'notifikasi pembayaran dari Midtrans',
            str_starts_with($routeName, 'admin.import.') => 'impor data melalui halaman Impor',
            str_starts_with($routeName, 'admin.transactions.') => 'input melalui halaman Transaksi',
            str_starts_with($routeName, 'boarding-houses.rooms.') => 'input melalui halaman detail kamar',
            str_starts_with($routeName, 'boarding-houses.') => 'input melalui halaman detail boarding house',
            str_starts_with($request->path(), 'api/') => 'permintaan API',
            $route !== null => 'permintaan aplikasi',
            default => 'proses sistem/command; halaman asal tidak tersedia',
        };
        $direction = match ($log->type) {
            'payment', 'income', 'deposit' => 'Uang masuk',
            'expense', 'refund' => 'Uang keluar',
            default => 'Catatan keuangan',
        };
        $amount = number_format((float) $log->amount, 2, ',', '.');
        $description = "{$direction} sejumlah Rp {$amount} dicatat dari {$source}.";
        $description .= $actor
            ? " Pencatat: {$actor->name} (user ID {$actor->id})."
            : ' Tidak ada pengguna login pada proses ini.';
        $description .= " Jenis: {$log->type}; status saat dicatat: {$log->status}; metode: ".($log->payment_method ?? 'tidak tersedia').'.';
        if ($route) {
            // Path only: do not store query strings, tokens, or callback payloads.
            $description .= " Endpoint: {$request->method()} /{$request->path()}.";
        }
        $description .= ' Referensi: '.($log->reference_number ?? '-').'.';
        if ($log->description) {
            $description .= " Catatan: {$log->description}.";
        }
        $log->financialSource()->create(['keterangan' => $description]);
    }
}
