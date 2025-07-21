<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PostResource;
use App\Models\Post;
use App\Services\PostService\PostServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Operations related to posts"
 * )
 */
class PostController extends Controller
{
    public function __construct(protected PostServiceInterface $postService) {}

    /**
     * Get a paginated list of posts.
     *
     * @OA\Get(
     *   path="/api/v1/posts",
     *   tags={"Posts"},
     *   summary="List posts (paginated)",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     description="Items per page",
     *     @OA\Schema(type="integer", default=10)
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="Page number",
     *     @OA\Schema(type="integer", default=1)
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Paginated posts list",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(
     *           type="object",
     *           @OA\Property(property="heading",    type="string"),
     *           @OA\Property(property="content",    type="string"),
     *           @OA\Property(property="view_count", type="integer"),
     *           @OA\Property(
     *             property="author",
     *             type="object",
     *             @OA\Property(property="id",     type="integer"),
     *             @OA\Property(property="mobile", type="string"),
     *             @OA\Property(property="avatar", type="string", nullable=true)
     *           )
     *         )
     *       ),
     *       @OA\Property(property="links", type="object"),
     *       @OA\Property(property="meta",  type="object"),
     *       @OA\Property(property="server_time", type="string", format="date-time", example="2025-07-21T14:00:00Z")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $posts   = $this->postService->paginateForUser($perPage);

        return response()->api(PostResource::collection($posts));
    }

    /**
     * Get a single post and increment its view count.
     *
     * @OA\Get(
     *   path="/api/v1/posts/{post}",
     *   tags={"Posts"},
     *   summary="Show post details & increase view count",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="post",
     *     in="path",
     *     description="Post ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Post details with updated view count",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="heading",    type="string"),
     *         @OA\Property(property="content",    type="string"),
     *         @OA\Property(property="view_count", type="integer"),
     *         @OA\Property(
     *           property="author",
     *           type="object",
     *           @OA\Property(property="id",     type="integer"),
     *           @OA\Property(property="mobile", type="string"),
     *           @OA\Property(property="avatar", type="string", nullable=true)
     *         )
     *       ),
     *       @OA\Property(property="server_time", type="string", format="date-time", example="2025-07-21T14:00:00Z")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Post not found")
     * )
     */
    public function show(Request $request, Post $post): JsonResponse
    {
        $post = $this->postService->getPost($post, $request->ip());

        return response()->api(new PostResource($post));
    }
}
