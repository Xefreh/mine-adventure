import { EditorToolbar } from '@/components/admin/editor-toolbar';
import { LessonPreview } from '@/components/admin/lesson-preview';
import { Container } from '@/components/ui/container';
import type { BlockType, Chapter, Lesson, LessonBlock } from '@/types';
import type { DragEndEvent } from '@dnd-kit/core';
import { arrayMove } from '@dnd-kit/sortable';
import { Head, router } from '@inertiajs/react';
import { useCallback, useRef, useState } from 'react';
import { toast } from 'sonner';

interface AdminLessonsEditProps {
  chapter: Chapter & { course: { id: number; name: string } };
  lesson: Lesson & { blocks: LessonBlock[] };
}

export default function AdminLessonsEdit({ chapter, lesson }: AdminLessonsEditProps) {
  const [blocks, setBlocks] = useState<LessonBlock[]>(lesson.blocks ?? []);
  const [newBlockIds, setNewBlockIds] = useState<Set<number>>(new Set());
  const [modifiedBlockIds, setModifiedBlockIds] = useState<Set<number>>(new Set());
  const [deletedBlockIds, setDeletedBlockIds] = useState<number[]>([]);
  const [isReordered, setIsReordered] = useState(false);
  const [isSaving, setIsSaving] = useState(false);
  const tempIdCounter = useRef(0);

  const hasPendingChanges =
    deletedBlockIds.length > 0 || newBlockIds.size > 0 || modifiedBlockIds.size > 0 || isReordered;

  const handleDragEnd = useCallback(
    (event: DragEndEvent) => {
      const { active, over } = event;

      if (over && active.id !== over.id) {
        const oldIndex = blocks.findIndex((b) => b.id === active.id);
        const newIndex = blocks.findIndex((b) => b.id === over.id);

        const newBlocks = arrayMove(blocks, oldIndex, newIndex).map((block, index) => ({
          ...block,
          position: index + 1,
        }));

        setBlocks(newBlocks);
        setIsReordered(true);
      }
    },
    [blocks],
  );

  const handleAddBlock = useCallback(
    (type: BlockType) => {
      tempIdCounter.current -= 1;
      const tempId = tempIdCounter.current;
      const position = blocks.length + 1;

      const newBlock: LessonBlock = {
        id: tempId,
        lesson_id: lesson.id,
        type,
        position,
        side: null,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
      };

      if (type === 'video') {
        newBlock.video = { id: tempId, block_id: tempId, url: '', duration: null };
      } else if (type === 'text') {
        newBlock.text = { id: tempId, block_id: tempId, content: '' };
      } else if (type === 'assignment') {
        newBlock.assignment = { id: tempId, block_id: tempId, instructions: '', starter_code: null };
      } else if (type === 'resources') {
        newBlock.resource = { id: tempId, block_id: tempId, links: [] };
      } else if (type === 'quiz') {
        newBlock.quiz = { id: tempId, block_id: tempId, questions: [] };
      }

      setBlocks((prev) => [...prev, newBlock]);
      setNewBlockIds((prev) => new Set(prev).add(tempId));
    },
    [blocks.length, lesson.id],
  );

  const handleSave = useCallback(
    async (name: string, shouldNavigate = false) => {
      setIsSaving(true);

      const handleError = (message: string) => {
        toast.error(message);
        setIsSaving(false);
      };

      const resetState = () => {
        setNewBlockIds(new Set());
        setModifiedBlockIds(new Set());
        setDeletedBlockIds([]);
        setIsReordered(false);
      };

      try {
        // 1. Delete blocks
        if (deletedBlockIds.length > 0) {
          await new Promise<void>((resolve, reject) => {
            router.post(
              `/admin/lessons/${lesson.id}/blocks/delete-multiple`,
              { block_ids: deletedBlockIds },
              {
                preserveScroll: true,
                onSuccess: () => resolve(),
                onError: () => reject(new Error('Failed to delete blocks')),
              },
            );
          });
        }

        // 2. Create new blocks
        const newBlocks = blocks.filter((b) => newBlockIds.has(b.id));
        for (const block of newBlocks) {
          const payload: Record<string, unknown> = {
            type: block.type,
            position: block.position,
          };

          if (block.type === 'video' && block.video) {
            payload.url = block.video.url;
            payload.duration = block.video.duration;
          } else if (block.type === 'text' && block.text) {
            payload.content = block.text.content;
          } else if (block.type === 'assignment' && block.assignment) {
            payload.instructions = block.assignment.instructions;
            payload.starter_code = block.assignment.starter_code;
          } else if (block.type === 'resources' && block.resource) {
            payload.links = block.resource.links;
          }

          await new Promise<void>((resolve, reject) => {
            router.post(`/admin/lessons/${lesson.id}/blocks`, payload as Parameters<typeof router.post>[1], {
              preserveScroll: true,
              onSuccess: () => resolve(),
              onError: () => reject(new Error('Failed to create block')),
            });
          });
        }

        // 3. Update modified blocks
        for (const blockId of modifiedBlockIds) {
          const block = blocks.find((b) => b.id === blockId);
          if (!block) continue;

          const data: Record<string, unknown> = {};

          if (block.type === 'video' && block.video) {
            data.url = block.video.url;
            data.duration = block.video.duration;
          } else if (block.type === 'text' && block.text) {
            data.content = block.text.content;
          } else if (block.type === 'assignment' && block.assignment) {
            data.instructions = block.assignment.instructions;
            data.starter_code = block.assignment.starter_code;
          } else if (block.type === 'resources' && block.resource) {
            data.links = block.resource.links;
          } else if (block.type === 'quiz' && block.quiz) {
            data.questions = block.quiz.questions;
          }

          await new Promise<void>((resolve, reject) => {
            router.patch(`/admin/lessons/${lesson.id}/blocks/${blockId}`, data as Parameters<typeof router.patch>[1], {
              preserveScroll: true,
              onSuccess: () => resolve(),
              onError: () => reject(new Error('Failed to update block')),
            });
          });
        }

        // 4. Reorder blocks (only existing blocks, not temp IDs)
        if (isReordered) {
          const existingBlocks = blocks.filter((b) => !newBlockIds.has(b.id));
          if (existingBlocks.length > 0) {
            await new Promise<void>((resolve, reject) => {
              router.post(
                `/admin/lessons/${lesson.id}/blocks/reorder`,
                { blocks: existingBlocks.map((b) => ({ id: b.id, position: b.position })) },
                {
                  preserveScroll: true,
                  onSuccess: () => resolve(),
                  onError: () => reject(new Error('Failed to reorder blocks')),
                },
              );
            });
          }
        }

        // 5. Save lesson name and refresh
        router.patch(
          `/admin/chapters/${chapter.id}/lessons/${lesson.id}`,
          { name },
          {
            preserveScroll: true,
            onSuccess: (page) => {
              const props = page.props as unknown as { lesson: Lesson & { blocks: LessonBlock[] } };
              if (props.lesson?.blocks) {
                setBlocks(props.lesson.blocks);
              }
              resetState();
              toast.success('Lesson saved successfully');

              if (shouldNavigate) {
                router.visit(`/admin/chapters/${chapter.id}/lessons`);
              }
            },
            onError: () => {
              handleError('Failed to save lesson');
            },
            onFinish: () => {
              setIsSaving(false);
            },
          },
        );
      } catch (error) {
        handleError(error instanceof Error ? error.message : 'Failed to save');
      }
    },
    [chapter.id, lesson.id, blocks, deletedBlockIds, newBlockIds, modifiedBlockIds, isReordered],
  );

  const handleSaveBlock = useCallback((blockId: number, data: Record<string, unknown>) => {
    setBlocks((prev) =>
      prev.map((block) => {
        if (block.id !== blockId) return block;

        const updatedBlock = { ...block };

        if (block.type === 'video' && block.video) {
          updatedBlock.video = { ...block.video, ...data };
        } else if (block.type === 'text' && block.text) {
          updatedBlock.text = { ...block.text, ...data };
        } else if (block.type === 'assignment' && block.assignment) {
          updatedBlock.assignment = { ...block.assignment, ...data };
        } else if (block.type === 'resources' && block.resource) {
          updatedBlock.resource = { ...block.resource, ...data };
        } else if (block.type === 'quiz' && block.quiz && data.questions) {
          updatedBlock.quiz = { ...block.quiz, questions: data.questions as typeof block.quiz.questions };
        }

        return updatedBlock;
      }),
    );

    if (!newBlockIds.has(blockId)) {
      setModifiedBlockIds((prev) => new Set(prev).add(blockId));
    }
  }, [newBlockIds]);

  const handleDeleteBlock = useCallback(
    (blockId: number) => {
      setBlocks((prev) => prev.filter((b) => b.id !== blockId));

      if (newBlockIds.has(blockId)) {
        setNewBlockIds((prev) => {
          const next = new Set(prev);
          next.delete(blockId);
          return next;
        });
      } else {
        setDeletedBlockIds((prev) => [...prev, blockId]);
        setModifiedBlockIds((prev) => {
          const next = new Set(prev);
          next.delete(blockId);
          return next;
        });
      }
    },
    [newBlockIds],
  );

  return (
    <>
      <Head title={`Edit ${lesson.name}`} />

      <div className="flex h-screen flex-col">
        <EditorToolbar
          lessonName={lesson.name}
          backHref={`/admin/chapters/${chapter.id}/lessons`}
          onAddBlock={handleAddBlock}
          onSave={handleSave}
          isSaving={isSaving}
          hasPendingChanges={hasPendingChanges}
        />

        <Container className="flex-1 overflow-y-auto">
          <div className="mx-auto max-w-7xl px-4 py-4">
            <LessonPreview blocks={blocks} onSave={handleSaveBlock} onDelete={handleDeleteBlock} onReorder={handleDragEnd} />
          </div>
        </Container>
      </div>
    </>
  );
}
