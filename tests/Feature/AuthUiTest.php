<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_loads_project_stylesheet(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
        ]);

        $response = $this->get('/login');

        $response
            ->assertOk()
            ->assertSee('/build/', false)
            ->assertSee('Mini ERP', false);
    }
}
