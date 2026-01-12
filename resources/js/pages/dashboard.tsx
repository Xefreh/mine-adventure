import { CourseProgress } from '@/components/dashboard/course-progress';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { useCourseStore } from '@/stores/course-store';
import type { BreadcrumbItem, Course, CurrentCourseProgress, DashboardStats, Lesson, SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { BookOpen, CheckCircle2, Flame, GraduationCap, Trophy } from 'lucide-react';
import { useEffect } from 'react';

interface DashboardProps {
  courses: Course[];
  currentCourseProgress: CurrentCourseProgress | null;
  nextLesson: Lesson | null;
  stats: DashboardStats;
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: dashboard().url,
  },
];

const difficultyColors = {
  easy: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
  medium: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
  hard: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
};

export default function Dashboard({ courses, currentCourseProgress, nextLesson, stats }: DashboardProps) {
  const { auth } = usePage<SharedData>().props;
  const { setCourses, isLoaded } = useCourseStore();

  useEffect(() => {
    if (!isLoaded) {
      setCourses(courses);
    }
  }, [courses, isLoaded, setCourses]);

  const getGreeting = () => {
    const hour = new Date().getHours();
    if (hour < 12) return 'Good morning';
    if (hour < 18) return 'Good afternoon';
    return 'Good evening';
  };

  const firstName = auth.user.name.split(' ')[0];

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Dashboard" />

      <div className="py-8">
        {/* Welcome Section */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold">
            {getGreeting()}, {firstName}!
          </h1>
          <p className="text-muted-foreground">
            {stats.totalLessonsCompleted > 0
              ? "Ready to continue your learning journey?"
              : "Welcome to Mine Adventure! Start your coding journey today."}
          </p>
        </div>

        {/* Stats Cards */}
        <div className="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <Card>
            <CardHeader className="flex flex-row items-center justify-between pb-2">
              <CardTitle className="text-sm font-medium">Lessons Completed</CardTitle>
              <CheckCircle2 className="size-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">{stats.totalLessonsCompleted}</div>
              <p className="text-xs text-muted-foreground">
                {stats.totalLessonsCompleted === 1 ? 'lesson' : 'lessons'} finished
              </p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader className="flex flex-row items-center justify-between pb-2">
              <CardTitle className="text-sm font-medium">Courses Started</CardTitle>
              <BookOpen className="size-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">{stats.totalCoursesStarted}</div>
              <p className="text-xs text-muted-foreground">
                of {courses.length} available
              </p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader className="flex flex-row items-center justify-between pb-2">
              <CardTitle className="text-sm font-medium">Courses Completed</CardTitle>
              <Trophy className="size-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">{stats.totalCoursesCompleted}</div>
              <p className="text-xs text-muted-foreground">
                {stats.totalCoursesCompleted === 1 ? 'course' : 'courses'} mastered
              </p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader className="flex flex-row items-center justify-between pb-2">
              <CardTitle className="text-sm font-medium">Current Streak</CardTitle>
              <Flame className={`size-4 ${stats.currentStreak > 0 ? 'text-orange-500' : 'text-muted-foreground'}`} />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">{stats.currentStreak}</div>
              <p className="text-xs text-muted-foreground">
                {stats.currentStreak === 1 ? 'day' : 'days'} in a row
              </p>
            </CardContent>
          </Card>
        </div>

        {/* Current Course Progress */}
        {currentCourseProgress && (
          <CourseProgress progress={currentCourseProgress} nextLesson={nextLesson} />
        )}

        {/* Courses Section */}
        <div className="mb-6 flex items-center gap-2">
          <GraduationCap className="size-5" />
          <h2 className="text-xl font-semibold">All Courses</h2>
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
                  <img src={course.thumbnail} alt={course.name} className="h-full w-full object-cover transition-transform group-hover:scale-105" />
                ) : (
                  <div className="flex h-full items-center justify-center">
                    <BookOpen className="h-12 w-12 text-muted-foreground" />
                  </div>
                )}
              </div>
              <div className="p-4">
                <div className="mb-2 flex items-center justify-between">
                  <span className={`rounded-full px-2 py-1 text-xs font-medium ${difficultyColors[course.difficulty]}`}>{course.difficulty}</span>
                  <span className="text-sm text-muted-foreground">{course.chapters_count} chapters</span>
                </div>
                <h3 className="font-semibold group-hover:text-primary">{course.name}</h3>
              </div>
            </Link>
          ))}
        </div>

        {courses.length === 0 && (
          <div className="py-12 text-center">
            <BookOpen className="mx-auto h-12 w-12 text-muted-foreground" />
            <h3 className="mt-4 text-lg font-semibold">No courses available</h3>
            <p className="text-muted-foreground">Check back later for new courses.</p>
          </div>
        )}
      </div>
    </AppLayout>
  );
}
