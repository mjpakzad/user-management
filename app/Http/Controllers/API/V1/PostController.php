<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PostResource;
use App\Models\Post;
use App\Services\PostService\PostServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{
    public function __construct(protected PostServiceInterface $postService) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $posts   = $this->postService->paginateForUser($perPage);

        return response()->api(PostResource::collection($posts));
    }

    public function show(Request $request, Post $post): JsonResponse
    {
        $post = $this->postService->getPost($post, $request->ip());

        return response()->api(new PostResource($post));
    }
}
