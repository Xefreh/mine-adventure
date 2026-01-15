import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem, Course, Lesson } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { BookOpen, Check, ChevronRight, Clock, Lock, Play } from 'lucide-react';

interface CourseShowProps {
  course: Course;
  completedLessonIds: number[];
  accessibleLessonIds: number[];
  progressPercentage: number;
  totalLessons: number;
  completedLessons: number;
  nextLesson: Lesson | null;
}

const difficultyColors = {
  easy: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
  medium: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
  hard: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
};

export default function CourseShow({
  course,
  completedLessonIds,
  accessibleLessonIds,
  progressPercentage,
  totalLessons,
  completedLessons,
  nextLesson,
}: CourseShowProps) {
  const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Courses', href: '/courses' },
    { title: course.name, href: `/courses/${course.id}` },
  ];

  const firstLesson = course.chapters?.[0]?.lessons?.[0];
  const isStarted = completedLessons > 0;
  const isCompleted = progressPercentage === 100;

  const ctaLesson = isStarted && nextLesson ? nextLesson : firstLesson;
  const ctaText = isCompleted ? 'Review Course' : isStarted ? 'Continue Learning' : 'Start Course';

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title={course.name} />

      <div className="py-8">
        {/* Hero Section */}
        <div className="mb-8 grid gap-8 lg:grid-cols-3">
          <div className="lg:col-span-2">
            <div className="aspect-video overflow-hidden rounded-lg bg-muted">
              {course.thumbnail ? (
                <img src={course.thumbnail} alt={course.name} className="h-full w-full object-cover" />
              ) : (
                <div className="flex h-full items-center justify-center">
                  <BookOpen className="h-16 w-16 text-muted-foreground" />
                </div>
              )}
            </div>
          </div>

          <div className="flex flex-col justify-center">
            <span className={`mb-4 inline-flex w-fit rounded-full px-3 py-1 text-sm font-medium capitalize ${difficultyColors[course.difficulty]}`}>
              {course.difficulty}
            </span>
            <h1 className="mb-4 text-3xl font-bold">{course.name}</h1>

            {/* Progress */}
            <div className="mb-6">
              <div className="mb-2 flex justify-between text-sm">
                <span className="text-muted-foreground">Progress</span>
                <span className="font-medium">{progressPercentage}%</span>
              </div>
              <div className="h-2 overflow-hidden rounded-full bg-muted">
                <div className="h-full bg-primary transition-all" style={{ width: `${progressPercentage}%` }} />
              </div>
              <p className="mt-2 text-sm text-muted-foreground">
                {completedLessons} of {totalLessons} lessons completed
              </p>
            </div>

            {/* CTA Button */}
            {ctaLesson && (
              <Button asChild size="lg" className="w-full gap-2">
                <Link href={`/courses/${course.id}/lessons/${ctaLesson.id}`}>
                  <Play className="size-4" />
                  {ctaText}
                </Link>
              </Button>
            )}
          </div>
        </div>

        {/* Description */}
        {course.description && (
          <div className="mb-8">
            <h2 className="mb-4 text-xl font-semibold">About this Course</h2>
            <div className="prose dark:prose-invert max-w-none">
              <p className="text-muted-foreground">{course.description}</p>
            </div>
          </div>
        )}

        {/* Curriculum */}
        <div className="mb-8">
          <h2 className="mb-4 text-xl font-semibold">Curriculum</h2>
          <Accordion type="multiple" className="rounded-lg border">
            {course.chapters?.map((chapter, index) => {
              const chapterLessons = chapter.lessons || [];
              const completedInChapter = chapterLessons.filter((l) => completedLessonIds.includes(l.id)).length;

              return (
                <AccordionItem key={chapter.id} value={`chapter-${chapter.id}`} className="border-b last:border-b-0">
                  <AccordionTrigger className="px-4 hover:no-underline">
                    <div className="flex flex-1 items-center justify-between pr-4">
                      <div className="flex items-center gap-3">
                        <span className="flex size-8 items-center justify-center rounded-full bg-muted text-sm font-medium">
                          {index + 1}
                        </span>
                        <span className="font-medium">{chapter.name}</span>
                      </div>
                      <span className="text-muted-foreground text-sm">
                        {completedInChapter}/{chapterLessons.length} lessons
                      </span>
                    </div>
                  </AccordionTrigger>
                  <AccordionContent className="px-4 pb-4">
                    <div className="space-y-2 pl-11">
                      {chapterLessons.map((lesson) => {
                        const isLessonCompleted = completedLessonIds.includes(lesson.id);
                        const isLessonAccessible = accessibleLessonIds.includes(lesson.id);

                        if (!isLessonAccessible) {
                          return (
                            <div
                              key={lesson.id}
                              className="flex items-center justify-between rounded-md p-2 opacity-50 cursor-not-allowed"
                            >
                              <div className="flex items-center gap-3">
                                <Lock className="size-4 text-muted-foreground" />
                                <span className="text-muted-foreground">{lesson.name}</span>
                              </div>
                            </div>
                          );
                        }

                        return (
                          <Link
                            key={lesson.id}
                            href={`/courses/${course.id}/lessons/${lesson.id}`}
                            className="flex items-center justify-between rounded-md p-2 transition-colors hover:bg-muted"
                          >
                            <div className="flex items-center gap-3">
                              {isLessonCompleted ? (
                                <Check className="size-4 text-green-600" />
                              ) : (
                                <Clock className="size-4 text-muted-foreground" />
                              )}
                              <span className={isLessonCompleted ? 'text-muted-foreground' : ''}>{lesson.name}</span>
                            </div>
                            <ChevronRight className="size-4 text-muted-foreground" />
                          </Link>
                        );
                      })}
                    </div>
                  </AccordionContent>
                </AccordionItem>
              );
            })}
          </Accordion>
        </div>

        {/* FAQ Section */}
        {course.faqs && course.faqs.length > 0 && (
          <div>
            <h2 className="mb-4 text-xl font-semibold">Frequently Asked Questions</h2>
            <Accordion type="single" collapsible className="rounded-lg border">
              {course.faqs.map((faq) => (
                <AccordionItem key={faq.id} value={`faq-${faq.id}`} className="border-b last:border-b-0">
                  <AccordionTrigger className="px-4 text-left hover:no-underline">{faq.question}</AccordionTrigger>
                  <AccordionContent className="px-4 pb-4 text-muted-foreground">{faq.answer}</AccordionContent>
                </AccordionItem>
              ))}
            </Accordion>
          </div>
        )}
      </div>
    </AppLayout>
  );
}
