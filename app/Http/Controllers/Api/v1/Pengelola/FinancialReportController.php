<?php

namespace App\Http\Controllers\Api\v1\Pengelola;

use App\Actions\Rekap\GetRecapPayment;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

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
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk menangani laporan keuangan bagi pengelola/pemilik kos melalui API.
 */
class FinancialReportController extends Controller
{
    use ApiResponse;

    /**
     * Menampilkan daftar semua kos dengan ringkasan keuangan (pemasukan, pengeluaran, laba).
     *
     * @param FinancialReportRequest $request
     * @param GetBoardingHouseFinancialList $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(FinancialReportRequest $request)
    {
        $boardingHouses = app(GetBoardingHouseFinancialList::class)->execute($request->validated(), $request->user());

        return $this->apiResponse(
            BoardingHouseFinancialResource::collection($boardingHouses),
            'Laporan keuangan berhasil diambil'
        );
    }

    /**
     * Menampilkan detail laporan keuangan untuk satu kos tertentu (pemasukan dan pengeluaran detail).
     *
     * @param int $id ID dari boarding house
     * @param FinancialReportRequest $request
     * @param GetBoardingHouseDetailedReport $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, FinancialReportRequest $request)
    {
        $boardingHouse = BoardingHouse::findOrFail($id);
        $this->authorizeOwner($boardingHouse);

        $reportData = app(GetBoardingHouseDetailedReport::class)->execute($boardingHouse, $request->validated());
        $totalIncome = $reportData['total_income'];
        $totalExpense = $reportData['total_expense'];
        $netProfit = $totalIncome - $totalExpense;
        $ownerPercentage = $boardingHouse->persentasi_pemilik ?? 100;
        $ownerShare = ($netProfit * $ownerPercentage) / 100;
        $managementShare = $netProfit - $ownerShare;

        return $this->apiResponse([
            'boardingHouse' => $boardingHouse,
            'incomes' => TransactionResource::collection($reportData['incomes']),
            'expenses' => ExpenseResource::collection($reportData['expenses']),
            'summary' => [
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'net_profit' => $ownerShare,
                'owner_share' => $ownerShare,
                'management_share' => $managementShare,
            ],
            'filters' => $request->validated(),
        ], 'Detail laporan keuangan berhasil diambil');
    }

    /**
     * Menampilkan rekapitulasi data penyewa dan status pembayaran mereka.
     *
     * @param int $id ID dari boarding house
     * @param FinancialReportRequest $request
     * @param GetBoardingHouseRecap $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function recap($id, FinancialReportRequest $request)
    {
        $boardingHouse = BoardingHouse::findOrFail($id);
        $this->authorizeOwner($boardingHouse);

        $recapData = app(GetBoardingHouseRecap::class)->execute($boardingHouse, $request->validated());
        $tenants = TenantRecapResource::collection($recapData['tenants']);

        $totalIncome = $tenants->where('payment_status', 'Lunas')->sum('amount');
        $totalExpense = $recapData['expenses']->sum('amount');
        $netProfit = $totalIncome - $totalExpense;
        $ownerPercentage = $boardingHouse->persentasi_pemilik ?? 100;
        $ownerShare = ($netProfit * $ownerPercentage) / 100;
        $managementShare = $netProfit - $ownerShare;

        return $this->apiResponse([
            'boardingHouse' => $boardingHouse,
            'tenants' => $tenants,
            'expenses' => ExpenseResource::collection($recapData['expenses']),
            'summary' => [
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'net_profit' => $ownerShare,
                'owner_share' => $ownerShare,
                'management_share' => $managementShare,
            ],
            'filters' => $request->validated(),
        ], 'Rekap laporan keuangan berhasil diambil');
    }

    /**
     * Menampilkan detail riwayat transaksi per kamar secara spesifik.
     *
     * @param int $roomId ID dari kamar
     * @param FinancialReportRequest $request
     * @param GetRoomTransactionDetail $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function roomDetail($roomId, FinancialReportRequest $request)
    {
        $room = Room::with(['boardingHouse', 'prices'])->findOrFail($roomId);
        $this->authorizeOwner($room->boardingHouse);

        $detailData = app(GetRoomTransactionDetail::class)->execute($room, $request->validated());

        return $this->apiResponse([
            'room' => new RoomDetailResource($room),
            'transactions' => TransactionResource::collection($detailData['transactions']),
            'totalIncome' => $detailData['total_income'],
            'filters' => $request->validated(),
            'boardingHouse' => [
                'id' => $room->boardingHouse->id,
                'name' => $room->boardingHouse->name,
            ],
        ], 'Detail transaksi kamar berhasil diambil');
    }

    /**
     * Verifikasi otorisasi agar hanya pemilik kos yang bersangkutan yang bisa akses data.
     *
     * @param BoardingHouse $boardingHouse
     * @return void
     */
    private function authorizeOwner(BoardingHouse $boardingHouse): void
    {
        if (Auth::user()->hasRole('Pemilik') && $boardingHouse->owner_id !== Auth::id()) {
            abort(403);
        }
    }
}
