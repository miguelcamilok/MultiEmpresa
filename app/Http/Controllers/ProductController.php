<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Filtrar solo productos disponibles
        $query->where('state', 'available');

        // Filtrar por categoría (string o array)
        if ($request->has('category') && $request->category) {
            $categories = $request->category;

            // Si viene como string separado por comas
            if (is_string($categories)) {
                $categories = explode(',', $categories);
            }

            // Mapeo directo de filtros frontend a categorías existentes en tu DB
            $finalCategories = [];
            foreach ($categories as $category) {
                switch ($category) {
                    // MOBILE CATEGORIES
                    case 'smartphones':
                        $finalCategories[] = 'smartphones'; // Asume que tienes esta categoría
                        break;
                    case 'cellphones':
                        $finalCategories[] = 'cellphones'; // O mapear a la categoría real que tengas
                        break;
                    case 'mobile_tablets':
                        $finalCategories[] = 'tablets';
                        break;
                    case 'mobile_accessories':
                        $finalCategories[] = 'accessories'; // O la categoría real de accesorios móviles
                        break;

                    // TABLET CATEGORIES  
                    case 'ipad':
                        $finalCategories[] = 'tablets'; // iPad es un tipo de tablet
                        break;
                    case 'android_tablets':
                        $finalCategories[] = 'tablets';
                        break;
                    case 'windows_tablets':
                        $finalCategories[] = 'tablets';
                        break;
                    case 'tablet_accessories':
                        $finalCategories[] = 'accessories'; // O categoría específica de accesorios tablet
                        break;

                    // DRONES & CAMERAS CATEGORIES
                    case 'recreational_drones':
                        $finalCategories[] = 'drones'; // or your actual category
                        break;
                    case 'professional_drones':
                        $finalCategories[] = 'drones';
                        break;
                    case 'drone_accessories':
                        $finalCategories[] = 'accessories';
                        break;
                    case 'dslr_cameras':
                        $finalCategories[] = 'cameras';
                        break;
                    case 'mirrorless_cameras':
                        $finalCategories[] = 'cameras';
                        break;
                    case 'action_cameras':
                        $finalCategories[] = 'cameras';
                        break;
                    case 'camera_accessories':
                        $finalCategories[] = 'accessories';
                        break;

                    // AUDIO
                    case 'headphones':
                        $finalCategories[] = 'headphones';
                        break;
                    case 'speakers':
                        $finalCategories[] = 'speakers';
                        break;
                    case 'soundbars':
                        $finalCategories[] = 'soundbars';
                        break;
                    case 'audio_accessories':
                        $finalCategories[] = 'accessories';
                        break;


                    // TV & HOME CINEMA CATEGORIES
                    case 'tv':
                        $finalCategories[] = 'tv';
                        break;
                    case 'home_cinema':
                        $finalCategories[] = 'home_cinema';
                        break;
                    case 'projectors':
                        $finalCategories[] = 'projectors';
                        break;
                    case 'soundbars':
                        $finalCategories[] = 'soundbars';
                        break;
                    case 'tv_accessories':
                        $finalCategories[] = 'accessories';
                        break;

                    // Mantener compatibilidad con categorías directas
                    default:
                        $finalCategories[] = $category;
                        break;
                }
            }

            if (!empty($finalCategories)) {
                $query->whereIn('category', array_unique($finalCategories));
            }
        }

        // También agregar filtros de precio para tablets (rangos más altos)
        if ($request->has('price') && $request->price) {
            switch ($request->price) {
                // Rangos Mobile (precios más bajos)
                case 'Menos de $50.000':
                    $query->where('unit_price', '<', 50000);
                    break;
                case '$50.000 - $100.000':
                    $query->whereBetween('unit_price', [50000, 100000]);
                    break;
                case '$100.000 - $200.000':
                    $query->whereBetween('unit_price', [100000, 200000]);
                    break;
                case 'Más de $200.000':
                    $query->where('unit_price', '>', 200000);
                    break;
                case 'Menos de $200.000':
                    $query->where('unit_price', '<', 200000);
                    break;
                case '$200.000 - $500.000':
                    $query->whereBetween('unit_price', [200000, 500000]);
                    break;
                case '$500.000 - $1.000.000':
                    $query->whereBetween('unit_price', [500000, 1000000]);
                    break;
                case 'Más de $1.000.000':
                    $query->where('unit_price', '>', 1000000);
                    break;

                // Rangos Tablets (precios más altos)
                case 'Menos de $500.000':
                    $query->where('unit_price', '<', 500000);
                    break;
                case '$1.000.000 - $2.000.000':
                    $query->whereBetween('unit_price', [1000000, 2000000]);
                    break;
                case 'Más de $2.000.000':
                    $query->where('unit_price', '>', 2000000);
                    break;
            }
        }

        // Filtrar por marca
        if ($request->has('brand') && $request->brand) {
            $query->where('brand', $request->brand);
        }


        // Envío gratuito
        if ($request->has('free_shipping') && $request->free_shipping) {
            $query->where('free_shipping', true);
        }

        // Ordenar
        if ($request->has('sort') && $request->sort) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('unit_price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('unit_price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('rating', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        // Debug: Log the final query (remove in production)
        \Log::info('Product Query SQL: ' . $query->toSql());
        \Log::info('Product Query Bindings: ', $query->getBindings());

        // Paginación
        $products = $query->paginate(20);

        return response()->json($products);
    }

    /**
     * Show a single product by ID.
     */
    public function show($id)
    {
        $product = Product::with('stocks.branch.company')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $productData = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'originalPrice' => $product->original_price ?? $product->unit_price,
            'price' => $product->unit_price,
            'discount' => $product->discount ?? null,
            'reviews' => $product->rating,
            'category' => $product->category,
            'subcategory' => $product->subcategory,
            'brand' => $product->brand,
            'model' => $product->model,
            'color' => $product->color,
            'size' => $product->size,
            'dimensions' => $product->dimensions,
            'weight' => $product->weight,
            'material' => $product->material,
            'features' => json_decode($product->features) ?? [],
            'warranty' => $product->warranty,
            'packaging' => $product->packaging,
            'notes' => $product->notes,
            'media' => json_decode($product->media) ?? [],
            'freeShipping' => $product->free_shipping,
            'international' => $product->international,
            'stocks' => $product->stocks->map(function ($stock) {
                return [
                    'branch_id' => $stock->branch->id,
                    'branch_name' => $stock->branch->name,
                    'company_name' => $stock->branch->company->name,
                    'quantity' => $stock->quantity,
                    'shipping_cost' => $stock->branch->shipping_cost ?? 0,
                ];
            }),
            'total_stock' => $product->stocks->sum('quantity'),
            'is_best_seller' => $product->rating >= 4.7 && $product->sales_count >= 50,
        ];

        return response()->json($productData);
    }

    /**
     * List best sellers.
     */
    public function bestSellers()
    {
        $bestSellers = Product::where('state', 'available')
            ->whereBetween('rating', [4.7, 5.0])
            ->where('sales_count', '>=', 50)
            ->orderByDesc('sales_count')
            ->orderByDesc('rating')
            ->take(10)
            ->get()
            ->map(fn($product) => tap($product, fn(&$p) => $p->is_best_seller = true));

        return response()->json($bestSellers);
    }
}
