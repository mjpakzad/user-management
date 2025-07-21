<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserViewedResource;
use App\Services\UserService\UserServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *   name="Users",
 *   description="Operations related to user ranking"
 * )
 */
class UserController extends Controller
{
    public function __construct(protected UserServiceInterface $userService) {}

    /**
     * @OA\Get(
     *     path="/api/v1/users/ranking",
     *     tags={"Users"},
     *     summary="Get users ordered by total views on their posts",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Ranked list of users",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="mobile", type="string"),
     *                     @OA\Property(property="avatar", type="string", nullable=true),
     *                     @OA\Property(property="total_views", type="integer")
     *                 )
     *             ),
     *             @OA\Property(property="server_time", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index(): JsonResponse
    {
        $users = $this->userService->paginateByTotalViews(10);

        return response()->api(UserViewedResource::collection($users));
    }
}
