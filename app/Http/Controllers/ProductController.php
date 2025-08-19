<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all(); // Obtiene todos los productos de la base de datos
        $categories = Category::all()->pluck('name', 'id'); // Para las categorías

        return view('tu_vista', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function bestSellers()
    {
        $bestSellers = Product::where('state', 'available')
            ->whereBetween('rating', [4.7, 5.0]) // solo productos con rating 4.7 a 5
            ->where('sales_count', '>=', 50)    // opcional: filtrar ventas mínimas
            ->orderByDesc('sales_count')
            ->orderByDesc('rating')
            ->take(10)
            ->get();

        return response()->json($bestSellers);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
