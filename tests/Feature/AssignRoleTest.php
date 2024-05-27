<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AssignRoleTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_assign_role_to_user(): void
    {
       $user = User::factory()->create();


        //  $response = $this->graphQL( '
        //     mutation assignRole($user_id: ID!, $role: String!) {
        //         createFreelancerForUser
        //             id
        //             name
        //         }
        //     }
        // ');
    }
}
