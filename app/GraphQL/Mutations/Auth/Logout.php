<?php

namespace App\GraphQL\Mutations\Auth;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Logout 
{
    /**
     * @return array
     *
     * @throws \Exception
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        if (! Auth::check()) { 
            return [
                'status' => 'UNAUTHENTICATED',
                'message' => __('no estás autenticado'),
            ];
        }

        Auth::user()->tokens()->each(function ($token) {
            $token->delete();
        });

        

        return [
            'status' => 'TOKEN_REVOKED',
            'message' => __('Your session has been terminated'),
        ];
    }
}
