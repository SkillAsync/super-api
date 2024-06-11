<?php

namespace Tests\Feature\Review;

use App\Models\Freelancer;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CreateReviewTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $freelancer;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear usuario y autenticar
        $this->user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        // Crear Freelancer asociado al usuario
        $this->freelancer = Freelancer::factory()->create();

        // Crear Servicio asociado al usuario
        $this->service = Service::factory()->create([
            'user_id' => $this->user->id
        ]);

        // Autenticación
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

        // Sembrar roles y permisos
        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
    }

    public function test_create_review()
    {
        // Debug para ver datos del servicio
        dd($this->service->toArray());

        $response = $this->post('graphql', [
            'query' => 'mutation CreateReview {
                createReview(
                    input: {
                        user: '.$this->user->id.'
                        freelancer: '.$this->freelancer->id.'
                        service: '.$this->service->id.'
                        comment: "test"
                        rating: 4
                    }
                ) {
                    uuid
                    user {
                        uuid
                    }
                    service {
                        uuid
                    }
                     freelancer {
            uuid
            description
        }
                    comment
                    rating
                }
            }'
        ]);

        $response->assertStatus(200);

        // Debug para ver la respuesta completa
        dd($response->json());

        $response->assertJsonStructure([
            'data' => [
                'createReview' => [
                    'uuid',
                    'comment',
                    'rating'
                ]
            ]
        ]);
    }
}
