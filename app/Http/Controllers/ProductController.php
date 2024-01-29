<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = Product::where(function ($query) use ($search) {
            if ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            }
        })->orderBy('name', 'asc')->paginate(10);

        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|required',
            'price' => 'numeric|required|min:0',
            'quantity' => 'numeric|required|min:0'
        ]);

        $input = $request->all();

        Product::create($input);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Product Berhasil Ditambahkan.',
                'redirect' => route('product.index'),
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::find($id);

        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'string|required',
            'price' => 'numeric|required|min:0',
            'quantity' => 'numeric|required|min:0'
        ]);

        $product = Product::find($id);

        $input = $request->all();

        $product->update($input);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Product Berhasil Di ubah.',
                'redirect' => route('product.index'),
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()->route('product.index')->with('message', 'Data berhasil di hapus');
    }

}
