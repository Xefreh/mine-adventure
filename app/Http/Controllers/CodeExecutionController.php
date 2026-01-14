<?php

namespace App\Http\Controllers;

use App\Http\Requests\RunCodeRequest;
use App\Http\Requests\SubmitCodeRequest;
use App\Models\BlockAssignment;
use App\Services\TestSubmissionService;
use Illuminate\Http\JsonResponse;
use Xefreh\Judge0PhpClient\DTO\Submission;
use Xefreh\Judge0PhpClient\Judge0Client;

class CodeExecutionController extends Controller
{
    public function __construct(
        private readonly Judge0Client $client,
        private readonly TestSubmissionService $testService,
    ) {}

    public function run(RunCodeRequest $request, BlockAssignment $assignment): JsonResponse
    {
        $languageId = $this->getLanguageId($assignment->language);

        if ($languageId === -1) {
            return response()->json([
                'success' => false,
                'error' => "Unsupported language: {$assignment->language}",
            ], 422);
        }

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
        if ($assignment->test === null) {
            return response()->json([
                'success' => false,
                'message' => 'No tests configured for this assignment.',
                'passed' => 0,
                'total' => 0,
                'results' => [],
            ]);
        }

        $result = $this->testService->execute(
            $request->validated('code'),
            $assignment->test
        );

        return response()->json($result);
    }

    private function getLanguageId(string $language): int
    {
        $languages = $this->client->languages->all();
        $searchTerm = strtolower($language);

        foreach ($languages as $lang) {
            if (str_contains(strtolower($lang->name), $searchTerm)) {
                return $lang->id;
            }
        }

        return -1;
    }
}
