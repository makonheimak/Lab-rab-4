<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PushSubscriptionResource;
use App\Models\PushSubscription;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Push", description="Push-уведомления")
 */
class PushController extends Controller
{
    /**
     * @OA\Post(path="/api/push/subscribe", tags={"Push"}, summary="Сохранить push-подписку", security={{"sanctum":{}}})
     */
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        $subscription = PushSubscription::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'endpoint' => $data['endpoint'],
                'p256dh' => $data['keys']['p256dh'],
                'auth' => $data['keys']['auth'],
            ]
        );

        return new PushSubscriptionResource($subscription);
    }

    /**
     * @OA\Post(path="/api/push/send", tags={"Push"}, summary="Подготовить тестовое push-уведомление", security={{"sanctum":{}}})
     */
    public function send(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:120',
            'body' => 'required|string|max:255',
        ]);

        return response()->json([
            'message' => 'Тестовое push-уведомление подготовлено',
            'notification' => [
                'title' => $data['title'],
                'body' => $data['body'],
                'icon' => '/icons/icon-192.svg',
            ],
        ]);
    }
}
