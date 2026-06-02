<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Transaction, Product, Category, User, Expense};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $thisMonth = now()->format('Y-m');

        $stats = [
            'today_sales' => Transaction::whereDate('created_at', $today)
                ->where('status', 'completed')->sum('total'),
            'today_transactions' => Transaction::whereDate('created_at', $today)
                ->where('status', 'completed')->count(),
            'month_sales' => Transaction::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$thisMonth])
                ->where('status', 'completed')->sum('total'),
            'total_products' => Product::where('is_active', true)->count(),
            'low_stock_products' => Product::whereRaw('stock <= min_stock')->count(),
            'total_categories' => Category::where('is_active', true)->count(),
        ];

        // Sales chart last 7 days
        $salesChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $salesChart[] = [
                'date' => now()->subDays($i)->format('d M'),
                'total' => Transaction::whereDate('created_at', $date)
                    ->where('status', 'completed')->sum('total'),
                'count' => Transaction::whereDate('created_at', $date)
                    ->where('status', 'completed')->count(),
            ];
        }

        // Top products
        $topProducts = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'completed')
            ->whereMonth('transactions.created_at', now()->month)
            ->select('products.name', DB::raw('SUM(transaction_items.quantity) as total_qty'), DB::raw('SUM(transaction_items.subtotal) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Payment method breakdown
        $paymentBreakdown = Transaction::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('payment_method')
            ->get();

        $recentTransactions = Transaction::with('user')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $lowStockProducts = Product::with('category')
            ->whereRaw('stock <= min_stock')
            ->orderBy('stock')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'salesChart', 'topProducts', 'paymentBreakdown',
            'recentTransactions', 'lowStockProducts'
        ));
    }
}
