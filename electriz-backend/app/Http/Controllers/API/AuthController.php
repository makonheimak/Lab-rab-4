<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

/**
 * @OA\Tag(name="Auth", description="Авторизация и регистрация")
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(path="/api/register", tags={"Auth"}, summary="Регистрация",
     *   @OA\RequestBody(required=true, @OA\JsonContent(
     *     required={"name","email","password"},
     *     @OA\Property(property="name", type="string", example="Иван"),
     *     @OA\Property(property="email", type="string", example="ivan@example.com"),
     *     @OA\Property(property="password", type="string", example="secret123")
     *   )),
     *   @OA\Response(response=201, description="Пользователь создан"),
     *   @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user  = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    /**
     * @OA\Post(path="/api/login", tags={"Auth"}, summary="Вход",
     *   @OA\RequestBody(required=true, @OA\JsonContent(
     *     required={"email","password"},
     *     @OA\Property(property="email", type="string"),
     *     @OA\Property(property="password", type="string")
     *   )),
     *   @OA\Response(response=200, description="Успешный вход"),
     *   @OA\Response(response=401, description="Неверные данные")
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Неверный email или пароль'], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    /**
     * @OA\Post(path="/api/logout", tags={"Auth"}, summary="Выход",
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=200, description="Выход выполнен")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Выход выполнен успешно']);
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        $user = User::updateOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name'              => $socialUser->getName()
                                    ?? $socialUser->getNickname()
                                    ?? explode('@', $socialUser->getEmail())[0]
                                    ?? 'Пользователь',
                'provider'          => $provider,
                'provider_id'       => $socialUser->getId(),
                'avatar'            => $socialUser->getAvatar(),
                'email_verified_at' => now(),
                'password'          => Hash::make(str()->random(32)),
            ]
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return redirect(env('FRONTEND_URL', 'http://localhost:3000') . '/auth/callback?token=' . $token);
    }
}
