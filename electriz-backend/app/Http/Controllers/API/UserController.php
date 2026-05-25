<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="User", description="Профиль пользователя")
 */
class UserController extends Controller
{
    /**
     * @OA\Get(path="/api/user", tags={"User"}, summary="Данные профиля", security={{"sanctum":{}}})
     */
    public function profile(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * @OA\Put(path="/api/user", tags={"User"}, summary="Обновить профиль", security={{"sanctum":{}}})
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:500',
        ]);

        $request->user()->update($request->only('name', 'phone', 'address'));
        return new UserResource($request->user());
    }
}
