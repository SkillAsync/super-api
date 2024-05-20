<?php

namespace Tests\Feature\user;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

class UserTest extends TestCase
{
    use RefreshDatabase, MakesGraphQLRequests;

    public function test_can_create_user()
    {
        // Define input for the GraphQL mutation
        $mutation = '
            mutation createUser($input: CreateUserInput!) {
                createUser(input: $input) {
                    first_name
                    last_name
                    email
                    password
                }
            }
        ';

        // Execute the GraphQL mutation
        $response = $this->graphQL($mutation, [
            'input' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'test@gmail.com',
                'password' => 'password',
            ],
        ]);

        // Assert the response JSON data
        $response->assertJson([
            'data' => [
                'createUser' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'test@gmail.com',
                ],
            ],
        ]);
    }

    public function test_can_update_user()
    {
        // Create a user
        $user = User::factory()->create();
        //dd($user->uuid);
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
        // Create a user
        $user = User::factory()->create();

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
            'uuid' => $user->uuid,
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
