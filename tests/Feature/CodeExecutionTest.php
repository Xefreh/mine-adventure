<?php

use App\Models\BlockAssignment;
use App\Models\BlockAssignmentTest;
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

test('submit with no test returns appropriate message', function () {
    $user = User::factory()->create();
    $assignment = BlockAssignment::factory()->create(['language' => 'php']);

    $this->actingAs($user)
        ->postJson("/assignments/{$assignment->id}/submit", ['code' => '<?php echo "hi";'])
        ->assertOk()
        ->assertJson([
            'success' => false,
            'message' => 'No tests configured for this assignment.',
            'passed' => 0,
            'total' => 0,
        ]);
});

test('submit executes PHPUnit tests against user code', function () {
    $user = User::factory()->create();
    $assignment = BlockAssignment::factory()->create(['language' => 'php']);

    BlockAssignmentTest::factory()->create([
        'block_assignment_id' => $assignment->id,
        'file_content' => <<<'PHPUNIT'
<?php

use PHPUnit\Framework\TestCase;

class HelloWorldTest extends TestCase
{
    public function test_outputs_hello_world(): void
    {
        ob_start();
        include __DIR__ . '/../solution.php';
        $output = ob_get_clean();

        $this->assertEquals('Hello, World!', trim($output));
    }
}
PHPUNIT,
        'class_name' => 'HelloWorldTest',
    ]);

    $response = $this->actingAs($user)
        ->postJson("/assignments/{$assignment->id}/submit", [
            'code' => '<?php echo "Hello, World!";',
        ]);

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'passed',
            'total',
            'results',
        ]);
})->group('judge0');
