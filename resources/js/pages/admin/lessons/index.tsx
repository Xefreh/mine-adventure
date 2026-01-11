import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem, Chapter, Lesson } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { Edit, Plus, Trash } from 'lucide-react';

interface AdminLessonsIndexProps {
  chapter: Chapter & { course: { id: number; name: string } };
  lessons: (Lesson & { blocks_count: number })[];
}

export default function AdminLessonsIndex({ chapter, lessons }: AdminLessonsIndexProps) {
  const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Admin', href: '/admin' },
    { title: 'Courses', href: '/admin/courses' },
    { title: chapter.course.name, href: `/admin/courses/${chapter.course.id}/edit` },
    { title: chapter.name, href: `/admin/chapters/${chapter.id}/lessons` },
  ];

  const handleDelete = (lesson: Lesson) => {
    router.delete(`/admin/chapters/${chapter.id}/lessons/${lesson.id}`);
  };

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title={`Lessons - ${chapter.name}`} />

      <div className="py-8">
        <div className="mb-8 flex items-center justify-between">
          <div>
            <h1 className="text-2xl font-bold">{chapter.name}</h1>
            <p className="text-muted-foreground">Manage lessons in this chapter</p>
          </div>
          <Link href={`/admin/chapters/${chapter.id}/lessons/create`}>
            <Button>
              <Plus className="mr-2 h-4 w-4" />
              Add Lesson
            </Button>
          </Link>
        </div>

        <div className="rounded-lg border">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Name</TableHead>
                <TableHead>Blocks</TableHead>
                <TableHead className="w-[100px]">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {lessons.map((lesson) => (
                <TableRow key={lesson.id}>
                  <TableCell className="font-medium">{lesson.name}</TableCell>
                  <TableCell>{lesson.blocks_count}</TableCell>
                  <TableCell>
                    <div className="flex items-center gap-2">
                      <Link href={`/admin/chapters/${chapter.id}/lessons/${lesson.id}`}>
                        <Button variant="ghost" size="icon">
                          <Edit className="h-4 w-4" />
                        </Button>
                      </Link>
                      <AlertDialog>
                        <AlertDialogTrigger asChild>
                          <Button variant="ghost" size="icon">
                            <Trash className="h-4 w-4" />
                          </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                          <AlertDialogHeader>
                            <AlertDialogTitle>Delete lesson</AlertDialogTitle>
                            <AlertDialogDescription>
                              Are you sure you want to delete "{lesson.name}"? This action cannot be undone.
                            </AlertDialogDescription>
                          </AlertDialogHeader>
                          <AlertDialogFooter>
                            <AlertDialogCancel>Cancel</AlertDialogCancel>
                            <AlertDialogAction onClick={() => handleDelete(lesson)}>Delete</AlertDialogAction>
                          </AlertDialogFooter>
                        </AlertDialogContent>
                      </AlertDialog>
                    </div>
                  </TableCell>
                </TableRow>
              ))}
              {lessons.length === 0 && (
                <TableRow>
                  <TableCell colSpan={3} className="py-8 text-center text-muted-foreground">
                    No lessons yet. Add your first lesson.
                  </TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        </div>
      </div>
    </AppLayout>
  );
}
