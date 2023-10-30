<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class ForbiddenAccessException extends CustomException
{
    protected $message = 'Forbidden Access';

    public function status(): string
    {
        return Response::HTTP_FORBIDDEN;
    }
}
