import { LessonNav } from '@/components/lesson-nav';
import AppHeaderLayout from '@/layouts/app/app-header-layout';
import type { Chapter, Course, Lesson } from '@/types';
import type { PropsWithChildren } from 'react';

interface LessonLayoutProps {
  course: Course;
  chapter: Chapter;
  lesson: Lesson;
  chapters: Chapter[];
  completedLessonIds: number[];
  accessibleLessonIds: number[];
  prevLesson: Lesson | null;
  nextLesson: Lesson | null;
  currentLessonNumber: number;
  totalLessons: number;
  canComplete?: boolean;
}

export default function LessonLayout({
  children,
  course,
  chapter,
  lesson,
  chapters,
  completedLessonIds,
  accessibleLessonIds,
  prevLesson,
  nextLesson,
  currentLessonNumber,
  totalLessons,
  canComplete = true,
}: PropsWithChildren<LessonLayoutProps>) {
  return (
    <AppHeaderLayout fullWidth>
      <div className="flex flex-col">
        <LessonNav
          course={course}
          chapter={chapter}
          lesson={lesson}
          chapters={chapters}
          completedLessonIds={completedLessonIds}
          accessibleLessonIds={accessibleLessonIds}
          prevLesson={prevLesson}
          nextLesson={nextLesson}
          currentLessonNumber={currentLessonNumber}
          totalLessons={totalLessons}
          canComplete={canComplete}
        />
        <div className="flex-1">{children}</div>
      </div>
    </AppHeaderLayout>
  );
}
