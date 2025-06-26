<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated admin panel', function () {
    $response = $this->get('http://localhost/admin');

    $response->assertStatus(302);
    $response->assertLocation('http://localhost/admin/login');
});

test('authenticated admin panel', function () {
    $this->actingAs(User::factory()->create());
    $response = $this->get('http://locahost/admin');

    $response->assertStatus(200);
    $response->assertSee('Dashboard');
});