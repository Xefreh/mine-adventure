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
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem, Chapter, Course, CourseDifficulty, CourseFaq } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { Edit, GripVertical, HelpCircle, Layers, Plus, Trash } from 'lucide-react';
import { useState } from 'react';

interface AdminCoursesEditProps {
  course: Course & { chapters: (Chapter & { lessons_count: number })[]; faqs: CourseFaq[] };
}

export default function AdminCoursesEdit({ course }: AdminCoursesEditProps) {
  const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Admin', href: '/admin' },
    { title: 'Courses', href: '/admin/courses' },
    { title: course.name, href: `/admin/courses/${course.id}/edit` },
  ];

  const { data, setData, patch, processing, errors } = useForm({
    name: course.name,
    thumbnail: course.thumbnail,
    description: course.description ?? '',
    difficulty: course.difficulty,
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    patch(`/admin/courses/${course.id}`);
  };

  const handleDeleteChapter = (chapter: Chapter) => {
    router.delete(`/admin/courses/${course.id}/chapters/${chapter.id}`);
  };

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title={`Edit ${course.name}`} />

      <div className="py-8">
        <div className="space-y-8">
          <Card>
            <CardHeader>
              <CardTitle>Edit Course</CardTitle>
            </CardHeader>
            <CardContent>
              <form onSubmit={handleSubmit} className="space-y-6">
                <div className="space-y-2">
                  <Label htmlFor="name">Name</Label>
                  <Input id="name" value={data.name} onChange={(e) => setData('name', e.target.value)} />
                  {errors.name && <p className="text-destructive text-sm">{errors.name}</p>}
                </div>

                <div className="space-y-2">
                  <Label htmlFor="thumbnail">Thumbnail URL</Label>
                  <Input id="thumbnail" value={data.thumbnail} onChange={(e) => setData('thumbnail', e.target.value)} />
                  {errors.thumbnail && <p className="text-destructive text-sm">{errors.thumbnail}</p>}
                </div>

                <div className="space-y-2">
                  <Label htmlFor="description">Description</Label>
                  <Textarea
                    id="description"
                    value={data.description}
                    onChange={(e) => setData('description', e.target.value)}
                    placeholder="Enter course description..."
                    className="min-h-[120px] resize-y"
                  />
                  {errors.description && <p className="text-destructive text-sm">{errors.description}</p>}
                </div>

                <div className="space-y-2">
                  <Label htmlFor="difficulty">Difficulty</Label>
                  <Select value={data.difficulty} onValueChange={(value) => setData('difficulty', value as CourseDifficulty)}>
                    <SelectTrigger>
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="easy">Easy</SelectItem>
                      <SelectItem value="medium">Medium</SelectItem>
                      <SelectItem value="hard">Hard</SelectItem>
                    </SelectContent>
                  </Select>
                  {errors.difficulty && <p className="text-destructive text-sm">{errors.difficulty}</p>}
                </div>

                <Button type="submit" disabled={processing}>
                  {processing ? 'Saving...' : 'Save Changes'}
                </Button>
              </form>
            </CardContent>
          </Card>

          <FaqSection courseId={course.id} faqs={course.faqs ?? []} />

          <Card>
            <CardHeader className="flex flex-row items-center justify-between">
              <CardTitle className="flex items-center gap-2">
                <Layers className="h-5 w-5" />
                Chapters
              </CardTitle>
              <Link href={`/admin/courses/${course.id}/chapters`}>
                <Button size="sm">
                  <Plus className="mr-2 h-4 w-4" />
                  Manage Chapters
                </Button>
              </Link>
            </CardHeader>
            <CardContent>
              <div className="rounded-lg border">
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>Position</TableHead>
                      <TableHead>Name</TableHead>
                      <TableHead>Lessons</TableHead>
                      <TableHead className="w-[100px]">Actions</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {course.chapters.map((chapter) => (
                      <TableRow key={chapter.id}>
                        <TableCell>{chapter.position}</TableCell>
                        <TableCell className="font-medium">{chapter.name}</TableCell>
                        <TableCell>{chapter.lessons_count}</TableCell>
                        <TableCell>
                          <div className="flex items-center gap-2">
                            <Link href={`/admin/chapters/${chapter.id}/lessons`}>
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
                                  <AlertDialogTitle>Delete chapter</AlertDialogTitle>
                                  <AlertDialogDescription>
                                    Are you sure you want to delete "{chapter.name}"? This action cannot be undone.
                                  </AlertDialogDescription>
                                </AlertDialogHeader>
                                <AlertDialogFooter>
                                  <AlertDialogCancel>Cancel</AlertDialogCancel>
                                  <AlertDialogAction onClick={() => handleDeleteChapter(chapter)}>Delete</AlertDialogAction>
                                </AlertDialogFooter>
                              </AlertDialogContent>
                            </AlertDialog>
                          </div>
                        </TableCell>
                      </TableRow>
                    ))}
                    {course.chapters.length === 0 && (
                      <TableRow>
                        <TableCell colSpan={4} className="text-muted-foreground py-8 text-center">
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
      </div>
    </AppLayout>
  );
}

interface FaqSectionProps {
  courseId: number;
  faqs: CourseFaq[];
}

function FaqSection({ courseId, faqs }: FaqSectionProps) {
  const [localFaqs, setLocalFaqs] = useState(faqs);
  const [newQuestion, setNewQuestion] = useState('');
  const [newAnswer, setNewAnswer] = useState('');
  const [editingId, setEditingId] = useState<number | null>(null);
  const [editQuestion, setEditQuestion] = useState('');
  const [editAnswer, setEditAnswer] = useState('');

  const handleAddFaq = () => {
    if (!newQuestion.trim() || !newAnswer.trim()) return;

    router.post(
      `/admin/courses/${courseId}/faqs`,
      {
        question: newQuestion,
        answer: newAnswer,
        order: localFaqs.length,
      },
      {
        preserveScroll: true,
        onSuccess: () => {
          setNewQuestion('');
          setNewAnswer('');
        },
      },
    );
  };

  const handleUpdateFaq = (faqId: number) => {
    router.patch(
      `/admin/courses/${courseId}/faqs/${faqId}`,
      {
        question: editQuestion,
        answer: editAnswer,
      },
      {
        preserveScroll: true,
        onSuccess: () => {
          setEditingId(null);
        },
      },
    );
  };

  const handleDeleteFaq = (faqId: number) => {
    router.delete(`/admin/courses/${courseId}/faqs/${faqId}`, {
      preserveScroll: true,
    });
  };

  const startEditing = (faq: CourseFaq) => {
    setEditingId(faq.id);
    setEditQuestion(faq.question);
    setEditAnswer(faq.answer);
  };

  return (
    <Card>
      <CardHeader>
        <CardTitle className="flex items-center gap-2">
          <HelpCircle className="h-5 w-5" />
          FAQs
        </CardTitle>
      </CardHeader>
      <CardContent className="space-y-6">
        {/* Existing FAQs */}
        {faqs.length > 0 && (
          <div className="space-y-4">
            {faqs.map((faq) => (
              <div key={faq.id} className="rounded-lg border p-4">
                {editingId === faq.id ? (
                  <div className="space-y-4">
                    <div className="space-y-2">
                      <Label>Question</Label>
                      <Input value={editQuestion} onChange={(e) => setEditQuestion(e.target.value)} />
                    </div>
                    <div className="space-y-2">
                      <Label>Answer</Label>
                      <Textarea
                        value={editAnswer}
                        onChange={(e) => setEditAnswer(e.target.value)}
                        className="min-h-[80px] resize-y"
                      />
                    </div>
                    <div className="flex gap-2">
                      <Button size="sm" onClick={() => handleUpdateFaq(faq.id)}>
                        Save
                      </Button>
                      <Button size="sm" variant="outline" onClick={() => setEditingId(null)}>
                        Cancel
                      </Button>
                    </div>
                  </div>
                ) : (
                  <div>
                    <div className="mb-2 flex items-start justify-between">
                      <div className="flex items-center gap-2">
                        <GripVertical className="text-muted-foreground h-4 w-4" />
                        <h4 className="font-medium">{faq.question}</h4>
                      </div>
                      <div className="flex gap-1">
                        <Button variant="ghost" size="icon" onClick={() => startEditing(faq)}>
                          <Edit className="h-4 w-4" />
                        </Button>
                        <AlertDialog>
                          <AlertDialogTrigger asChild>
                            <Button variant="ghost" size="icon">
                              <Trash className="h-4 w-4" />
                            </Button>
                          </AlertDialogTrigger>
                          <AlertDialogContent>
                            <AlertDialogHeader>
                              <AlertDialogTitle>Delete FAQ</AlertDialogTitle>
                              <AlertDialogDescription>
                                Are you sure you want to delete this FAQ? This action cannot be undone.
                              </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                              <AlertDialogCancel>Cancel</AlertDialogCancel>
                              <AlertDialogAction onClick={() => handleDeleteFaq(faq.id)}>Delete</AlertDialogAction>
                            </AlertDialogFooter>
                          </AlertDialogContent>
                        </AlertDialog>
                      </div>
                    </div>
                    <p className="text-muted-foreground pl-6 text-sm">{faq.answer}</p>
                  </div>
                )}
              </div>
            ))}
          </div>
        )}

        {/* Add new FAQ */}
        <div className="rounded-lg border border-dashed p-4">
          <h4 className="mb-4 font-medium">Add New FAQ</h4>
          <div className="space-y-4">
            <div className="space-y-2">
              <Label>Question</Label>
              <Input
                value={newQuestion}
                onChange={(e) => setNewQuestion(e.target.value)}
                placeholder="Enter a question..."
              />
            </div>
            <div className="space-y-2">
              <Label>Answer</Label>
              <Textarea
                value={newAnswer}
                onChange={(e) => setNewAnswer(e.target.value)}
                placeholder="Enter the answer..."
                className="min-h-[80px] resize-y"
              />
            </div>
            <Button onClick={handleAddFaq} disabled={!newQuestion.trim() || !newAnswer.trim()}>
              <Plus className="mr-2 h-4 w-4" />
              Add FAQ
            </Button>
          </div>
        </div>
      </CardContent>
    </Card>
  );
}
