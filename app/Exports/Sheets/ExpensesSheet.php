<?php

namespace App\Exports\Sheets;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExpensesSheet implements
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
        return 'Pengeluaran';
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kategori',
            'Deskripsi',
            'Nominal'
        ];
    }

    public function collection()
    {
        return Expense::whereBetween(
            'expense_date',
            [$this->dateFrom, $this->dateTo]
        )
        ->select(
            'expense_date',
            'category',
            'description',
            'amount'
        )
        ->get();
    }
}