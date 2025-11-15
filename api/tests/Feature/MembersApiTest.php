<?php

namespace Tests\Feature;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembersApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_members_crud_under_tenant_domain(): void
    {
        // Central: create tenant and domain
        $tenant = Gym::create(['id' => 'gym-alpha', 'data' => ['name' => 'Gym Alpha']]);
        $tenant->domains()->create(['domain' => 'alpha.localhost']);

        // Central: create a user token for auth
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $headers = [
            'Authorization' => 'Bearer '.$token,
            'HOST' => 'alpha.localhost',
            'Accept' => 'application/json',
        ];

        // Create
        $create = $this->postJson('http://alpha.localhost/api/v1/members', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-0101',
        ], $headers);
        $create->assertCreated();

        $memberId = $create->json('id');

        // List
        $index = $this->getJson('http://alpha.localhost/api/v1/members', $headers);
        $index->assertOk();

        // Show
        $show = $this->getJson("http://alpha.localhost/api/v1/members/{$memberId}", $headers);
        $show->assertOk()->assertJsonPath('email', 'john@example.com');

        // Update
        $update = $this->putJson("http://alpha.localhost/api/v1/members/{$memberId}", [
            'phone' => '555-9999',
        ], $headers);
        $update->assertOk()->assertJsonPath('phone', '555-9999');

        // Delete
        $delete = $this->deleteJson("http://alpha.localhost/api/v1/members/{$memberId}", [], $headers);
        $delete->assertNoContent();
    }
}

