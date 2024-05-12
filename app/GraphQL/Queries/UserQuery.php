<?php

namespace App\GraphQL\Queries;

use App\Models\User;

class UserQuery
{
    public function all()
    {
        return User::all();
    }

    public function find($root, array $args)
    {
        return User::findOrFail($args['uuid']);
    }
}
