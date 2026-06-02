<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Transaction, Expense, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? now()->toDateString();

        $sales = Transaction::where('status', 'completed')
            ->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo]);

        $summary = [
            'total_sales' => (clone $sales)->sum('total'),
            'total_transactions' => (clone $sales)->count(),
            'total_discount' => (clone $sales)->sum('discount_amount'),
            'total_tax' => (clone $sales)->sum('tax_amount'),
            'avg_transaction' => (clone $sales)->avg('total') ?? 0,
        ];

        $totalExpenses = Expense::whereBetween('expense_date', [$dateFrom, $dateTo])->sum('amount');
        $summary['net_profit'] = $summary['total_sales'] - $totalExpenses;

        // Daily sales
        $dailySales = DB::table('transactions')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->where('status', 'completed')
            ->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Top products
        $topProducts = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transactions.status', 'completed')
            ->whereBetween(DB::raw('DATE(transactions.created_at)'), [$dateFrom, $dateTo])
            ->select('products.name', 'products.sku',
                DB::raw('SUM(transaction_items.quantity) as qty'),
                DB::raw('SUM(transaction_items.subtotal) as revenue'))
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        // Payment methods
        $paymentMethods = DB::table('transactions')
            ->where('status', 'completed')
            ->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('payment_method')
            ->get();

        // Expenses breakdown
        $expenseBreakdown = Expense::whereBetween('expense_date', [$dateFrom, $dateTo])
            ->select('category', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->get();

        return view('admin.reports.index', compact(
            'summary', 'dailySales', 'topProducts', 'paymentMethods',
            'expenseBreakdown', 'dateFrom', 'dateTo', 'totalExpenses'
        ));
    }
}
