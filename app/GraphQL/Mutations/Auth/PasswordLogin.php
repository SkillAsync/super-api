<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final readonly class PasswordLogin
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::where('email', $args['username'])->first();
        
        if (! $user || ! Hash::check($args['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($args['device'])->plainTextToken;
    }
}
