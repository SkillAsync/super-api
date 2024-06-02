<?php

namespace Tests\Feature\Freelancer;

use App\Models\User;

use Laravel\Passport\Passport;
use Tests\TestCase;

class crudFreelancerTest extends TestCase
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

        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
    }
    public function test_create_freelancer()
    {
        $response = $this->post('graphql',[
            'query' => '
            mutation createFreelancer {
                        createFreelancer(
                            input: {
                                user: { connect: "'.$this->user->uuid.'" }
                                description: "df"
                            }
                        ) {
                            uuid
                            user {
                                first_name
                            }
                        }
                    }'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'createFreelancer' => [
                    'uuid',
                    'user' => [
                        'first_name'
                    ]
                ]
            ]
        ]);
        $response = $this->user->hasRole('freelancer');
        $this->assertTrue($response);
    }
}
