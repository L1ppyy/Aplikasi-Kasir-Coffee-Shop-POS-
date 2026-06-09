<?php

namespace App\Exports\Sheets;

use App\Models\Transaction;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class SummarySheet implements FromArray, WithTitle
{
    protected $dateFrom;
    protected $dateTo;

    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function array(): array
    {
        $sales = Transaction::where('status', 'completed')
            ->whereBetween(
                DB::raw('DATE(created_at)'),
                [$this->dateFrom, $this->dateTo]
            );

        $totalSales = (clone $sales)->sum('total');
        $totalTax = (clone $sales)->sum('tax_amount');
        $totalTransactions = (clone $sales)->count();
        $avgTransaction = (clone $sales)->avg('total') ?? 0;

        $totalExpenses = Expense::whereBetween(
            'expense_date',
            [$this->dateFrom, $this->dateTo]
        )->sum('amount');

        return [
            ['LAPORAN OMZET'],
            [],
            ['Periode', $this->dateFrom.' s/d '.$this->dateTo],
            ['Total Omzet', $totalSales],
            ['Total Transaksi', $totalTransactions],
            ['Rata-rata Transaksi', round($avgTransaction)],
            ['Total Pajak', $totalTax],
            ['Total Pengeluaran', $totalExpenses],
            ['Laba Bersih', $totalSales - $totalExpenses],
        ];
    }
}