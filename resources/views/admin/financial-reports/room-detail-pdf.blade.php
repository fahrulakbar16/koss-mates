<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Detail Pemasukan - {{ $room->name }}</title>
    <style>
        @page {
            margin: 0.8cm;
            size: a4 portrait;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9pt;
            color: #1f2937;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }

        /* Typography */
        h1,
        h2,
        h3 {
            margin: 0;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-xs {
            font-size: 7pt;
        }

        .text-sm {
            font-size: 8pt;
        }

        /* Colors */
        .text-primary {
            color: #4f46e5;
        }

        .text-gray-400 {
            color: #9ca3af;
        }

        .text-gray-500 {
            color: #6b7280;
        }

        .text-gray-600 {
            color: #4b5563;
        }

        .text-primary-600 {
            color: #e11d48;
        }

        .text-emerald-600 {
            color: #059669;
        }

        .text-blue-600 {
            color: #2563eb;
        }

        /* Header Section */
        .header {
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .title {
            font-size: 18pt;
            font-weight: 800;
            color: #111827;
        }

        .subtitle {
            font-size: 10pt;
            color: #6b7280;
            margin-top: 2px;
        }

        /* Room Info Card */
        .room-info-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            overflow: hidden;
        }

        .room-name {
            font-size: 22pt;
            font-weight: 800;
            color: #4f46e5;
            margin-bottom: 5px;
        }

        .room-details {
            font-size: 10pt;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge {
            background-color: #dcfce7;
            color: #166534;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 8pt;
            margin-left: 10px;
        }

        /* Filters / Date Range */
        .date-range {
            background-color: #f1f5f9;
            padding: 8px 15px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 15px;
            font-weight: 700;
            font-size: 9pt;
            color: #334155;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            table-layout: fixed;
        }

        th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 7.5pt;
            padding: 12px 10px;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
        }

        td {
            padding: 12px 10px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .zebra tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* Summary Box */
        .summary-container {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        .summary-box {
            background-color: #fff1f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 20px;
            text-align: right;
        }

        .summary-label {
            font-size: 9pt;
            color: #9f1239;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 24pt;
            font-weight: 800;
            color: #e11d48;
            font-family: 'Courier', monospace;
        }

        .summary-meta {
            font-size: 8pt;
            color: #b91c1c;
            margin-top: 5px;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            border-top: 1px dashed #d1d5db;
            padding-top: 15px;
            text-align: right;
            color: #94a3b8;
            font-size: 8pt;
            font-style: italic;
        }

        .currency {
            font-family: 'Courier', 'Courier New', monospace;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="title">Detail Pemasukan per Kamar</h1>
        <p class="subtitle">Riwayat transaksi pembayaran hunian & bull; Multi-Penyewa Terintegrasi</p>
    </div>

    <div class="room-info-card">
        <div class="room-name">{{ $room['name'] }}</div>
        <div class="room-details">
            Kategori: {{ $room['category'] }}
            <span class="status-badge">{{ $room['status'] }}</span>
        </div>
    </div>

    <div class="date-range">
        Daftar Riwayat Transaksi
        @if (($filters['start_date'] ?? null) || ($filters['end_date'] ?? null))
            : {{ \Carbon\Carbon::parse($filters['start_date'] ?? 'now')->format('d/m/Y') }} &mdash;
            {{ \Carbon\Carbon::parse($filters['end_date'] ?? 'now')->format('d/m/Y') }}
        @else
            : Seluruh Periode
        @endif
    </div>

    <table class="zebra">
        <thead>
            <tr>
                <th style="width: 100px;">Tanggal</th>
                <th style="width: 140px;">Penyewa</th>
                <th>Keterangan / Deskripsi</th>
                <th style="width: 110px;" class="text-center">Metode</th>
                <th style="width: 120px;" class="text-right">Jumlah (IDR)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td class="text-gray-600 font-bold">
                        {{ \Carbon\Carbon::parse($transaction['date'])->format('d M Y') }}</td>
                    <td class="font-bold text-primary">{{ $transaction['tenant'] }}</td>
                    <td>
                        <div class="font-bold text-sm">{{ $transaction['description'] }}</div>
                        <div class="text-xs text-gray-500">Regular Monthly Payment</div>
                    </td>
                    <td class="text-center">
                        <span
                            style="font-size: 8pt; color: #64748b; border: 1px solid #e2e8f0; padding: 2px 6px; border-radius: 4px;">
                            {{ $transaction['payment_method'] }}
                        </span>
                    </td>
                    <td class="text-right currency text-emerald-600" style="font-size: 11pt;">
                        {{ number_format($transaction['amount'], 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 40px; color: #94a3b8; font-style: italic;">
                        Belum ada riwayat transaksi yang tercatat dalam sistem untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-container">
        <div class="summary-box">
            <div class="summary-label">Total Akumulasi Pemasukan Kamar</div>
            <div class="summary-value currency">{{ number_format($totalIncome, 0, ',', '.') }}</div>
            <div class="summary-meta">Tercatat {{ count($transactions) }} transaksi pembayaran valid</div>
        </div>
    </div>

    <div class="footer">
        Laporan ini dihasilkan secara otomatis melalui Sistem Tharahub <br>
        Terakhir diperbarui: {{ $currentDateTime }}
    </div>
</body>

</html>
