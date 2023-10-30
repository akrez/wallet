<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class UnauthorizedException extends CustomException
{
    protected $message = 'Unauthorized';

    public function status(): string
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
