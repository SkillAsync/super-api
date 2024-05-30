<?php

namespace Tests\Feature\user;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

class UserTest extends TestCase
{
    use  MakesGraphQLRequests, RefreshDatabase;

    

    public function test_can_update_user()
    {

        $user = User::factory()->create([
            'email' => 'jose@example.com',
            'password' => bcrypt('123456789'),
            'email_verified_at' => null,
            'uuid' => Uuid::uuid4(),
        ]);

        Auth::setUser($user);
      
        // Define input for the GraphQL mutation
        $mutation = '
            mutation updateUser($uuid: String!, $input: UpdateUserInput!) {
                updateUser(uuid: $uuid, input: $input) {
                    first_name
                    last_name
                    email
                }
            }
        ';
            
        // Execute the GraphQL mutation
        $response = $this->graphQL($mutation, [
            'uuid' => $user->uuid,
            'input' => [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'test@gmail.com',
            ],
        ]);
       
        // Assert the response JSON data
        $response->assertJson([
            'data' => [
                'updateUser' => [
                    'first_name' => 'Jane',
                    'last_name' => 'Doe',
                    'email' => 'test@gmail.com',
                ],
            ],
        ]);
    }


    public function test_can_delete_user()
    {

         $user = User::factory()->create([
            'email' => 'jose@example.com',
            'password' => bcrypt('123456789'),
            'email_verified_at' => null,
            'uuid' => Uuid::uuid4(),
        ]);

        Auth::setUser($user);
        // Create a user
    
        // Define input for the GraphQL mutation
        $mutation = '
            mutation deleteUser($uuid: String!) {
                deleteUser(uuid: $uuid) {
                    first_name
                    last_name
                    email
                }
            }
        ';

        // Execute the GraphQL mutation
        $response = $this->graphQL($mutation, [
            'uuid' => $user->uuid->toString(),
        ]);

        // Assert the response JSON data
        $response->assertJson([
            'data' => [
                'deleteUser' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                ],
            ],
        ]);
    }
}
