<?php
namespace Tests\Feature\User;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CrudUserTest extends TestCase
{
   protected $user;
     protected function setUp(): void
    {
        parent::setUp();
         $this->user = User::factory()->create(
            [
                'password' => bcrypt('password'),
        ]);

        $loginResponse = $this->post('graphql', [
            'query' => 'mutation Login {
                login(
                    input: {
                        username: "'.$this->user->email.'"
                        password: "password"
                    }
                ) {
                    access_token
                }
            }'
        ]);

        $loginResponseData = json_decode($loginResponse->getContent(), true);
        Passport::actingAs($this->user, ['*'], 'web', [], $loginResponseData['data']['login']['access_token']);
    }

    public function test_create_user()
    {
        $response = $this->post('graphql',[
            'query' => 'mutation CreateUser {
                createUser(
                    input: {
                        first_name: "souahil"
                        last_name: "test"
                        email: "souhail@gmail.com"
                        password: "souhailsouhail"
                    }
                ) {
                    uuid
                    first_name
                    email
                    last_name
                }}'
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createUser' => [
                    'uuid',
                    'first_name',
                    'email',
                    'last_name'
                ]
            ]
        ]);
    }
}
