<?php

use App\Models\BlockAssignment;
use App\Models\User;

test('run endpoint requires authentication', function () {
    $assignment = BlockAssignment::factory()->create(['language' => 'php']);

    $this->postJson("/assignments/{$assignment->id}/run", ['code' => '<?php echo "hi";'])
        ->assertUnauthorized();
});

test('run endpoint validates code is required', function () {
    $user = User::factory()->create();
    $assignment = BlockAssignment::factory()->create(['language' => 'php']);

    $this->actingAs($user)
        ->postJson("/assignments/{$assignment->id}/run", [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['code']);
});

test('submit endpoint requires authentication', function () {
    $assignment = BlockAssignment::factory()->create(['language' => 'php']);

    $this->postJson("/assignments/{$assignment->id}/submit", ['code' => '<?php'])
        ->assertUnauthorized();
});

test('submit endpoint validates code is required', function () {
    $user = User::factory()->create();
    $assignment = BlockAssignment::factory()->create(['language' => 'php']);

    $this->actingAs($user)
        ->postJson("/assignments/{$assignment->id}/submit", [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['code']);
});

test('run endpoint returns 404 for non-existent assignment', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/assignments/999999/run', ['code' => '<?php echo "hi";'])
        ->assertNotFound();
});

test('submit endpoint returns 404 for non-existent assignment', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/assignments/999999/submit', ['code' => '<?php'])
        ->assertNotFound();
});
