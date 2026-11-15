<?php

test('chat api returns successful response', function () {
    $response = $this->post('/api/chat', [
        'message' => 'Hello, I need help with HTML',
    ], [
        'X-CSRF-TOKEN' => csrf_token(),
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'response',
        ])
        ->assertJson([
            'success' => true,
        ]);
});

test('chat api handles different messages', function () {
    $messages = [
        'hello',
        'help with css',
        'javascript tutorial',
        'responsive design',
    ];

    foreach ($messages as $message) {
        $response = $this->post('/api/chat', [
            'message' => $message,
        ], [
            'X-CSRF-TOKEN' => csrf_token(),
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'response',
            ]);
    }
});
