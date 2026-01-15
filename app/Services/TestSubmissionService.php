<?php

namespace App\Services;

use App\Models\BlockAssignmentTest;
use Illuminate\Support\Facades\Log;
use Xefreh\Judge0PhpClient\DTO\Submission;
use Xefreh\Judge0PhpClient\DTO\SubmissionResult;
use Xefreh\Judge0PhpClient\Exceptions\ApiException;
use Xefreh\Judge0PhpClient\Judge0Client;
use Xefreh\Judge0PhpClient\Utils\ArchiveBuilder;

class TestSubmissionService
{
    private const int LANGUAGE_ID_MULTI_FILE = 89;

    public function __construct(
        private readonly Judge0Client $client,
    ) {}

    /**
     * Execute user code against PHPUnit tests.
     *
     * @return array{success: bool, passed: int, total: int, results: array<int, array{test: string, status: string, message?: string}>, raw_output?: string, error?: string}
     *
     * @throws ApiException
     */
    public function execute(string $userCode, BlockAssignmentTest $test): array
    {
        $archive = $this->buildArchive($userCode, $test);

        $submission = new Submission(
            languageId: self::LANGUAGE_ID_MULTI_FILE,
            additional_files: $archive,
            enable_network: true,
        );

        $result = $this->client->submissions->create($submission, wait: true);

        Log::info('Judge0 submission result', [
            'status' => $result->status?->description,
            'status_id' => $result->status?->id,
            'stdout' => $result->stdout,
            'stderr' => $result->stderr,
            'compile_output' => $result->compileOutput,
            'message' => $result->message,
            'time' => $result->time,
            'memory' => $result->memory,
        ]);

        if (! $result->isSuccess()) {
            return $this->handleExecutionError($result);
        }

        return $this->parseTestOutput($result->stdout ?? '');
    }

    /**
     * Build the archive for multi-file submission.
     */
    private function buildArchive(string $userCode, BlockAssignmentTest $test): string
    {
        // Remove opening PHP tag from user code if present
        $userCode = preg_replace('/^<\?php\s*/i', '', trim($userCode));

        $runScript = <<<'BASH'
#!/bin/bash
cd /box

# Install PHP via apt-get (Debian)
apt-get update -qq
apt-get install -y -qq php-cli php-xml php-mbstring

# Download PHPUnit
curl -sL https://phar.phpunit.de/phpunit-11.phar -o phpunit.phar

# Run tests
php phpunit.phar --testdox --colors=never tests/
BASH;

        $solutionCode = "<?php\n{$userCode}\n";
        $testContent = $test->file_content;

        Log::info('Building archive', [
            'solution_code' => $solutionCode,
            'test_content' => $testContent,
        ]);

        return ArchiveBuilder::createArchive(
            files: [
                'solution.php' => $solutionCode,
                'tests/SolutionTest.php' => $testContent,
            ],
            runScript: $runScript,
        );
    }

    /**
     * Handle execution errors from Judge0.
     *
     * @return array{success: bool, passed: int, total: int, results: array<int, array{test: string, status: string, message?: string}>, error?: string}
     */
    private function handleExecutionError(SubmissionResult $result): array
    {
        $errorMessage = $result->stderr ?? $result->compileOutput ?? $result->status?->description ?? 'Unknown error';

        return [
            'success' => false,
            'passed' => 0,
            'total' => 0,
            'results' => [
                [
                    'test' => 'Execution',
                    'status' => 'error',
                    'message' => $errorMessage,
                ],
            ],
            'error' => $errorMessage,
        ];
    }

    /**
     * Parse PHPUnit testdox output to extract test results.
     *
     * @return array{success: bool, passed: int, total: int, results: array<int, array{test: string, status: string, message?: string}>, raw_output: string}
     */
    private function parseTestOutput(string $output): array
    {
        $results = [];
        $passed = 0;
        $failed = 0;

        $lines = explode("\n", $output);
        $currentFailureMessage = [];
        $inFailureBlock = false;
        $lastFailedTestIndex = -1;

        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            // Match passed test: " ✔ Test name" or " ✓ Test name"
            if (preg_match('/^\s*[✔✓]\s+(.+)$/u', $line, $matches)) {
                $results[] = [
                    'test' => trim($matches[1]),
                    'status' => 'passed',
                ];
                $passed++;
                $inFailureBlock = false;

                continue;
            }

            // Match failed test: " ✘ Test name" or " ✗ Test name"
            if (preg_match('/^\s*[✘✗]\s+(.+)$/u', $line, $matches)) {
                $results[] = [
                    'test' => trim($matches[1]),
                    'status' => 'failed',
                ];
                $failed++;
                $lastFailedTestIndex = count($results) - 1;
                $inFailureBlock = true;
                $currentFailureMessage = [];

                continue;
            }

            // Collect failure message lines (lines starting with │ or containing assertion messages)
            if ($inFailureBlock && $lastFailedTestIndex >= 0) {
                // Strip the │ character and collect the message
                $cleanLine = preg_replace('/^\s*│\s*/u', '', $trimmedLine);
                if ($cleanLine !== '' && $cleanLine !== $trimmedLine) {
                    $currentFailureMessage[] = $cleanLine;
                } elseif (preg_match('/^(Failed asserting|Expected|Got|Actual)/', $trimmedLine)) {
                    $currentFailureMessage[] = $trimmedLine;
                }
            }
        }

        // Attach the last failure message to the last failed test
        if ($lastFailedTestIndex >= 0 && ! empty($currentFailureMessage)) {
            $results[$lastFailedTestIndex]['message'] = implode("\n", $currentFailureMessage);
        }

        // Also try to extract failure messages from the full output for any failed tests
        $this->extractFailureMessages($output, $results);

        return [
            'success' => $failed === 0 && $passed > 0,
            'passed' => $passed,
            'total' => $passed + $failed,
            'results' => $results,
            'raw_output' => $output,
        ];
    }

    /**
     * Extract failure messages from PHPUnit output and attach to failed tests.
     *
     * @param  array<int, array{test: string, status: string, message?: string}>  $results
     */
    private function extractFailureMessages(string $output, array &$results): void
    {
        foreach ($results as &$result) {
            if ($result['status'] !== 'failed' || isset($result['message'])) {
                continue;
            }

            $testName = $result['test'];
            $methodPattern = preg_quote(str_replace(' ', '_', strtolower($testName)), '/');

            if (preg_match('/::test[^(]*'.$methodPattern.'[^)]*\)?\s*\n([^\n]+(?:\n[^\n]+)*?)(?=\n\n|\n\d+\)|\Z)/i', $output, $matches)) {
                $result['message'] = trim($matches[1]);
            }
        }
    }
}
