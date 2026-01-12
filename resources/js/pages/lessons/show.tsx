import { run, submit } from '@/actions/App/Http/Controllers/CodeExecutionController';
import { BlockRenderer } from '@/components/blocks/block-renderer';
import { CodeEditor } from '@/components/code-editor';
import { EditorActionBar } from '@/components/editor-action-bar';
import { TerminalOutput } from '@/components/terminal-output';
import LessonLayout from '@/layouts/lesson-layout';
import type { Chapter, Course, Lesson, LessonBlock } from '@/types';
import { Head } from '@inertiajs/react';
import { useMemo, useRef, useState } from 'react';

interface LessonShowProps {
  course: Course;
  lesson: Lesson;
  chapters: Chapter[];
  completedLessonIds: number[];
  prevLesson: Lesson | null;
  nextLesson: Lesson | null;
  currentLessonNumber: number;
  totalLessons: number;
}

export default function LessonShow({
  course,
  lesson,
  chapters,
  completedLessonIds,
  prevLesson,
  nextLesson,
  currentLessonNumber,
  totalLessons,
}: LessonShowProps) {
  const blocks = lesson.blocks || [];

  const { hasAssignment, assignmentBlock, contentBlocks } = useMemo(() => {
    const assignment = blocks.find((b) => b.type === 'assignment');
    const content = blocks.filter((b) => b.type !== 'assignment' || b.id === assignment?.id);

    return {
      hasAssignment: !!assignment,
      assignmentBlock: assignment,
      contentBlocks: content,
    };
  }, [blocks]);

  const chapter = lesson.chapter || chapters.find((c) => c.id === lesson.chapter_id);

  if (!chapter) {
    return null;
  }

  return (
    <LessonLayout
      course={course}
      chapter={chapter}
      lesson={lesson}
      chapters={chapters}
      completedLessonIds={completedLessonIds}
      prevLesson={prevLesson}
      nextLesson={nextLesson}
      currentLessonNumber={currentLessonNumber}
      totalLessons={totalLessons}
    >
      <Head title={`${lesson.name} - ${course.name}`} />

      {hasAssignment ? (
        <SplitViewLayout
          contentBlocks={contentBlocks}
          assignmentBlock={assignmentBlock!}
        />
      ) : (
        <ColumnViewLayout blocks={contentBlocks} />
      )}
    </LessonLayout>
  );
}

function ColumnViewLayout({ blocks }: { blocks: LessonBlock[] }) {
  return (
    <div className="mx-auto max-w-4xl px-4 py-8">
      <div className="space-y-8">
        {blocks.map((block) => (
          <BlockRenderer key={block.id} block={block} />
        ))}
      </div>
    </div>
  );
}

function SplitViewLayout({
  contentBlocks,
  assignmentBlock,
}: {
  contentBlocks: LessonBlock[];
  assignmentBlock: LessonBlock;
}) {
  const assignment = assignmentBlock.assignment;

  const [code, setCode] = useState(assignment?.starter_code || '');
  const [output, setOutput] = useState('');
  const [isRunning, setIsRunning] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [solutionRevealed, setSolutionRevealed] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const savedCodeRef = useRef<string>('');

  const handleRun = async () => {
    if (!assignment) return;

    setIsRunning(true);
    setOutput('');
    setError(null);

    try {
      const response = await fetch(run.url(assignment.id), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        },
        body: JSON.stringify({ code }),
      });

      if (!response.ok) {
        const text = await response.text();
        setError(`Server error (${response.status}): ${text.slice(0, 200)}`);
        return;
      }

      const data = await response.json();

      if (data.success) {
        setOutput(data.output || 'Program executed successfully with no output.');
      } else {
        setError(data.error || data.status || 'Execution failed');
        if (data.output) {
          setOutput(data.output);
        }
      }
    } catch (err) {
      setError(`Failed to execute code: ${err instanceof Error ? err.message : 'Unknown error'}`);
    } finally {
      setIsRunning(false);
    }
  };

  const handleSubmit = async () => {
    if (!assignment) return;

    setIsSubmitting(true);
    setOutput('');
    setError(null);

    try {
      const response = await fetch(submit.url(assignment.id), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        },
        body: JSON.stringify({ code }),
      });

      const data = await response.json();

      if (data.success) {
        setOutput(`All tests passed! (${data.passed}/${data.total})`);
      } else {
        const details = data.results
          ?.map((r: { passed: boolean }, i: number) => `Test ${i + 1}: ${r.passed ? '✓' : '✗'}`)
          .join('\n');
        setError(`Tests failed: ${data.passed}/${data.total} passed`);
        if (details) {
          setOutput(details);
        }
      }
    } catch {
      setError('Failed to submit code. Please try again.');
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleRevealSolution = () => {
    if (!solutionRevealed && assignment?.solution) {
      // Save the user's current code before showing solution
      savedCodeRef.current = code;
      setCode(assignment.solution);
    } else {
      // Restore the user's saved code
      setCode(savedCodeRef.current);
    }
    setSolutionRevealed(!solutionRevealed);
  };

  const handleClearOutput = () => {
    setOutput('');
    setError(null);
  };

  return (
    <div className="mx-auto max-w-7xl px-4 py-8">
      <div className="grid gap-8 lg:grid-cols-2">
        {/* Left Panel - Content */}
        <div className="space-y-8">
          {contentBlocks.map((block) => (
            <BlockRenderer key={block.id} block={block} />
          ))}
        </div>

        {/* Right Panel - Code Editor */}
        <div className="lg:sticky lg:top-4 lg:self-start">
          <div className="overflow-hidden rounded-lg border">
            <div className="border-b bg-muted/50 px-4 py-2">
              <h3 className="font-medium">Code Editor</h3>
              <p className="text-muted-foreground text-sm">{assignment?.language || 'php'}</p>
            </div>
            <CodeEditor
              language={assignment?.language || 'php'}
              value={code}
              onChange={setCode}
              height="300px"
            />
            <EditorActionBar
              onRun={handleRun}
              onSubmit={handleSubmit}
              onRevealSolution={handleRevealSolution}
              isRunning={isRunning}
              isSubmitting={isSubmitting}
              solutionRevealed={solutionRevealed}
              hasSolution={!!assignment?.solution}
            />
            <TerminalOutput
              output={output}
              isRunning={isRunning}
              error={error}
              onClear={handleClearOutput}
            />
          </div>
        </div>
      </div>
    </div>
  );
}
