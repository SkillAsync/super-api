<?php

namespace App\Exceptions\Responses;

use GraphQL\Error\Error;
use Illuminate\Support\Facades\Log;

class AuthenticationError
{
    public const CATEGORY = 'authentication';
    public const INVALID_CLIENT_MESSAGE = 'invalid_client';
    public const INVALID_GRANT_MESSAGE = 'invalid_grant';
    public const AUTHENTICATION_ERROR = 'authentication_error';
    public const UNAUTHENTICATED_ERROR = 'Unauthenticated.';
    public const WRONG_CREDENTIALS = 'wrong_credentials';

    public static function response(Error $error): array
    {
        $reason = $error->getExtensions()['reason'] ?? null;

        return match (true) {
            $reason === self::WRONG_CREDENTIALS => self::getErrorMessage($reason, __('auth.wrong_credentials'), $error),
            $error->getMessage() === self::INVALID_CLIENT_MESSAGE => self::getErrorMessage(self::INVALID_CLIENT_MESSAGE, __('auth.invalid_client'), $error),
            $error->getMessage() === self::INVALID_GRANT_MESSAGE => self::getErrorMessage(self::INVALID_GRANT_MESSAGE, __('auth.failed'), $error),
            $error->getMessage() === self::UNAUTHENTICATED_ERROR => self::getErrorMessage(self::AUTHENTICATION_ERROR, __('auth.no_authenticated'), $error),
            default => self::getErrorMessage(self::AUTHENTICATION_ERROR, __('auth.authentication_error'), $error),
        };
    }

    private static function getErrorMessage(string $reason, string $message, Error $error)
    {
        Log::info('Authentication error', ['content' => $reason]);

        return [
            'message' => __($message),
            'reason' => $reason,
            'error' => $error,
        ];
    }
}
