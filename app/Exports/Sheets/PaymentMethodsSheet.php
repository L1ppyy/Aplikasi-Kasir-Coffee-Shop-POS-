<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PaymentMethodsSheet implements
    FromCollection,
    WithHeadings,
    WithTitle
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
        return 'Pembayaran';
    }

    public function headings(): array
    {
        return [
            'Metode',
            'Jumlah Transaksi',
            'Total'
        ];
    }

    public function collection()
    {
        return DB::table('transactions')
            ->where('status', 'completed')
            ->whereBetween(
                DB::raw('DATE(created_at)'),
                [$this->dateFrom, $this->dateTo]
            )
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('payment_method')
            ->get();
    }
}