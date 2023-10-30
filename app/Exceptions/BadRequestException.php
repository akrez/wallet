<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class BadRequestException extends CustomException
{
    protected $message = 'Bad Request';

    public function status(): string
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
