<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string',
            'icon' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'color' => $request->color,
            'icon' => $request->icon,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string',
            'icon' => 'required|string',
        ]);

        $category->update($request->only(['name', 'color', 'icon', 'description', 'is_active']));
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk!');
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
