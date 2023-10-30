<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Exception;

class CustomException extends Exception
{
    protected $message = 'Exception';
    protected $errors = [];

    public function __construct(string $message = '', array $errors = [])
    {
        $this->message = strlen($message) > 0 ? $message : $this->message;
        $this->errors = count($errors) > 0 ? $errors : $this->errors;

        parent::__construct($this->message(), $this->status());
    }

    public function message(): string
    {
        return $this->message;
    }

    public function status(): string
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function render()
    {
        return Response::message($this->message())
            ->status($this->status())
            ->errors($this->errors())
            ->send();
    }

    public function report()
    {
        return false;
    }
}
