<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\SummarySheet;
use App\Exports\Sheets\TransactionsSheet;
use App\Exports\Sheets\TopProductsSheet;
use App\Exports\Sheets\PaymentMethodsSheet;
use App\Exports\Sheets\ExpensesSheet;

class SalesReportExport implements WithMultipleSheets
{
    protected $dateFrom;
    protected $dateTo;

    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function sheets(): array
    {
        return [
            new SummarySheet($this->dateFrom, $this->dateTo),
            new TransactionsSheet($this->dateFrom, $this->dateTo),
            new TopProductsSheet($this->dateFrom, $this->dateTo),
            new PaymentMethodsSheet($this->dateFrom, $this->dateTo),
            new ExpensesSheet($this->dateFrom, $this->dateTo),
        ];
    }
}