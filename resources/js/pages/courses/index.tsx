import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem, Course } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { BookOpen } from 'lucide-react';

interface CoursesIndexProps {
  courses: Course[];
  completedLessonIds: number[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Courses',
    href: '/courses',
  },
];

const difficultyColors = {
  easy: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
  medium: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
  hard: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
};

export default function CoursesIndex({ courses }: CoursesIndexProps) {
  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Courses" />

      <div className="py-8">
        <div className="mb-8">
          <h1 className="text-3xl font-bold">All Courses</h1>
          <p className="text-muted-foreground">Browse and start learning from our course catalog</p>
        </div>

        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {courses.map((course) => (
            <Link
              key={course.id}
              href={`/courses/${course.id}`}
              className="group overflow-hidden rounded-lg border bg-card transition-shadow hover:shadow-lg"
            >
              <div className="aspect-video overflow-hidden bg-muted">
                {course.thumbnail ? (
                  <img
                    src={course.thumbnail}
                    alt={course.name}
                    className="h-full w-full object-cover transition-transform group-hover:scale-105"
                  />
                ) : (
                  <div className="flex h-full items-center justify-center">
                    <BookOpen className="h-12 w-12 text-muted-foreground" />
                  </div>
                )}
              </div>
              <div className="p-4">
                <div className="mb-2 flex items-center justify-between">
                  <span className={`rounded-full px-2 py-1 text-xs font-medium capitalize ${difficultyColors[course.difficulty]}`}>
                    {course.difficulty}
                  </span>
                  <span className="text-muted-foreground text-sm">{course.chapters_count} chapters</span>
                </div>
                <h3 className="font-semibold group-hover:text-primary">{course.name}</h3>
                {course.description && <p className="text-muted-foreground mt-1 line-clamp-2 text-sm">{course.description}</p>}
              </div>
            </Link>
          ))}
        </div>

        {courses.length === 0 && (
          <div className="py-12 text-center">
            <BookOpen className="text-muted-foreground mx-auto h-12 w-12" />
            <h3 className="mt-4 text-lg font-semibold">No courses available</h3>
            <p className="text-muted-foreground">Check back later for new courses.</p>
          </div>
        )}
      </div>
    </AppLayout>
  );
}
