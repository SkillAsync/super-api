<?php

namespace App\Exceptions\Responses;

use GraphQL\Error\Error;

class UnhandledError
{
    public static function response(Error $error): array
    {
        return [
            'message' => __($error->getMessage()),
            'reason' => 'unhandled_error',
            'error' => $error,
        ];
    }
}
