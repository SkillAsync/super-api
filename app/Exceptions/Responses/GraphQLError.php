<?php

namespace App\Exceptions\Responses;

use GraphQL\Error\Error;

class GraphQLError
{
    public static function response(Error $error): array
    {
        return [
            'message' => __($error->getMessage()),
            'reason' => $error->getExtensions()['reason'],
            'error' => $error,
        ];
    }
}
