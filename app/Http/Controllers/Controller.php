<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(title="My First API", version="0.1")
 * @OA\Schema(
 *     schema="Error",
 *     type="object",
 *     title="Error Response",
 *     properties={
 *         @OA\Property(property="error", type="object",
 *             @OA\Property(property="code", type="integer", example=422),
 *             @OA\Property(property="message", type="string", example="Validation error"),
 *             @OA\Property(property="details", type="object", example={"field": "The field is required."})
 *         )
 *     }
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
