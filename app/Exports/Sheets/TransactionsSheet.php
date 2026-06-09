<?php

namespace App\Exports\Sheets;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TransactionsSheet implements
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
        return 'Transaksi';
    }

    public function headings(): array
    {
        return [
            'Invoice',
            'Pelanggan',
            'Kasir',
            'Pembayaran',
            'Subtotal',
            'Pajak',
            'Total',
            'Tanggal'
        ];
    }

    public function collection()
    {
        return Transaction::with('user')
            ->where('status', 'completed')
            ->whereBetween(
                DB::raw('DATE(created_at)'),
                [$this->dateFrom, $this->dateTo]
            )
            ->get()
            ->map(function ($trx) {
                return [
                    $trx->invoice_number,
                    $trx->customer_name,
                    $trx->user?->name,
                    strtoupper($trx->payment_method),
                    $trx->subtotal,
                    $trx->tax_amount,
                    $trx->total,
                    $trx->created_at,
                ];
            });
    }
}