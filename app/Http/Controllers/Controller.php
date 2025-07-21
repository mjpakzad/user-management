<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *   title="User Management API",
 *   version="1.0.0",
 *   description="API for user registration, authentication, profile & posts"
 * )
 *
 * @OA\Server(
 *   url=L5_SWAGGER_CONST_HOST,
 *   description="Development server"
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT",
 *   description="Enter your bearer token in format **Bearer <token>**"
 * )
 *
 * @OA\Schema(
 *   schema="TokenResponse",
 *   description="Response schema for JWT token operations",
 *   @OA\Property(
 *     property="data",
 *     type="object",
 *     @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
 *     @OA\Property(property="token_type",   type="string", example="bearer"),
 *     @OA\Property(property="expires_in",   type="integer", example=3600)
 *   ),
 *   @OA\Property(property="server_time", type="string", format="date-time", example="2025-07-20T12:00:00Z")
 * )
 */
abstract class Controller extends BaseController
{
    //
}
