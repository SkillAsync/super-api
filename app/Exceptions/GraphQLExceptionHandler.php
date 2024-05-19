<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\Responses\AuthenticationError;
use App\Exceptions\Responses\AuthorizationError;
use App\Exceptions\Responses\EmailNotSentError;
use App\Exceptions\Responses\GraphQLError;
use App\Exceptions\Responses\UnhandledError;
use App\Exceptions\Responses\ValidationError;
use Closure;
use GraphQL\Error\Error;
use Illuminate\Auth\Access\AuthorizationException as LaravelAuthorizationException;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\AuthenticationException;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\EmailNotSentException;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Nuwave\Lighthouse\Execution\ErrorHandler;

class GraphQLExceptionHandler implements ErrorHandler
{
    /**
     * @var \Illuminate\Contracts\Debug\ExceptionHandler
     */
    protected $exceptionHandler;

    public function __invoke(?Error $error, Closure $next): ?array
    {
        if ($error === null) {
            return $next(null);
        }

        $previous = $error->getPrevious();

        return match (true) {
            //$previous instanceof EmailNotSentException => EmailNotSentError::response($error),
            //$previous instanceof AuthenticationException, $previous instanceof \Nuwave\Lighthouse\Exceptions\AuthenticationException => AuthenticationError::response($error),
            $previous instanceof LaravelAuthorizationException => AuthorizationError::response($error),
            //$previous instanceof ValidationException || $previous instanceof  \Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException => ValidationError::response($error),
            $previous instanceof GraphQLException => GraphQLError::response($error),
            default => UnhandledError::response($error),
        };
    }
}
