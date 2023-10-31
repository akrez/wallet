<?php

namespace App\Contracts\Interfaces\Controllers;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="wallet doc",
 * description="wallet doc"
 * )
 * @OA\SecurityScheme(
 * type="http",
 * description="Login with email and password to get the authentication token",
 * name="Token based Based",
 * in="header",
 * scheme="bearer",
 * bearerFormat="JWT",
 * securityScheme="bearerAuth",
 * )
 **/
interface Controller
{
}
