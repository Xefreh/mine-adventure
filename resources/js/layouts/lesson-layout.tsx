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
  prevLesson,
  nextLesson,
  currentLessonNumber,
  totalLessons,
  canComplete = true,
}: PropsWithChildren<LessonLayoutProps>) {
  return (
    <AppHeaderLayout
      breadcrumbs={[
        { title: 'Courses', href: '/courses' },
        { title: course.name, href: `/courses/${course.id}` },
        { title: lesson.name, href: `/courses/${course.id}/lessons/${lesson.id}` },
      ]}
    >
      <div className="flex min-h-[calc(100vh-4rem)] flex-col">
        <LessonNav
          course={course}
          chapter={chapter}
          lesson={lesson}
          chapters={chapters}
          completedLessonIds={completedLessonIds}
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
