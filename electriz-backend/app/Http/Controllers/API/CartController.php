<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemCollection;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Cart", description="Корзина")
 */
class CartController extends Controller
{
    /**
     * @OA\Get(path="/api/cart", tags={"Cart"}, summary="Корзина пользователя", security={{"sanctum":{}}})
     */
    public function index(Request $request)
    {
        $items = CartItem::with('product')->where('user_id', $request->user()->id)->get();
        return new CartItemCollection($items);
    }

    /**
     * @OA\Post(path="/api/cart", tags={"Cart"}, summary="Добавить в корзину", security={{"sanctum":{}}})
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $existing = CartItem::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->update(['quantity' => $existing->quantity + $request->quantity]);
            return new CartItemResource($existing->load('product'));
        }

        $item = CartItem::create([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        return new CartItemResource($item->load('product'));
    }

    /**
     * @OA\Put(path="/api/cart/{id}", tags={"Cart"}, summary="Обновить количество", security={{"sanctum":{}}})
     */
    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $item = CartItem::where('user_id', $request->user()->id)->findOrFail($id);
        $item->update(['quantity' => $request->quantity]);
        return new CartItemResource($item->load('product'));
    }

    /**
     * @OA\Delete(path="/api/cart/{id}", tags={"Cart"}, summary="Удалить позицию", security={{"sanctum":{}}})
     */
    public function destroy(Request $request, $id)
    {
        CartItem::where('user_id', $request->user()->id)->findOrFail($id)->delete();
        return response()->json(['message' => 'Удалено из корзины']);
    }

    /**
     * @OA\Delete(path="/api/cart", tags={"Cart"}, summary="Очистить корзину", security={{"sanctum":{}}})
     */
    public function clear(Request $request)
    {
        CartItem::where('user_id', $request->user()->id)->delete();
        return response()->json(['message' => 'Корзина очищена']);
    }
}
