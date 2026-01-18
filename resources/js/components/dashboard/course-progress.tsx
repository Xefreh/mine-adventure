import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import type { CurrentCourseProgress, Lesson } from '@/types';
import { Link } from '@inertiajs/react';
import { Check, Lock, Play } from 'lucide-react';

interface CourseProgressProps {
  progress: CurrentCourseProgress;
  nextLesson: Lesson | null;
}

interface TimelineNode {
  type: 'chapter' | 'lesson';
  id: number;
  name: string;
  isComplete: boolean;
  isCurrent: boolean;
  isLocked: boolean;
  chapterName?: string;
  lessonsCompleted?: number;
  totalLessons?: number;
}

export function CourseProgress({ progress, nextLesson }: CourseProgressProps) {
  const { course, progressPercentage, completedLessons, totalLessons, chapters } = progress;

  // Flatten chapters and lessons into a single timeline (desktop)
  const timelineNodes: TimelineNode[] = [];
  chapters.forEach((chapter) => {
    // Add chapter node
    timelineNodes.push({
      type: 'chapter',
      id: chapter.id,
      name: chapter.name,
      isComplete: chapter.isComplete,
      isCurrent: chapter.isCurrent,
      isLocked: chapter.isLocked,
      lessonsCompleted: chapter.lessonsCompleted,
      totalLessons: chapter.totalLessons,
    });
    // Add lesson nodes
    chapter.lessons.forEach((lesson) => {
      timelineNodes.push({
        type: 'lesson',
        id: lesson.id,
        name: lesson.name,
        isComplete: lesson.isComplete,
        isCurrent: lesson.isCurrent,
        isLocked: lesson.isLocked || chapter.isLocked,
        chapterName: chapter.name,
      });
    });
  });

  // Mobile: show current lesson and next 3 nodes
  const currentLessonIndex = timelineNodes.findIndex((node) => node.type === 'lesson' && node.isCurrent);
  const mobileStartIndex = currentLessonIndex >= 0 ? currentLessonIndex : 0;
  const mobileNodes: TimelineNode[] = timelineNodes.slice(mobileStartIndex, mobileStartIndex + 4);

  // Calculate progress for desktop (full timeline)
  let lastCompletedIndex = timelineNodes.reduce((lastIdx, node, idx) => {
    return node.isComplete ? idx : lastIdx;
  }, -1);

  if (
    lastCompletedIndex >= 0 &&
    lastCompletedIndex < timelineNodes.length - 1
  ) {
    const current = timelineNodes[lastCompletedIndex];
    const next = timelineNodes[lastCompletedIndex + 1];

    if (
      (current.type === 'lesson' && next.type === 'chapter') ||
      (current.type === 'chapter' && next.type === 'lesson' && next.isCurrent)
    ) {
      lastCompletedIndex = lastCompletedIndex + 1;
    }
  }

  const progressLinePercentage = timelineNodes.length > 1
    ? (lastCompletedIndex / (timelineNodes.length - 1)) * 100
    : 0;

  return (
    <Card className="mb-8">
      <CardHeader className="flex flex-row items-center justify-between pb-2">
        <div>
          <CardTitle className="text-lg">Continue Learning</CardTitle>
          <p className="text-muted-foreground text-sm">{course.name}</p>
        </div>
        {nextLesson && (
          <Button asChild>
            <Link href={`/courses/${course.id}/lessons/${nextLesson.id}`}>
              <Play className="mr-2 size-4" />
              Continue
            </Link>
          </Button>
        )}
      </CardHeader>
      <CardContent>
        {/* Progress Bar */}
        <div className="mb-8">
          <div className="mb-2 flex justify-between text-sm">
            <span className="text-muted-foreground">
              {completedLessons} of {totalLessons} lessons completed
            </span>
            <span className="font-medium">{progressPercentage}%</span>
          </div>
          <Progress value={progressPercentage} className="h-2" />
        </div>

        {/* Mobile Timeline - Current lesson + next 3 nodes */}
        <TooltipProvider delayDuration={200}>
          <div className="relative py-8 md:hidden">
            {/* Base Line */}
            <div className="absolute top-1/2 left-0 right-0 h-1 -translate-y-1/2 bg-muted rounded-full" />

            {/* Progress Line - goes to first node (current lesson) */}
            <div
              className="absolute top-1/2 left-0 h-1 -translate-y-1/2 bg-green-600 rounded-full transition-all duration-300"
              style={{ width: mobileNodes.length > 0 ? '0.75rem' : 0 }}
            />

            {/* Nodes */}
            <div className="relative flex justify-between items-center">
              {mobileNodes.map((node) => (
                <TimelineNodeComponent
                  key={`${node.type}-${node.id}`}
                  node={node}
                  courseId={course.id}
                />
              ))}
            </div>
          </div>

          {/* Desktop Timeline - Full with chapters and lessons */}
        <div className="relative py-8 hidden md:block">
            {/* Base Line */}
            <div className="absolute top-1/2 left-0 right-0 h-1 -translate-y-1/2 bg-muted rounded-full" />

            {/* Progress Line */}
            <div
              className="absolute top-1/2 left-0 h-1 -translate-y-1/2 bg-green-600 rounded-full transition-all duration-300"
              style={{ width: lastCompletedIndex >= 0 ? `calc(${progressLinePercentage}% + 0.75rem)` : 0 }}
            />

            {/* Nodes */}
            <div className="relative flex justify-between items-center">
              {timelineNodes.map((node) => (
                <TimelineNodeComponent
                  key={`${node.type}-${node.id}`}
                  node={node}
                  courseId={course.id}
                />
              ))}
            </div>
          </div>
        </TooltipProvider>
      </CardContent>
    </Card>
  );
}

function TimelineNodeComponent({
  node,
  courseId,
}: {
  node: TimelineNode;
  courseId: number;
}) {
  const isChapter = node.type === 'chapter';

  const getStyles = () => {
    if (node.isComplete) {
      return 'bg-green-600 text-white border-green-600';
    }
    if (node.isCurrent) {
      return 'bg-primary text-primary-foreground border-primary ring-4 ring-primary/20';
    }
    if (node.isLocked) {
      return 'bg-background text-muted-foreground border-muted';
    }
    return 'bg-background text-muted-foreground border-muted';
  };

  const isClickable = node.type === 'lesson' && !node.isLocked;

  const nodeElement = (
    <div className="relative">
      {/* Pulse ring effect for current lesson */}
      {node.isCurrent && !isChapter && (
        <div className="absolute inset-0 rounded-full bg-primary animate-ping opacity-75" />
      )}
      <div
        className={`
          relative flex items-center justify-center rounded-full border-2 transition-transform
          ${getStyles()}
          ${isChapter ? 'size-10' : 'size-6'}
          ${isClickable ? 'hover:scale-110 cursor-pointer' : ''}
        `}
      >
        {node.isComplete ? (
          <Check className={isChapter ? 'size-5' : 'size-3'} />
        ) : node.isLocked ? (
          <Lock className={isChapter ? 'size-4' : 'size-2.5'} />
        ) : isChapter ? (
          <span className="text-xs font-bold">{node.lessonsCompleted}</span>
        ) : (
          <span className="size-2 rounded-full bg-current opacity-40" />
        )}
      </div>
    </div>
  );

  return (
    <div className="flex flex-col items-center">
      <Tooltip>
        <TooltipTrigger asChild>
          {isClickable ? (
            <Link href={`/courses/${courseId}/lessons/${node.id}`}>{nodeElement}</Link>
          ) : (
            <span>{nodeElement}</span>
          )}
        </TooltipTrigger>
        <TooltipContent side="top" className="text-xs max-w-[200px]">
          {isChapter ? (
            <>
              <p className="font-semibold">{node.name}</p>
              <p className="text-muted-foreground">
                {node.lessonsCompleted}/{node.totalLessons} lessons completed
              </p>
            </>
          ) : (
            <>
              <p className="font-medium">{node.name}</p>
              <p className="text-muted-foreground">{node.chapterName}</p>
              <p className="text-muted-foreground mt-1">
                {node.isComplete ? 'Completed' : node.isCurrent ? 'Current lesson' : node.isLocked ? 'Locked' : 'Not started'}
              </p>
            </>
          )}
        </TooltipContent>
      </Tooltip>
    </div>
  );
}
