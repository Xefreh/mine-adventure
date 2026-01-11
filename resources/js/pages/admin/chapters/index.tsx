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
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem, Chapter, Course } from '@/types';
import { Head, router, useForm } from '@inertiajs/react';
import { ArrowDown, ArrowUp, Edit, Plus, Trash } from 'lucide-react';
import { useState } from 'react';

interface AdminChaptersIndexProps {
  course: Course;
  chapters: (Chapter & { lessons_count: number })[];
}

export default function AdminChaptersIndex({ course, chapters }: AdminChaptersIndexProps) {
  const [editingChapter, setEditingChapter] = useState<Chapter | null>(null);
  const [isCreateOpen, setIsCreateOpen] = useState(false);

  const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Admin', href: '/admin' },
    { title: 'Courses', href: '/admin/courses' },
    { title: course.name, href: `/admin/courses/${course.id}/edit` },
    { title: 'Chapters', href: `/admin/courses/${course.id}/chapters` },
  ];

  const createForm = useForm({
    name: '',
    position: chapters.length + 1,
  });

  const editForm = useForm({
    name: editingChapter?.name ?? '',
  });

  const handleCreate = (e: React.FormEvent) => {
    e.preventDefault();
    createForm.post(`/admin/courses/${course.id}/chapters`, {
      onSuccess: () => {
        setIsCreateOpen(false);
        createForm.reset();
      },
    });
  };

  const handleUpdate = (e: React.FormEvent) => {
    e.preventDefault();
    if (!editingChapter) return;
    editForm.patch(`/admin/courses/${course.id}/chapters/${editingChapter.id}`, {
      onSuccess: () => setEditingChapter(null),
    });
  };

  const handleDelete = (chapter: Chapter) => {
    router.delete(`/admin/courses/${course.id}/chapters/${chapter.id}`);
  };

  const handleReorder = (chapterId: number, direction: 'up' | 'down') => {
    const currentIndex = chapters.findIndex((c) => c.id === chapterId);
    if (currentIndex === -1) return;

    const newChapters = [...chapters];
    const targetIndex = direction === 'up' ? currentIndex - 1 : currentIndex + 1;

    if (targetIndex < 0 || targetIndex >= chapters.length) return;

    const reorderedChapters = newChapters.map((chapter, index) => {
      if (index === currentIndex) {
        return { id: chapter.id, position: chapters[targetIndex].position };
      }
      if (index === targetIndex) {
        return { id: chapter.id, position: chapters[currentIndex].position };
      }
      return { id: chapter.id, position: chapter.position };
    });

    router.post(`/admin/courses/${course.id}/chapters/reorder`, {
      chapters: reorderedChapters,
    });
  };

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title={`Manage Chapters - ${course.name}`} />

      <div className="py-8">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between">
            <CardTitle>Chapters for {course.name}</CardTitle>
            <Dialog open={isCreateOpen} onOpenChange={setIsCreateOpen}>
              <DialogTrigger asChild>
                <Button size="sm">
                  <Plus className="mr-2 h-4 w-4" />
                  Add Chapter
                </Button>
              </DialogTrigger>
              <DialogContent>
                <DialogHeader>
                  <DialogTitle>Add Chapter</DialogTitle>
                </DialogHeader>
                <form onSubmit={handleCreate} className="space-y-4">
                  <div className="space-y-2">
                    <Label htmlFor="create-name">Name</Label>
                    <Input id="create-name" value={createForm.data.name} onChange={(e) => createForm.setData('name', e.target.value)} />
                    {createForm.errors.name && <p className="text-sm text-destructive">{createForm.errors.name}</p>}
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="create-position">Position</Label>
                    <Input
                      id="create-position"
                      type="number"
                      value={createForm.data.position}
                      onChange={(e) => createForm.setData('position', parseInt(e.target.value))}
                    />
                    {createForm.errors.position && <p className="text-sm text-destructive">{createForm.errors.position}</p>}
                  </div>
                  <Button type="submit" disabled={createForm.processing}>
                    {createForm.processing ? 'Creating...' : 'Create Chapter'}
                  </Button>
                </form>
              </DialogContent>
            </Dialog>
          </CardHeader>
          <CardContent>
            <div className="rounded-lg border">
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead className="w-[80px]">Order</TableHead>
                    <TableHead>Name</TableHead>
                    <TableHead>Lessons</TableHead>
                    <TableHead className="w-[150px]">Actions</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {chapters.map((chapter, index) => (
                    <TableRow key={chapter.id}>
                      <TableCell>
                        <div className="flex items-center gap-1">
                          <Button
                            variant="ghost"
                            size="icon"
                            className="h-8 w-8"
                            onClick={() => handleReorder(chapter.id, 'up')}
                            disabled={index === 0}
                          >
                            <ArrowUp className="h-4 w-4" />
                          </Button>
                          <Button
                            variant="ghost"
                            size="icon"
                            className="h-8 w-8"
                            onClick={() => handleReorder(chapter.id, 'down')}
                            disabled={index === chapters.length - 1}
                          >
                            <ArrowDown className="h-4 w-4" />
                          </Button>
                        </div>
                      </TableCell>
                      <TableCell className="font-medium">{chapter.name}</TableCell>
                      <TableCell>{chapter.lessons_count}</TableCell>
                      <TableCell>
                        <div className="flex items-center gap-2">
                          <Dialog
                            open={editingChapter?.id === chapter.id}
                            onOpenChange={(open) => {
                              if (open) {
                                setEditingChapter(chapter);
                                editForm.setData('name', chapter.name);
                              } else {
                                setEditingChapter(null);
                              }
                            }}
                          >
                            <DialogTrigger asChild>
                              <Button variant="ghost" size="icon">
                                <Edit className="h-4 w-4" />
                              </Button>
                            </DialogTrigger>
                            <DialogContent>
                              <DialogHeader>
                                <DialogTitle>Edit Chapter</DialogTitle>
                              </DialogHeader>
                              <form onSubmit={handleUpdate} className="space-y-4">
                                <div className="space-y-2">
                                  <Label htmlFor="edit-name">Name</Label>
                                  <Input id="edit-name" value={editForm.data.name} onChange={(e) => editForm.setData('name', e.target.value)} />
                                  {editForm.errors.name && <p className="text-sm text-destructive">{editForm.errors.name}</p>}
                                </div>
                                <Button type="submit" disabled={editForm.processing}>
                                  {editForm.processing ? 'Saving...' : 'Save Changes'}
                                </Button>
                              </form>
                            </DialogContent>
                          </Dialog>
                          <AlertDialog>
                            <AlertDialogTrigger asChild>
                              <Button variant="ghost" size="icon">
                                <Trash className="h-4 w-4" />
                              </Button>
                            </AlertDialogTrigger>
                            <AlertDialogContent>
                              <AlertDialogHeader>
                                <AlertDialogTitle>Delete chapter</AlertDialogTitle>
                                <AlertDialogDescription>
                                  Are you sure you want to delete "{chapter.name}"? This action cannot be undone.
                                </AlertDialogDescription>
                              </AlertDialogHeader>
                              <AlertDialogFooter>
                                <AlertDialogCancel>Cancel</AlertDialogCancel>
                                <AlertDialogAction onClick={() => handleDelete(chapter)}>Delete</AlertDialogAction>
                              </AlertDialogFooter>
                            </AlertDialogContent>
                          </AlertDialog>
                        </div>
                      </TableCell>
                    </TableRow>
                  ))}
                  {chapters.length === 0 && (
                    <TableRow>
                      <TableCell colSpan={4} className="py-8 text-center text-muted-foreground">
                        No chapters yet. Add your first chapter.
                      </TableCell>
                    </TableRow>
                  )}
                </TableBody>
              </Table>
            </div>
          </CardContent>
        </Card>
      </div>
    </AppLayout>
  );
}
