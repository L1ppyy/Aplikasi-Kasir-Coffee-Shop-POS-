<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'items']);

        if ($request->search) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%')
                ->orWhere('customer_name', 'like', '%' . $request->search . '%');
        }
        if ($request->date_from) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('created_at', '<=', $request->date_to);
        if ($request->status) $query->where('status', $request->status);
        if ($request->payment_method) $query->where('payment_method', $request->payment_method);

        $transactions = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $summary = [
            'total' => $query->sum('total'),
            'count' => $query->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'summary'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'items.product']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate(['status' => 'required|in:completed,pending,cancelled,refunded']);

        // Restore stock if cancelling
        if ($request->status === 'cancelled' && $transaction->status === 'completed') {
            foreach ($transaction->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
        }

        $transaction->update(['status' => $request->status]);
        return back()->with('success', 'Status transaksi diperbarui!');
    }
}
