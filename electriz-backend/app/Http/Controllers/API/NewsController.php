<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Http\Resources\NewsCollection;
use App\Models\News;

/**
 * @OA\Tag(name="News", description="Новости магазина")
 */
class NewsController extends Controller
{
    /**
     * @OA\Get(path="/api/news", tags={"News"}, summary="Список новостей",
     *   @OA\Response(response=200, description="Список новостей")
     * )
     */
    public function index() { return new NewsCollection(News::latest()->paginate(10)); }

    /**
     * @OA\Get(path="/api/news/{id}", tags={"News"}, summary="Новость по ID",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Данные новости"),
     *   @OA\Response(response=404, description="Не найдено")
     * )
     */
    public function show($id) { return new NewsResource(News::findOrFail($id)); }
}
