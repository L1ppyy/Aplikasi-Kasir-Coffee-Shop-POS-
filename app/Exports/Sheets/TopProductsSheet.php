<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TopProductsSheet implements
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
        return 'Produk Terlaris';
    }

    public function headings(): array
    {
        return [
            'Produk',
            'SKU',
            'Qty Terjual',
            'Pendapatan'
        ];
    }

    public function collection()
    {
        return DB::table('transaction_items')
            ->join(
                'transactions',
                'transaction_items.transaction_id',
                '=',
                'transactions.id'
            )
            ->join(
                'products',
                'transaction_items.product_id',
                '=',
                'products.id'
            )
            ->where('transactions.status', 'completed')
            ->whereBetween(
                DB::raw('DATE(transactions.created_at)'),
                [$this->dateFrom, $this->dateTo]
            )
            ->select(
                'products.name',
                'products.sku',
                DB::raw('SUM(transaction_items.quantity) as qty'),
                DB::raw('SUM(transaction_items.subtotal) as revenue')
            )
            ->groupBy(
                'products.id',
                'products.name',
                'products.sku'
            )
            ->orderByDesc('revenue')
            ->get();
    }
}