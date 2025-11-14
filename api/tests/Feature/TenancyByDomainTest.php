<?php

namespace Tests\Feature;

use App\Models\Gym;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenancyByDomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_route_initializes_by_domain(): void
    {
        // Create tenant and domain in central DB
        $tenant = Gym::create(['id' => 'gym-alpha', 'data' => ['name' => 'Gym Alpha']]);
        $tenant->domains()->create(['domain' => 'alpha.localhost']);

        // Hit tenant route with the tenant's host header
        $response = $this->get('/', ['HOST' => 'alpha.localhost']);

        $response->assertOk();
        $response->assertSee('gym-alpha');
    }
}

