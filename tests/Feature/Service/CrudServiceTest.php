<?php

namespace Tests\Feature\Service;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CrudServiceTest extends TestCase
{
    protected $user;
    protected $category;
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
           $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
        $loginResponseData = json_decode($loginResponse->getContent(), true);
        Passport::actingAs($this->user, ['*'], 'web', [], $loginResponseData['data']['login']['access_token']);
        $loginResponseData = json_decode($loginResponse->getContent(), true);
        $role = Role::where('name', 'freelancer')->first();
                
        $this->user->assignRole($role);
        $this->category = Category::factory()->create();
     
    }

    public function test_create_service()
    {
        $response = $this->post('graphql',[
            'query' => '
            mutation createservice {
                        createservice(
                            input: {
                                user: { connect: "'.$this->user->uuid.'" }
                                category: { connect: "'.$this->category->uuid.'" }
                                title: "df"
                                description: "este es un servicio"
                                price: 10.5
                            }
                        ) {
                            uuid
                            title
                            description
                        }
                    }'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'createservice' => [
                    'uuid',
                    'title',
                    'description'
                ]
            ]
        ]);
    
    }
}
