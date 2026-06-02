<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category, StockMovement};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('sku', 'like', '%' . $request->search . '%')
                    ->orWhere('barcode', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->status === 'low_stock') {
            $query->whereRaw('stock <= min_stock');
        } elseif ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        $products = $query->orderBy('name')->paginate(15)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|unique:products,sku',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->name) . '-' . time();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        // Record initial stock
        if ($product->stock > 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => 'in',
                'quantity' => $product->stock,
                'stock_before' => 0,
                'stock_after' => $product->stock,
                'reference' => 'Initial Stock',
                'notes' => 'Stok awal saat produk dibuat',
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except(['image', '_method', '_token']);

        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    public function adjustStock(Request $request, Product $product)
    {
        $request->validate([
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $stockBefore = $product->stock;

        if ($request->type === 'adjustment') {
            $stockAfter = $request->quantity;
        } elseif ($request->type === 'in') {
            $stockAfter = $stockBefore + $request->quantity;
        } else {
            $stockAfter = max(0, $stockBefore - $request->quantity);
        }

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => $request->type,
            'quantity' => $request->quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'notes' => $request->notes,
        ]);

        $product->update(['stock' => $stockAfter]);

        return back()->with('success', 'Stok berhasil diperbarui!');
    }
}
