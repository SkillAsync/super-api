<?php 
namespace App\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Role;

class AssignRole
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = User::findOrFail($args['userId']);
        $role = Role::where('name', $args['role'])->firstOrFail();
        $user->assignRole($role);
        
        return $user;
    }
}
