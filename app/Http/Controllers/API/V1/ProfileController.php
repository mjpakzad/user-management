<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\UploadAvatarRequest;
use App\Services\ProfileService\ProfileServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *   name="Profile",
 *   description="User profile operations"
 * )
 */
class ProfileController extends Controller
{
    public function __construct(protected ProfileServiceInterface $profileService)
    {
    }

    /**
     * @OA\Post(
     *   path="/api/v1/avatar",
     *   tags={"Profile"},
     *   summary="Upload the authenticated user’s avatar",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"avatar"},
     *         @OA\Property(
     *           property="avatar",
     *           type="string",
     *           format="binary",
     *           description="Image file to upload"
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Avatar uploaded successfully",
     *     @OA\JsonContent(
     *       required={"data","server_time"},
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="message",   type="string", example="Avatar uploaded successfully."),
     *         @OA\Property(property="avatar_url", type="string", example="http://localhost/storage/avatars/xyz.jpg")
     *       ),
     *       @OA\Property(property="server_time", type="string", format="date-time")
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation error",
     *     @OA\JsonContent(
     *       required={"data","server_time"},
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="message", type="string", example="The given data was invalid."),
     *         @OA\Property(
     *           property="errors",
     *           type="object",
     *           @OA\Property(
     *             property="avatar",
     *             type="array",
     *             @OA\Items(type="string", example="The avatar field is required.")
     *           )
     *         )
     *       ),
     *       @OA\Property(property="server_time", type="string", format="date-time")
     *     )
     *   )
     * )
     */
    public function uploadAvatar(UploadAvatarRequest $request): JsonResponse
    {
        $user   = auth()->user();
        $result = $this->profileService->uploadAvatar($user, $request->file('avatar'));

        return response()->api([
            'message'    => 'Avatar uploaded successfully.',
            'avatar_url' => $result['url'],
        ]);
    }
}

