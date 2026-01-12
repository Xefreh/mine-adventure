import { Button } from '@/components/ui/button';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { Chapter, Course, Lesson } from '@/types';
import { Link, router } from '@inertiajs/react';
import { Check, ChevronDown, ChevronLeft, ChevronRight } from 'lucide-react';
import { useEffect } from 'react';

interface LessonNavProps {
  course: Course;
  chapter: Chapter;
  lesson: Lesson;
  chapters: Chapter[];
  completedLessonIds: number[];
  prevLesson: Lesson | null;
  nextLesson: Lesson | null;
  currentLessonNumber: number;
  totalLessons: number;
}

export function LessonNav({
  course,
  chapter,
  lesson,
  chapters,
  completedLessonIds,
  prevLesson,
  nextLesson,
  currentLessonNumber,
  totalLessons,
}: LessonNavProps) {
  const isCompleted = completedLessonIds.includes(lesson.id);
  const currentChapter = chapters.find((c) => c.id === chapter.id);

  const handleMarkComplete = () => {
    router.post(`/courses/${course.id}/lessons/${lesson.id}/complete`, {}, { preserveScroll: true });
  };

  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.target instanceof HTMLInputElement || e.target instanceof HTMLTextAreaElement) {
        return;
      }

      if (e.key === 'ArrowLeft' && prevLesson) {
        router.visit(`/courses/${course.id}/lessons/${prevLesson.id}`);
      } else if (e.key === 'ArrowRight' && nextLesson) {
        router.visit(`/courses/${course.id}/lessons/${nextLesson.id}`);
      }
    };

    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [course.id, prevLesson, nextLesson]);

  return (
    <div className="border-b border-sidebar-border/80 bg-background">
      <div className="mx-auto flex h-14 items-center justify-between px-4 md:max-w-7xl">
        <div className="flex items-center gap-2">
          {/* Chapter Dropdown */}
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="outline" size="sm" className="gap-2">
                <span className="max-w-[150px] truncate">{chapter.name}</span>
                <ChevronDown className="size-4" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="start" className="w-64">
              <DropdownMenuLabel>Chapters</DropdownMenuLabel>
              <DropdownMenuSeparator />
              {chapters.map((c) => {
                const chapterLessons = c.lessons || [];
                const completedInChapter = chapterLessons.filter((l) => completedLessonIds.includes(l.id)).length;
                const isCurrentChapter = c.id === chapter.id;

                return (
                  <DropdownMenuItem key={c.id} asChild className={isCurrentChapter ? 'bg-accent' : ''}>
                    <Link href={`/courses/${course.id}/lessons/${chapterLessons[0]?.id}`} className="flex w-full justify-between">
                      <span className="truncate">{c.name}</span>
                      <span className="text-muted-foreground text-xs">
                        {completedInChapter}/{chapterLessons.length}
                      </span>
                    </Link>
                  </DropdownMenuItem>
                );
              })}
            </DropdownMenuContent>
          </DropdownMenu>

          {/* Lesson Dropdown */}
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="outline" size="sm" className="gap-2">
                <span className="max-w-[200px] truncate">{lesson.name}</span>
                <ChevronDown className="size-4" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="start" className="w-72">
              <DropdownMenuLabel>Lessons in {chapter.name}</DropdownMenuLabel>
              <DropdownMenuSeparator />
              {currentChapter?.lessons?.map((l) => {
                const isLessonCompleted = completedLessonIds.includes(l.id);
                const isCurrentLesson = l.id === lesson.id;

                return (
                  <DropdownMenuItem key={l.id} asChild className={isCurrentLesson ? 'bg-accent' : ''}>
                    <Link href={`/courses/${course.id}/lessons/${l.id}`} className="flex w-full items-center gap-2">
                      {isLessonCompleted ? (
                        <Check className="size-4 shrink-0 text-green-600" />
                      ) : (
                        <span className="size-4 shrink-0" />
                      )}
                      <span className="truncate">{l.name}</span>
                    </Link>
                  </DropdownMenuItem>
                );
              })}
            </DropdownMenuContent>
          </DropdownMenu>
        </div>

        <div className="flex items-center gap-4">
          {/* Progress */}
          <span className="text-muted-foreground hidden text-sm md:inline">
            Lesson {currentLessonNumber} of {totalLessons}
          </span>

          {/* Mark Complete Button */}
          {isCompleted ? (
            <Button variant="outline" size="sm" disabled className="gap-2">
              <Check className="size-4 text-green-600" />
              Completed
            </Button>
          ) : (
            <Button variant="default" size="sm" onClick={handleMarkComplete}>
              Mark Complete
            </Button>
          )}

          {/* Navigation Arrows */}
          <div className="flex items-center gap-1">
            <Button variant="outline" size="icon" disabled={!prevLesson} asChild={!!prevLesson} className="size-8">
              {prevLesson ? (
                <Link href={`/courses/${course.id}/lessons/${prevLesson.id}`}>
                  <ChevronLeft className="size-4" />
                </Link>
              ) : (
                <span>
                  <ChevronLeft className="size-4" />
                </span>
              )}
            </Button>
            <Button variant="outline" size="icon" disabled={!nextLesson} asChild={!!nextLesson} className="size-8">
              {nextLesson ? (
                <Link href={`/courses/${course.id}/lessons/${nextLesson.id}`}>
                  <ChevronRight className="size-4" />
                </Link>
              ) : (
                <span>
                  <ChevronRight className="size-4" />
                </span>
              )}
            </Button>
          </div>
        </div>
      </div>
    </div>
  );
}
