<?php

namespace App\Http\Controllers;

use App\Models\{Product, Category, Transaction, TransactionItem, StockMovement, Setting};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->withCount(['products' => function($q) {
            $q->where('is_active', true)->where('stock', '>', 0);
        }])->get();

        $products = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get();

        $settings = [
            'store_name' => Setting::get('store_name', 'Toko POS'),
            'tax_percent' => Setting::get('tax_percent', '0'),
        ];

        return view('cashier.index', compact('categories', 'products', 'settings'));
    }

    public function getProducts(Request $request)
    {
        $query = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0);

        if ($request->category_id && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('sku', 'like', '%' . $request->search . '%')
                    ->orWhere('barcode', 'like', '%' . $request->search . '%');
            });
        }

        return response()->json($query->orderBy('name')->get());
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,debit,credit,qris,transfer',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi. Tersisa: {$product->stock}");
                }

                $itemSubtotal = $product->selling_price * $item['quantity'];
                $discount = $item['discount'] ?? 0;
                $itemSubtotal -= $discount;
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->selling_price,
                    'discount' => $discount,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $discountPercent = $request->discount_percent ?? 0;
            $discountAmount = $request->discount_amount ?? ($subtotal * $discountPercent / 100);
            $taxPercent = $request->tax_percent ?? Setting::get('tax_percent', 0);
            $taxableAmount = $subtotal - $discountAmount;
            $taxAmount = $taxableAmount * $taxPercent / 100;
            $total = $taxableAmount + $taxAmount;
            $changeAmount = $request->amount_paid - $total;

            if ($changeAmount < 0 && $request->payment_method === 'cash') {
                throw new \Exception('Jumlah pembayaran kurang dari total belanja!');
            }

            $transaction = Transaction::create([
                'invoice_number' => Transaction::generateInvoiceNumber(),
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_table_queue,
                'subtotal' => $subtotal,
                'discount_percent' => $discountPercent,
                'discount_amount' => $discountAmount,
                'tax_percent' => $taxPercent,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'amount_paid' => $request->amount_paid,
                'change_amount' => max(0, $changeAmount),
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'status' => 'completed',
            ]);

            foreach ($itemsData as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'subtotal' => $item['subtotal'],
                ]);

                $stockBefore = $item['product']->stock;
                $item['product']->decrement('stock', $item['quantity']);

                StockMovement::create([
                    'product_id' => $item['product']->id,
                    'user_id' => auth()->id(),
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockBefore - $item['quantity'],
                    'reference' => $transaction->invoice_number,
                    'notes' => 'Penjualan POS',
                ]);
            }

            DB::commit();

            $transaction->load(['items', 'user']);
            $storeName = Setting::get('store_name', 'Toko POS');
            $storeAddress = Setting::get('store_address', '');
            $receiptFooter = Setting::get('receipt_footer', 'Terima kasih!');

            return response()->json([
                'success' => true,
                'transaction' => $transaction,
                'store_name' => $storeName,
                'store_address' => $storeAddress,
                'receipt_footer' => $receiptFooter,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function history()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with('items')
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('cashier.history', compact('transactions'));
    }
}
