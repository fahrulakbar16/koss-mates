<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Rekap Keuangan - {{ $boardingHouse->name }}</title>
    <style>
        @page {
            margin: 0.8cm;
            size: a4 landscape;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8.5pt;
            color: #1f2937;
            margin: 0;
            padding: 0;
            line-height: 1.3;
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
            color: #dc2626;
        }

        .text-emerald-600 {
            color: #059669;
        }

        .text-primary-600 {
            color: #e11d48;
        }

        .text-blue-600 {
            color: #2563eb;
        }

        /* Header Section */
        .header {
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .house-name {
            font-size: 18pt;
            font-weight: 800;
            color: #111827;
        }

        .house-address {
            font-size: 9pt;
            color: #64748b;
            margin-top: 2px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 7pt;
            padding: 10px 8px;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .zebra tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* Badges */
        .badge {
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 6.5pt;
            display: inline-block;
            text-transform: uppercase;
        }

        .badge-lunas {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-belum {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* Sections */
        .section-header {
            margin-bottom: 10px;
            padding: 6px 12px;
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            font-size: 9.5pt;
            font-weight: 800;
            color: #1e40af;
        }

        .section-header.expense {
            background-color: #fff1f2;
            border-left-color: #f43f5e;
            color: #9f1239;
        }

        .section-header.summary {
            background-color: #f8fafc;
            border-left-color: #64748b;
            color: #334155;
        }

        /* Grid Alignment */
        .main-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .main-grid td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .col-expense {
            width: 55%;
        }

        .col-spacer {
            width: 10%;
        }

        .col-summary {
            width: auto;
        }

        /* Summary Card */
        .summary-card {
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .summary-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-item:last-child {
            border-bottom: none;
            background-color: #f8fafc;
            border-top: 2px solid #e2e8f0;
        }

        .summary-label {
            font-size: 7.5pt;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .summary-value {
            font-size: 13pt;
            font-weight: 800;
            font-family: 'Courier', monospace;
        }

        .net-value {
            font-size: 16pt;
        }

        .currency {
            font-family: 'Courier', 'Courier New', monospace;
            font-weight: 700;
        }

        .row-total {
            background-color: #f8fafc;
            font-weight: 800;
        }

        .signature-box {
            margin-top: 30px;
            text-align: center;
            padding: 15px;
            border: 1px dashed #e2e8f0;
            border-radius: 8px;
            color: #94a3b8;
        }

        .page-break {
            page-break-after: always;
        }

        .room-status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: 800;
            text-transform: uppercase;
            display: inline-block;
        }

        .status-available {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-booked {
            background-color: #fef9c3;
            color: #854d0e;
        }

        .status-occupied {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-maintenance {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .summary-label-room {
            font-size: 8.5pt;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .summary-value-room {
            font-size: 20pt;
            font-weight: 800;
            color: #111827;
        }

        .room-info-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-left: 5px solid #4f46e5;
        }

        .room-title {
            font-size: 20pt;
            font-weight: 800;
            color: #111827;
            margin-bottom: 5px;
        }

        .summary-box-room {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 15px 20px;
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 60%; border: none; padding: 0;">
                    <h1 class="house-name text-uppercase text-primary">{{ $boardingHouse->name }}</h1>
                    <p class="house-address">{{ $boardingHouse->address }}</p>
                </td>
                <td style="width: 40%; border: none; padding: 0;" class="text-right">
                    <div class="text-gray-400 text-xs">LAPORAN REKAPITULASI KEUANGAN</div>
                    <div class="font-bold text-lg text-uppercase" style="margin-top: 5px; color: #111827;">
                        {{ $periodLabel }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-header">DATA PENYEWA & PEMBAYARAN BULANAN</div>
    <table class="zebra">
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">NO</th>
                <th style="width: 55px;" class="text-center">KAMAR</th>
                <th style="width: 150px;">NAMA PENYEWA</th>
                <th style="width: 130px;">ASAL / DOMISILI</th>
                <th style="width: 90px;">NOMOR HP</th>
                <th style="width: 110px;" class="text-right">BAYAR KOS (IDR)</th>
                <th style="width: 80px;" class="text-center">STATUS</th>
                <th class="text-center">MASA SEWA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tenants as $index => $tenant)
                <tr>
                    <td class="text-center text-gray-400">{{ $index + 1 }}</td>
                    <td class="text-center font-bold text-primary-600">{{ $tenant['room_number'] }}</td>
                    <td class="font-bold">{{ $tenant['name'] }}</td>
                    <td class="text-xs text-gray-600">{{ $tenant['origin'] }}</td>
                    <td class="text-xs text-gray-500">{{ $tenant['phone'] }}</td>
                    <td class="text-right currency">{{ number_format($tenant['amount'], 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span
                            class="badge {{ $tenant['payment_status'] === 'Lunas' ? 'badge-lunas' : 'badge-belum' }}">
                            {{ $tenant['payment_status'] }}
                        </span>
                    </td>
                    <td class="text-center text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($tenant['start_kos'])->format('d/m/y') }} &mdash;
                        {{ \Carbon\Carbon::parse($tenant['finish_kos'])->format('d/m/y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="row-total">
                <td colspan="5" class="text-right text-xs" style="padding: 10px; color: #64748b;">SUBTOTAL PEMASUKAN:
                </td>
                <td class="text-right currency text-emerald-600" style="font-size: 11pt; padding: 10px;">
                    {{ number_format($summary['total_income'], 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <table class="main-grid">
        <tr>
            <td class="col-expense">
                <div class="section-header expense">REKAPITULASI PENGELUARAN OPERASIONAL</div>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 35px;" class="text-center">NO</th>
                            <th>KATEGORI & RINCIAN</th>
                            <th style="width: 110px;" class="text-right">JUMLAH (IDR)</th>
                        </tr>
                    </thead>
                    <tbody class="zebra">
                        @forelse($expenses as $index => $expense)
                            <tr>
                                <td class="text-center text-gray-400">{{ $index + 1 }}</td>
                                <td>
                                    <div class="font-bold">{{ $expense['category'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $expense['description'] }}</div>
                                </td>
                                <td class="text-right currency text-primary-600">
                                    {{ number_format($expense['amount'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-gray-400" style="padding: 20px;">
                                    <em>Tidak ada data pengeluaran.</em>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="row-total">
                            <td colspan="2" class="text-right text-xs" style="padding: 10px; color: #64748b;">TOTAL
                                PENGELUARAN:</td>
                            <td class="text-right currency text-primary-600" style="font-size: 11pt; padding: 10px;">
                                {{ number_format($summary['total_expense'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </td>
            <td class="col-spacer"></td>
            <td class="col-summary">
                <div class="section-header summary">RINGKASAN FINANSIAL</div>
                <div class="summary-card">
                    <div class="summary-item">
                        <div class="summary-label">TOTAL PENDAPATAN (CASH IN)</div>
                        <div class="summary-value text-emerald-600 text-right">
                            {{ number_format($summary['total_income'], 0, ',', '.') }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">TOTAL PENGELUARAN (CASH OUT)</div>
                        <div class="summary-value text-primary-600 text-right">-
                            {{ number_format($summary['total_expense'], 0, ',', '.') }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label text-uppercase font-bold" style="color: #0f172a;">PORSI PENGELOLA ({{ 100 - ($boardingHouse->persentasi_pemilik ?? 100) }}%)</div>
                        <div
                            class="summary-value net-value text-right {{ $summary['net_profit'] >= 0 ? 'text-blue-600' : 'text-primary-600' }}">
                           {{ number_format($summary['management_share'], 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label text-uppercase font-bold" style="color: #0f172a;">LABA BERSIH</div>
                        <div
                            class="summary-value net-value text-right {{ $summary['net_profit'] >= 0 ? 'text-blue-600' : 'text-primary-600' }}">
                           {{ number_format($summary['owner_share'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="signature-box">
                    <div class="text-xs font-bold"
                        style="color: #64748b; margin-bottom: 25px; text-decoration: underline;">VALIDASI MANAJEMEN
                    </div>
                    <div class="text-xs" style="font-style: italic;">Dicetak via KosMates &bull;
                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                </div>
            </td>
        </tr>
    </table>

    @foreach ($rooms as $room)
        <div class="page-break"></div>

        <div class="header" style="border-bottom: 1px solid #e2e8f0; margin-bottom: 15px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 70%; border: none; padding: 0;">
                        <div class="text-gray-400 text-xs text-uppercase font-bold">DETAIL TRANSAKSI PER KAMAR</div>
                        <h2 style="font-size: 14pt; margin-top: 2px;">{{ $boardingHouse->name }}</h2>
                    </td>
                    <td style="width: 30%; border: none; padding: 0;" class="text-right">
                        <div class="font-bold text-sm text-uppercase text-primary">
                            {{ $periodLabel }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="room-info-card">
            <table style="width: 100%; border: none; margin-bottom: 0;">
                <tr>
                    <td style="width: 50%; border: none; padding: 0;">
                        <div class="room-title">KAMAR {{ $room->name }}</div>
                        {{-- <div
                            style="font-size: 9pt; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                            Kapasitas: {{ $room->capacity }} Orang
                        </div> --}}
                    </td>
                    <td style="width: 50%; border: none; padding: 0;" class="text-right">
                        <span class="room-status-badge status-{{ $room->status }}">
                            STATUS: {{ $roomStatuses[$room->status] ?? $room->status }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section-header"
            style="background-color: #f8fafc; border-left-color: #4f46e5; color: #1e293b; margin-top: 10px;">
            RIWAYAT TRANSAKSI PERIODE {{ strtoupper($periodLabel) }}
        </div>

        <table class="zebra">
            <thead>
                <tr>
                    <th style="width: 120px;">TANGGAL</th>
                    <th style="width: 180px;">NAMA PENYEWA</th>
                    <th>KETERANGAN TRANSAKSI</th>
                    <th style="width: 150px;" class="text-center">METODE PEMBAYARAN</th>
                    <th style="width: 150px;" class="text-right">JUMLAH (IDR)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($room->transactionLogs as $log)
                    @php
                        $paymentMethod = $log->payment_method === 'gateway' ? 'Transfer Online' : 'Tunai / Manual';
                    @endphp
                    <tr>
                        <td class="text-gray-600 font-bold">
                            {{ \Carbon\Carbon::parse($log->transaction_date)->format('d M Y') }}
                        </td>
                        <td class="font-bold text-primary">{{ $log->transaction?->user?->name ?? '-' }}</td>
                        <td>
                            <div class="font-bold text-sm">
                                {{ $log->description }}
                            </div>
                            <div class="text-xs text-gray-400">ID TRX: #{{ $log->id }}</div>
                        </td>
                        <td class="text-center">
                            <span
                                style="font-size: 8pt; color: #475569; background-color: #f1f5f9; padding: 3px 8px; border-radius: 4px; font-weight: 600;">
                                {{ strtoupper($paymentMethod) }}
                            </span>
                        </td>
                        <td class="text-right currency text-emerald-600" style="font-size: 11pt;">
                            {{ number_format($log->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center"
                            style="padding: 40px; color: #94a3b8; font-style: italic;">
                            Belum ada riwayat transaksi yang tercatat untuk kamar ini pada periode {{ $periodLabel }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @php
            $roomTotal = $room->transactionLogs->sum('amount');
        @endphp
        <div class="summary-box-room">
            <div class="summary-label-room">TOTAL PENDAPATAN KAMAR ({{ $room->name }})</div>
            <div class="summary-value-room currency text-emerald-600">
                <span style="font-size: 12pt; color: #10b981;">IDR</span>
                {{ number_format($roomTotal, 0, ',', '.') }}
            </div>
            <div style="font-size: 8.5pt; color: #64748b; margin-top: 5px; font-weight: 600;">
                Berdasarkan validasi {{ count($room->transactionLogs) }} transaksi pembayaran selesai
            </div>
        </div>
    @endforeach
</body>

</html>
