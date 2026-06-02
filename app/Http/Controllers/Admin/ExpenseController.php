<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('user');
        if ($request->date_from) $query->where('expense_date', '>=', $request->date_from);
        if ($request->date_to) $query->where('expense_date', '<=', $request->date_to);
        if ($request->category) $query->where('category', $request->category);

        $expenses = $query->orderByDesc('expense_date')->paginate(20)->withQueryString();
        $total = $query->sum('amount');
        return view('admin.expenses.index', compact('expenses', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Expense::create(array_merge($request->only(['title','amount','category','expense_date','description']), [
            'user_id' => auth()->id(),
        ]));

        return redirect()->route('admin.expenses.index')->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('admin.expenses.index')->with('success', 'Pengeluaran berhasil dihapus!');
    }
}
