<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;

/**
 * @OA\Tag(name="Categories", description="Категории товаров")
 */
class CategoryController extends Controller
{
    /**
     * @OA\Get(path="/api/categories", tags={"Categories"}, summary="Список категорий",
     *   @OA\Response(response=200, description="Список категорий")
     * )
     */
    public function index() { return new CategoryCollection(Category::all()); }

    /**
     * @OA\Get(path="/api/categories/{id}", tags={"Categories"}, summary="Категория по ID",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Данные категории"),
     *   @OA\Response(response=404, description="Не найдено")
     * )
     */
    public function show($id) { return new CategoryResource(Category::findOrFail($id)); }
}
