<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneOrphanExpenseLogs extends Command
{
    protected $signature = 'transactions:prune-orphan-expense-logs {--delete : Hapus log yang terbukti tidak memiliki induk (default hanya pratinjau)}';

    protected $description = 'Periksa/hapus log expense yatim; log tanpa identitas induk dilewati untuk pemeriksaan manual';

    public function handle(): int
    {
        $orphans = 0;
        $unknown = 0;
        DB::table('transaction_logs')->where('type', 'expense')->orderBy('id')->chunkById(200, function ($logs) use (&$orphans, &$unknown) {
            foreach ($logs as $candidate) {
                DB::transaction(function () use ($candidate, &$orphans, &$unknown) {
                    $log = DB::table('transaction_logs')->where('id', $candidate->id)->lockForUpdate()->first();
                    if (!$log || $log->type !== 'expense') {
                        return;
                    }
                    $hasReference = preg_match('/^EXP-([1-9][0-9]*)$/D', $log->reference_number ?? '', $match);
                    $expenseId = $log->expense_id ?? ($hasReference ? $match[1] : null);
                    if (!$expenseId || ($log->expense_id && $hasReference && (string) $log->expense_id !== $match[1])) {
                        $unknown++;
                        $this->warn("Log #{$log->id}: identitas induk tidak jelas; dilewati.");
                        return;
                    }
                    if (DB::table('expenses')->where('id', $expenseId)->lockForUpdate()->exists()) {
                        return;
                    }
                    $orphans++;
                    $this->line("Log #{$log->id} | EXP-{$expenseId} | {$log->transaction_date} | {$log->amount}");
                    if ($this->option('delete')) {
                        DB::table('transaction_logs')->where('id', $log->id)->delete();
                    }
                });
            }
        });
        $this->info(($this->option('delete') ? 'Dihapus' : 'Kandidat hapus').": {$orphans}. Perlu tinjauan manual: {$unknown}.");
        return self::SUCCESS;
    }
}
