<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class RouteNotFoundException extends CustomException
{
    protected $message = 'Not Found';

    public function status(): string
    {
        return Response::HTTP_NOT_FOUND;
    }
}
