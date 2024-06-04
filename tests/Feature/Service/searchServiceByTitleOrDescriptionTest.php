<?php

namespace Tests\Feature\Service;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Tests\TestCase;

class searchServiceByTitleOrDescriptionTest extends TestCase
{

    public function testSearchServiceByTitleOrDescription()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        
    $service1=Service::factory()->create([
    'user_id' => $user->id,
    'category_id' => $category->id,
    'title' => 'Mi servicio',
    'description' => 'Esta es una descripción de mi servicio',
    ]);

    $service2=Service::factory()->create([
    'user_id' => $user->id,
    'category_id' => $category->id,
    'title' => 'Mi  2',
    'description' => 'Esta es una descripción de mi  2',
    ]);


    
    
    $response = $this->graphQL('
            query GetAllservices {
                    getAllservices(generecSearch: "servicio") {
                        title
                        description
                    }
                }
        ');
    
       
    $response->assertJson([
            'data' => [
                'getAllservices' => [
                    [
                        'title' => 'Mi servicio',
                        'description' => 'Esta es una descripción de mi servicio',
                    ],
                ],
            ],
        ]);

    $responseData = $response->json();
    $this->assertCount(1, $responseData['data']['getAllservices']);
    }


    
}
