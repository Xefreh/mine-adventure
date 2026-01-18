<?php

namespace App\Services;

use App\Models\BlockAssignmentTest;
use Xefreh\Judge0PhpClient\DTO\Submission;
use Xefreh\Judge0PhpClient\DTO\SubmissionResult;
use Xefreh\Judge0PhpClient\Exceptions\ApiException;
use Xefreh\Judge0PhpClient\Judge0Client;
use Xefreh\Judge0PhpClient\Utils\ArchiveBuilder;

class TestSubmissionService
{
    private const int LANGUAGE_ID_MULTI_FILE = 89;

    private const string JUNIT_JAR_PATH = 'junit-platform-console-standalone.jar';

    public function __construct(
        private readonly Judge0Client $client,
    ) {}

    /**
     * Execute user code against JUnit 5 tests.
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
        );

        $result = $this->client->submissions->create($submission, wait: true);

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
        $runScript = <<<'BASH'
#!/bin/bash
/usr/local/jdk17/bin/javac -cp .:junit-platform-console-standalone.jar Main.java tests/SolutionTest.java
if [ $? -ne 0 ]; then
    exit 1
fi

/usr/local/jdk17/bin/java -jar junit-platform-console-standalone.jar \
  --class-path .:tests \
  --scan-class-path \
  --details=tree
BASH;

        $testContent = $test->file_content;

        return ArchiveBuilder::createArchive(
            files: [
                'Main.java' => $userCode,
                'tests/SolutionTest.java' => $testContent,
                self::JUNIT_JAR_PATH => $this->getJunitJarContent(),
            ],
            runScript: $runScript,
        );
    }

    /**
     * Get the JUnit JAR content from storage.
     */
    private function getJunitJarContent(): string
    {
        $jarPath = storage_path('app/junit-platform-console-standalone.jar');

        if (! file_exists($jarPath)) {
            throw new \RuntimeException('JUnit JAR file not found at: '.$jarPath);
        }

        return file_get_contents($jarPath);
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
     * Parse JUnit 5 tree output to extract test results.
     *
     * @return array{success: bool, passed: int, total: int, results: array<int, array{test: string, status: string, message?: string}>, raw_output: string}
     */
    private function parseTestOutput(string $output): array
    {
        $results = [];
        $passed = 0;
        $failed = 0;

        // Strip ANSI color codes for easier parsing
        $cleanOutput = preg_replace('/\x1b\[[0-9;]*m/', '', $output);
        $lines = explode("\n", $cleanOutput);

        foreach ($lines as $line) {
            // Match passed test: "testMethodName()" followed by ✔ or [OK]
            if (preg_match('/(\w+)\(\)\s*[✔✓]/', $line, $matches) ||
                preg_match('/(\w+)\(\)\s*\[OK]/i', $line, $matches)) {
                $results[] = [
                    'test' => $this->formatTestName($matches[1]),
                    'status' => 'passed',
                ];
                $passed++;

                continue;
            }

            // Match failed test: "testMethodName()" followed by ✘ or [X]
            if (preg_match('/(\w+)\(\)\s*[✘✗]/', $line, $matches) ||
                preg_match('/(\w+)\(\)\s*\[X]/i', $line, $matches)) {
                $results[] = [
                    'test' => $this->formatTestName($matches[1]),
                    'status' => 'failed',
                ];
                $failed++;
            }
        }

        // Extract failure messages
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
     * Format test method name to human-readable form.
     */
    private function formatTestName(string $methodName): string
    {
        // Remove "test" prefix if present
        $name = preg_replace('/^test/i', '', $methodName);

        // Convert camelCase to spaces
        $name = preg_replace('/([a-z])([A-Z])/', '$1 $2', $name);

        return trim(ucfirst($name));
    }

    /**
     * Extract failure messages from JUnit output and attach to failed tests.
     *
     * @param  array<int, array{test: string, status: string, message?: string}>  $results
     */
    private function extractFailureMessages(string $output, array &$results): void
    {
        // Look for assertion failure patterns in JUnit output
        foreach ($results as &$result) {
            if ($result['status'] !== 'failed') {
                continue;
            }

            // Try to find assertion error messages
            if (preg_match('/expected:\s*<(.+?)>\s*but was:\s*<(.+?)>/i', $output, $matches)) {
                $result['message'] = "Expected: {$matches[1]}\nActual: {$matches[2]}";
            } elseif (preg_match('/AssertionFailedError:\s*(.+?)(?:\n|\r|$)/i', $output, $matches)) {
                $result['message'] = trim($matches[1]);
            }
        }
    }
}
