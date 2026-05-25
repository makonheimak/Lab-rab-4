<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *   title="ElectrizShop API",
 *   version="1.0.0",
 *   description="REST API для PWA интернет-магазина электроники"
 * )
 *
 * @OA\Server(
 *   url="http://localhost:8000",
 *   description="Локальный сервер Laravel"
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="sanctum",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="Token"
 * )
 */
class SwaggerController extends Controller
{
}
