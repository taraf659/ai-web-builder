<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('projects api can create and list a project', function () {
    // Ensure a user exists so the foreign key constraint on projects.user_id passes
    \App\Models\User::factory()->create(['id' => 1]);

    $payload = [
        'name' => 'Test Website',
        'description' => 'A simple test site',
        'html_code' => '<!DOCTYPE html><html><body><h1>Hi</h1></body></html>',
        'css_code' => 'body { font-family: Arial; }',
        'js_code' => 'console.log("hello");',
        'technology_stack' => ['html', 'css', 'javascript'],
    ];

    $response = $this->postJson('/api/projects', $payload);

    $response->assertStatus(201)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('projects', [
        'name' => 'Test Website',
    ]);

    $list = $this->getJson('/api/projects');
    $list->assertStatus(200)
        ->assertJsonPath('projects.0.name', 'Test Website');
});
