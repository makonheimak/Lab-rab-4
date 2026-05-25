<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Products", description="Товары")
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(path="/api/products", tags={"Products"}, summary="Список товаров",
     *   @OA\Parameter(name="category_id", in="query", required=false, @OA\Schema(type="integer")),
     *   @OA\Parameter(name="search", in="query", required=false, @OA\Schema(type="string")),
     *   @OA\Response(response=200, description="Список товаров")
     * )
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $search = trim((string) $request->query('search', ''));

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('brand', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        return new ProductCollection($query->paginate(12));
    }

    /**
     * @OA\Get(path="/api/products/{id}", tags={"Products"}, summary="Товар по ID",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Данные товара"),
     *   @OA\Response(response=404, description="Не найдено")
     * )
     */
    public function show($id)
    {
        return new ProductResource(Product::with('category')->findOrFail($id));
    }
}
