<?php

namespace App\Http\Controllers;

use App\Http\Requests\RunCodeRequest;
use App\Http\Requests\SubmitCodeRequest;
use App\Models\BlockAssignment;
use Illuminate\Http\JsonResponse;
use Xefreh\Judge0PhpClient\DTO\Submission;
use Xefreh\Judge0PhpClient\Judge0Client;

class CodeExecutionController extends Controller
{
    public function __construct(
        private readonly Judge0Client $client,
    ) {}

    public function run(RunCodeRequest $request, BlockAssignment $assignment): JsonResponse
    {
        $languageId = $this->getLanguageId($assignment->language);

        $submission = new Submission(
            languageId: $languageId,
            sourceCode: $request->validated('code'),
            stdin: $request->validated('stdin'),
        );

        $result = $this->client->submissions->create($submission, wait: true);

        return response()->json([
            'success' => $result->isSuccess(),
            'output' => $result->stdout,
            'error' => $result->stderr ?? $result->compileOutput,
            'time' => $result->time,
            'memory' => $result->memory,
            'status' => $result->status?->description,
        ]);
    }

    public function submit(SubmitCodeRequest $request, BlockAssignment $assignment): JsonResponse
    {
        $languageId = $this->getLanguageId($assignment->language);
        $tests = $assignment->tests;

        if ($tests->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No tests configured for this assignment.',
                'passed' => 0,
                'total' => 0,
                'results' => [],
            ]);
        }

        $results = [];
        $allPassed = true;

        foreach ($tests as $test) {
            $submission = new Submission(
                languageId: $languageId,
                sourceCode: $request->validated('code'),
                stdin: $test->stdin ?? '',
                expectedOutput: $test->expected_output,
            );

            $result = $this->client->submissions->create($submission, wait: true);

            $passed = $result->isSuccess() &&
                      trim($result->stdout ?? '') === trim($test->expected_output ?? '');

            if (! $passed) {
                $allPassed = false;
            }

            $results[] = [
                'passed' => $passed,
                'expected' => $test->expected_output,
                'actual' => $result->stdout,
                'error' => $result->stderr ?? $result->compileOutput,
                'status' => $result->status?->description,
            ];
        }

        return response()->json([
            'success' => $allPassed,
            'passed' => count(array_filter($results, fn ($r) => $r['passed'])),
            'total' => count($results),
            'results' => $results,
        ]);
    }

    private function getLanguageId(string $language): int
    {
        return match ($language) {
            'php' => 68,
            'python' => 71,
            'javascript' => 63,
            'typescript' => 74,
            'java' => 62,
            'c' => 50,
            'cpp' => 54,
            'csharp' => 51,
            'go' => 60,
            'rust' => 73,
            'ruby' => 72,
            default => 71,
        };
    }
}
