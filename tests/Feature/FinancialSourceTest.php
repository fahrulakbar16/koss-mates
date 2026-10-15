<?php

namespace Tests\Feature;

use App\Models\TransactionLog;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FinancialSourceTest extends TestCase
{
    public function test_source_records_origin_and_actor_and_survives_edits(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('transaction_logs', function (Blueprint $t) {
            $t->id(); $t->string('type'); $t->string('status'); $t->decimal('amount', 12, 2);
            $t->string('description')->nullable(); $t->string('reference_number')->nullable();
            $t->string('payment_method')->nullable(); $t->timestamps();
        });
        (require database_path('migrations/2026_10_16_000001_create_financial_sources_table.php'))->up();
        $request = Request::create('/admin/transactions/income?token=secret', 'POST');
        $route = (new Route('POST', 'admin/transactions/income', fn () => null))->name('admin.transactions.store-income');
        $request->setRouteResolver(fn () => $route);
        $user = new User; $user->id = 4; $user->name = 'Pengelola';
        $request->setUserResolver(fn () => $user);
        $this->app->instance('request', $request);
        $request->setUserResolver(fn () => $user);

        $log = TransactionLog::create(['type' => 'income', 'status' => 'completed', 'amount' => 10000000]);
        $text = $log->financialSource->keterangan;
        $this->assertStringContainsString('Rp 10.000.000,00', $text);
        $this->assertStringContainsString('halaman Transaksi', $text);
        $this->assertStringContainsString('user ID 4', $text);
        $this->assertStringNotContainsString('secret', $text);
        $log->update(['amount' => 500]);
        $this->assertSame($text, $log->financialSource()->first()->keterangan);
        $this->assertEquals(1, $log->financialSource()->count());
        $log->delete();
        $this->assertEquals(0, DB::table('financial_sources')->count());

        $request->setRouteResolver(fn () => (new Route('POST', 'midtrans/callback', fn () => null))->name('midtrans.callback'));
        $request->setUserResolver(fn () => null);
        $payment = TransactionLog::create(['type' => 'payment', 'status' => 'completed', 'amount' => 100]);
        $this->assertStringContainsString('notifikasi pembayaran dari Midtrans', $payment->financialSource->keterangan);
    }
}
