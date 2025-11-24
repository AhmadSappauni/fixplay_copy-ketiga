<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Product::orderBy('name');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%'.$q.'%')
                    ->orWhere('category', 'like', '%'.$q.'%');
            });
        }

        // INI YANG PENTING: variabelnya bernama $items
        $items = $query->paginate(20);

        // kirim $items (dan optional $q kalau mau dipakai di view)
        return view('products.index', compact('items', 'q'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'price'    => 'required|integer|min:0',
            'stock'    => 'required|integer|min:0',
            'unit'     => 'nullable|string|max:30',
        ]);

        Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk disimpan.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'price'    => 'required|integer|min:0',
            'stock'    => 'required|integer|min:0',
            'unit'     => 'nullable|string|max:30',
        ]);

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk dihapus.');
    }
}
