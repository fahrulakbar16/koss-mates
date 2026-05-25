<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardingHouse;
use App\Models\Room;
use App\Http\Requests\Admin\FinancialReportRequest;
use App\Http\Resources\Admin\FinancialReport\BoardingHouseFinancialResource;
use App\Http\Resources\Admin\FinancialReport\TransactionResource;
use App\Http\Resources\Admin\FinancialReport\ExpenseResource;
use App\Http\Resources\Admin\FinancialReport\TenantRecapResource;
use App\Http\Resources\Admin\FinancialReport\RoomDetailResource;
use App\Actions\Admin\FinancialReport\GetBoardingHouseFinancialList;
use App\Actions\Admin\FinancialReport\GetBoardingHouseDetailedReport;
use App\Actions\Admin\FinancialReport\GetBoardingHouseRecap;
use App\Actions\Admin\FinancialReport\GetRoomTransactionDetail;
use App\Actions\Admin\FinancialReport\GetTransactionByBoardingHouse;
use App\Http\Resources\Admin\FinancialReport\RoomTransaksiResource;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class FinancialReportController extends Controller
{
    /**
     * Menampilkan daftar sumber daya.
     */
    public function index(FinancialReportRequest $request)
    {
        $boardingHouses = app(GetBoardingHouseFinancialList::class)->execute($request->validated(), $request->user());

        return Inertia::render('Admin/FinancialReport/Index', [
            'boardingHouses' => BoardingHouseFinancialResource::collection($boardingHouses)->response()->getData(true),
            'clusters' => \App\Models\Cluster::orderBy('name')->get(['id', 'name']),
            'filters' => $request->validated(),
        ]);
    }

    /**
     * Menampilkan sumber daya yang ditentukan.
     */
    public function show($id, FinancialReportRequest $request)
    {
        $boardingHouse = BoardingHouse::findOrFail($id);
        $this->authorizeOwner($boardingHouse);

        $reportData = app(GetBoardingHouseDetailedReport::class)->execute($boardingHouse, $request->validated());

        return Inertia::render('Admin/FinancialReport/Show', [
            'boardingHouse' => $boardingHouse,
            'incomes' => TransactionResource::collection($reportData['incomes']),
            'expenses' => ExpenseResource::collection($reportData['expenses']),
            'summary' => [
                'total_income' => $totalIncome = $reportData['total_income'],
                'total_expense' => $totalExpense = $reportData['total_expense'],
                'total_profit' => $netProfit = $totalIncome - $totalExpense,
                'owner_share' => $ownerShare = ($netProfit * ($boardingHouse->persentasi_pemilik ?? 100)) / 100,
                'management_share' => $netProfit - $ownerShare,
                'net_profit' => $netProfit,
            ],
            'filters' => $request->validated(),
        ]);
    }

    /**
     * Menampilkan rekapitulasi untuk rumah kos.
     */
    public function recap($id, FinancialReportRequest $request)
    {
        $boardingHouse = BoardingHouse::findOrFail($id);
        $this->authorizeOwner($boardingHouse);

        $recapData = app(GetBoardingHouseRecap::class)->execute($boardingHouse, $request->validated());

        $transactions = app(GetTransactionByBoardingHouse::class)->execute($boardingHouse, $request->validated());

        $totalIncome = $recapData['income'];
        $totalExpense = $recapData['expenses']->sum('amount');

        return Inertia::render('Admin/FinancialReport/Recap', [
            'boardingHouse' => $boardingHouse,
            'transactions' => RoomTransaksiResource::collection($transactions),
            'expenses' => ExpenseResource::collection($recapData['expenses']),
            'summary' => [
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'total_profit' => $netProfit = $totalIncome - $totalExpense,
                'owner_share' => $ownerShare = ($netProfit * ($boardingHouse->persentasi_pemilik ?? 100)) / 100,
                'management_share' => $netProfit - $ownerShare,
                'net_profit' => $netProfit,
            ],
            'filters' => $request->validated(),
        ]);
    }

    /**
     * Menampilkan riwayat transaksi untuk kamar tertentu.
     */
    public function roomDetail($roomId, FinancialReportRequest $request)
    {
        $room = Room::with(['boardingHouse', 'prices'])->findOrFail($roomId);
        $this->authorizeOwner($room->boardingHouse);

        $detailData = app(GetRoomTransactionDetail::class)->execute($room, $request->validated());


        return Inertia::render('Admin/FinancialReport/RoomDetail', [
            'room' => new RoomDetailResource($room),
            'transactions' => TransactionResource::collection($detailData['transactions']),
            'totalIncome' => $detailData['total_income'],
            'filters' => $request->validated(),
            'boardingHouse' => [
                'id' => $room->boardingHouse->id,
                'name' => $room->boardingHouse->name,
            ],
        ]);
    }

    /**
     * Mencetak rekapitulasi untuk rumah kos tertentu sebagai PDF.
     *
     * @param int $boardingHouseId
     * @param FinancialReportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function printRecap($boardingHouseId, FinancialReportRequest $request)
    {
        $boardingHouse = BoardingHouse::findOrFail($boardingHouseId);
        $this->authorizeOwner($boardingHouse);

        $recapData = app(GetBoardingHouseRecap::class)->execute($boardingHouse, $request->validated());
        $tenants = TenantRecapResource::collection($recapData['tenants'])->toArray($request);

        $totalIncome = collect($tenants)->where('payment_status', 'Lunas')->sum('amount');
        $totalExpense = $recapData['expenses']->sum('amount');

        $monthNames = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        ];
        $monthName = $monthNames[intval($request->month) - 1];

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $useDateRange = $startDate && $endDate;

        $rooms = $boardingHouse->rooms()
            ->with(['penyewaAktif.tenant', 'prices', 'transactionLogs' => function ($query) use ($request, $startDate, $endDate, $useDateRange) {
                $query->with(['transaction.user']);
                if ($useDateRange) {
                    $query->whereBetween('transaction_date', [$startDate, $endDate]);
                } else {
                    $query->whereMonth('transaction_date', $request->month)
                        ->whereYear('transaction_date', $request->year);
                }
                $query->whereIn('type', ['payment', 'income'])
                    ->where('status', 'completed')
                    ->orderBy('transaction_date', 'desc');
            }])
            ->orderBy('name')
            ->get();

        $netProfit = $totalIncome - $totalExpense;
        $ownerPercentage = $boardingHouse->persentasi_pemilik ?? 100;
        $ownerShare = ($netProfit * $ownerPercentage) / 100;
        $managementShare = $netProfit - $ownerShare;

        // Build period label for PDF
        if ($useDateRange) {
            $periodLabel = \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') . ' — ' . \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y');
        } else {
            $periodLabel = $monthName . ' ' . $request->year;
        }

        $data = [
            'boardingHouse' => $boardingHouse,
            'tenants' => $tenants,
            'expenses' => ExpenseResource::collection($recapData['expenses'])->toArray($request),
            'summary' => [
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'total_profit' => $netProfit,
                'owner_share' => $ownerShare,
                'management_share' => $managementShare,
                'net_profit' => $netProfit,
            ],
            'filters' => $request->validated(),
            'monthName' => $monthName,
            'periodLabel' => $periodLabel,
            'currentDate' => now()->translatedFormat('d F Y H:i'),
            'rooms' => $rooms,
            'roomStatuses' => [
                'available' => 'Tersedia',
                'booked' => 'Dipesan',
                'occupied' => 'Terisi',
                'maintenance' => 'Maintenance',
            ],
        ];

        $pdfFilename = 'recap-' . str_replace(' ', '-', strtolower($boardingHouse->name));
        if ($useDateRange) {
            $pdfFilename .= '-' . $startDate . '-to-' . $endDate;
        } else {
            $pdfFilename .= '-' . strtolower($monthName) . '-' . $request->year;
        }
        $pdfFilename .= '.pdf';

        $pdf = Pdf::loadView('admin.financial-reports.recap-pdf', $data)->setPaper('a4', 'landscape');
        return $pdf->stream($pdfFilename);
    }

    /**
     * Mencetak riwayat detail kamar sebagai PDF.
     *
     * @param int $roomId
     * @param FinancialReportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function printRoomDetail($roomId, FinancialReportRequest $request)
    {
        $room = Room::with(['boardingHouse', 'prices'])->findOrFail($roomId);
        $this->authorizeOwner($room->boardingHouse);

        $detailData = app(GetRoomTransactionDetail::class)->execute($room, $request->validated());

        $data = [
            'room' => new RoomDetailResource($room),
            'transactions' => TransactionResource::collection($detailData['transactions'])->toArray($request),
            'totalIncome' => $detailData['total_income'],
            'filters' => $request->validated(),
            'currentDateTime' => now()->translatedFormat('d F Y H:i'),
        ];

        Log::info("Data: " . json_encode($data));

        $pdf = Pdf::loadView('admin.financial-reports.room-detail-pdf', $data)->setPaper('a4', 'portrait');
        return $pdf->stream('room-detail-' . str_replace(' ', '-', strtolower($room->name)) . '.pdf');
    }

    /**
     * Otorisasi pemilik rumah kos.
     */
    private function authorizeOwner(BoardingHouse $boardingHouse): void
    {
        if (Auth::user()->hasRole('Pemilik') && $boardingHouse->owner_id !== Auth::id()) {
            abort(403);
        }
    }
}
