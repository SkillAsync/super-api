<?php

namespace App\Exceptions;

use Exception;
use GraphQL\Error\ClientAware;
use GraphQL\Error\ProvidesExtensions;

class GraphQLException extends Exception implements ClientAware, ProvidesExtensions
{
    
    protected $reason;

    public function __construct(string $message, string $reason)
    {
        parent::__construct($message);

        $this->reason = $reason;
    }

    /**
     * Returns true when exception message is safe to be displayed to a client.
     */
    public function isClientSafe(): bool
    {
        return true;
    }

    /**
     * Data to include within the "extensions" key of the formatted error.
     *
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return [
            'reason' => $this->reason,
        ];
    }
}
