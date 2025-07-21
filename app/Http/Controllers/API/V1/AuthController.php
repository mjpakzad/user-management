<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\LoginRequest;
use App\Http\Requests\API\V1\RegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\AuthService\AuthServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(name="Auth", description="Authentication endpoints")
 */
class AuthController extends Controller
{
    public function __construct(protected AuthServiceInterface $authService) {}

    /**
     * @OA\Post(
     *   path="/api/v1/register",
     *   tags={"Auth"},
     *   summary="Register a new user",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"mobile","password"},
     *       @OA\Property(property="mobile", type="string", example="09123456789"),
     *       @OA\Property(property="password", type="string", example="secret")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Token response",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="access_token", type="string"),
     *         @OA\Property(property="token_type",   type="string"),
     *         @OA\Property(property="expires_in",   type="integer")
     *       ),
     *       @OA\Property(property="server_time", type="string", format="date-time")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->api($result);
    }

    /**
     * @OA\Post(
     *   path="/api/v1/login",
     *   tags={"Auth"},
     *   summary="Login and receive JWT",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"mobile","password"},
     *       @OA\Property(property="mobile", type="string"),
     *       @OA\Property(property="password", type="string")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Token response",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="access_token", type="string"),
     *         @OA\Property(property="token_type",   type="string"),
     *         @OA\Property(property="expires_in",   type="integer")
     *       ),
     *       @OA\Property(property="server_time", type="string", format="date-time")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        return response()->api($result);
    }

    /**
     * @OA\Get(
     *   path="/api/v1/me",
     *   tags={"Auth"},
     *   summary="Get current user profile",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="User profile",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="id",         type="integer"),
     *         @OA\Property(property="mobile",     type="string"),
     *         @OA\Property(property="avatar",     type="string", nullable=true),
     *         @OA\Property(property="created_at", type="string", format="date-time")
     *       ),
     *       @OA\Property(property="server_time", type="string", format="date-time")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function me(): JsonResponse
    {
        return response()->api(new UserResource(auth()->user()));
    }

    /**
     * @OA\Post(
     *   path="/api/v1/logout",
     *   tags={"Auth"},
     *   summary="Logout user",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Successfully logged out",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="message", type="string")
     *       ),
     *       @OA\Property(property="server_time", type="string", format="date-time")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->api(['message' => __('Successfully logged out')]);
    }

    /**
     * @OA\Post(
     *   path="/api/v1/refresh",
     *   tags={"Auth"},
     *   summary="Refresh JWT token",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Token response",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="access_token", type="string"),
     *         @OA\Property(property="token_type",   type="string"),
     *         @OA\Property(property="expires_in",   type="integer")
     *       ),
     *       @OA\Property(property="server_time", type="string", format="date-time")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function refresh(): JsonResponse
    {
        $result = $this->authService->refresh();

        return response()->api($result);
    }
}
