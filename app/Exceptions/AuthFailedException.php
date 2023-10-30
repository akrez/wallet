<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class AuthFailedException extends CustomException
{
    protected $message = 'Authentication Error';

    public function status(): string
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
