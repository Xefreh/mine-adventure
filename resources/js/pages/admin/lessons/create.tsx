import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem, Chapter } from '@/types';
import { Head, useForm } from '@inertiajs/react';

interface AdminLessonsCreateProps {
  chapter: Chapter & { course: { id: number; name: string } };
}

export default function AdminLessonsCreate({ chapter }: AdminLessonsCreateProps) {
  const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Admin', href: '/admin' },
    { title: 'Courses', href: '/admin/courses' },
    { title: chapter.course.name, href: `/admin/courses/${chapter.course.id}/edit` },
    { title: chapter.name, href: `/admin/chapters/${chapter.id}/lessons` },
    { title: 'Create', href: `/admin/chapters/${chapter.id}/lessons/create` },
  ];

  const { data, setData, post, processing, errors } = useForm({
    name: '',
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post(`/admin/chapters/${chapter.id}/lessons`);
  };

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Create Lesson" />

      <div className="py-8">
        <Card>
          <CardHeader>
            <CardTitle>Create Lesson</CardTitle>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-6">
              <div className="space-y-2">
                <Label htmlFor="name">Name</Label>
                <Input id="name" value={data.name} onChange={(e) => setData('name', e.target.value)} placeholder="Lesson name" />
                {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
              </div>

              <div className="flex gap-4">
                <Button type="submit" disabled={processing}>
                  {processing ? 'Creating...' : 'Create Lesson'}
                </Button>
                <Button type="button" variant="outline" onClick={() => window.history.back()}>
                  Cancel
                </Button>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </AppLayout>
  );
}
