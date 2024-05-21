<?php

namespace App\Exceptions\Responses;

use GraphQL\Error\Error;
use Illuminate\Support\Facades\Log;

class AuthorizationError
{
    public const CATEGORY = 'authorization';

    public const AUTHORIZATION_ERROR = 'authorization_error';

    public static function response(Error $error): array
    {
        if (! auth()->check()) {
            return AuthenticationError::response($error);
        }

        return self::getAuthorizationMessage($error);
    }

    private static function getAuthorizationMessage(Error $error)
    {
        return [
            'message' => __('auth.authorization_error.message'),
            'reason' => self::AUTHORIZATION_ERROR,
            'error' => $error,
        ];
    }
}
