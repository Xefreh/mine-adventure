import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem, Course } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { BookOpen, Layers, ListChecks } from 'lucide-react';

interface AdminDashboardProps {
  stats: {
    courses: number;
    chapters: number;
    lessons: number;
  };
  recentCourses: Course[];
}

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Admin', href: '/admin' },
];

export default function AdminDashboard({ stats, recentCourses }: AdminDashboardProps) {
  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Admin Dashboard" />

      <div className="py-8">
        <div className="mb-8">
          <h1 className="text-3xl font-bold">Admin Dashboard</h1>
          <p className="text-muted-foreground">Manage your courses, chapters, and lessons</p>
        </div>

        <div className="mb-8 grid gap-4 sm:grid-cols-3">
          <Card>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium">Total Courses</CardTitle>
              <BookOpen className="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">{stats.courses}</div>
            </CardContent>
          </Card>
          <Card>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium">Total Chapters</CardTitle>
              <Layers className="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">{stats.chapters}</div>
            </CardContent>
          </Card>
          <Card>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium">Total Lessons</CardTitle>
              <ListChecks className="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">{stats.lessons}</div>
            </CardContent>
          </Card>
        </div>

        <div className="grid gap-6 md:grid-cols-2">
          <Card>
            <CardHeader>
              <CardTitle>Quick Actions</CardTitle>
            </CardHeader>
            <CardContent className="space-y-2">
              <Link href="/admin/courses" className="block rounded-lg border p-4 transition-colors hover:bg-muted">
                <h3 className="font-medium">Manage Courses</h3>
                <p className="text-sm text-muted-foreground">Create, edit, and delete courses</p>
              </Link>
              <Link href="/admin/courses/create" className="block rounded-lg border p-4 transition-colors hover:bg-muted">
                <h3 className="font-medium">Create New Course</h3>
                <p className="text-sm text-muted-foreground">Add a new course to the platform</p>
              </Link>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Recent Courses</CardTitle>
            </CardHeader>
            <CardContent>
              {recentCourses.length > 0 ? (
                <div className="space-y-2">
                  {recentCourses.map((course) => (
                    <Link
                      key={course.id}
                      href={`/admin/courses/${course.id}/edit`}
                      className="flex items-center justify-between rounded-lg border p-3 transition-colors hover:bg-muted"
                    >
                      <span className="font-medium">{course.name}</span>
                      <span className="text-sm text-muted-foreground">{course.chapters_count} chapters</span>
                    </Link>
                  ))}
                </div>
              ) : (
                <p className="text-muted-foreground">No courses yet</p>
              )}
            </CardContent>
          </Card>
        </div>
      </div>
    </AppLayout>
  );
}
