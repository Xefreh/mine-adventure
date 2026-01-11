import type { LessonBlock } from '@/types';
import { closestCorners, DndContext, type DragEndEvent, DragOverlay, type DragStartEvent, KeyboardSensor, PointerSensor, useSensor, useSensors } from '@dnd-kit/core';
import { restrictToVerticalAxis } from '@dnd-kit/modifiers';
import { SortableContext, sortableKeyboardCoordinates, useSortable, verticalListSortingStrategy } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { GripVertical, Trash2 } from 'lucide-react';
import { useState } from 'react';
import { EditableBlockRenderer } from './blocks/editable-block-renderer';

interface LessonPreviewProps {
  blocks: LessonBlock[];
  onSave: (blockId: number, data: Record<string, unknown>) => void;
  onDelete: (blockId: number) => void;
  onReorder?: (event: DragEndEvent) => void;
}

function SortableBlock({
  block,
  onSave,
  onDelete,
  isAnyDragging,
}: {
  block: LessonBlock;
  onSave: (blockId: number, data: Record<string, unknown>) => void;
  onDelete: (blockId: number) => void;
  isAnyDragging: boolean;
}) {
  const { attributes, listeners, setNodeRef, transform, transition, isDragging } = useSortable({ id: block.id });

  const style = {
    transform: CSS.Translate.toString(transform),
    transition,
  };

  return (
    <div ref={setNodeRef} style={style} className={isDragging ? 'opacity-40' : 'group relative'}>
      {!isDragging && (
        <div className="absolute -left-10 top-2 flex flex-col gap-1 opacity-0 transition-opacity group-hover:opacity-100">
          <button type="button" className="cursor-grab touch-none rounded p-1 text-muted-foreground hover:bg-muted hover:text-foreground" {...attributes} {...listeners}>
            <GripVertical className="h-4 w-4" />
          </button>
          <button type="button" className="rounded p-1 text-muted-foreground hover:bg-destructive/10 hover:text-destructive" onClick={() => onDelete(block.id)}>
            <Trash2 className="h-4 w-4" />
          </button>
        </div>
      )}
      <div className={isDragging ? 'pointer-events-none rounded-lg border-2 border-dashed border-primary/50' : ''}>
        <EditableBlockRenderer block={block} onSave={onSave} isDragging={isAnyDragging} />
      </div>
    </div>
  );
}

function DragOverlayBlock({ block }: { block: LessonBlock }) {
  return (
    <div className="relative rounded-lg bg-background shadow-lg ring-2 ring-primary/20">
      <div className="absolute -left-10 top-2 flex flex-col gap-1">
        <div className="cursor-grabbing rounded p-1 text-muted-foreground">
          <GripVertical className="h-4 w-4" />
        </div>
      </div>
      <EditableBlockRenderer block={block} onSave={() => {}} />
    </div>
  );
}

function BlockList({
  blocks,
  onSave,
  onDelete,
  onReorder,
}: {
  blocks: LessonBlock[];
  onSave: (blockId: number, data: Record<string, unknown>) => void;
  onDelete: (blockId: number) => void;
  onReorder?: (event: DragEndEvent) => void;
}) {
  const [activeBlock, setActiveBlock] = useState<LessonBlock | null>(null);

  const sensors = useSensors(
    useSensor(PointerSensor, {
      activationConstraint: {
        distance: 8,
      },
    }),
    useSensor(KeyboardSensor, {
      coordinateGetter: sortableKeyboardCoordinates,
    }),
  );

  const handleDragStart = (event: DragStartEvent) => {
    const block = blocks.find((b) => b.id === event.active.id);
    setActiveBlock(block ?? null);
  };

  const handleDragEnd = (event: DragEndEvent) => {
    setActiveBlock(null);
    onReorder?.(event);
  };

  return (
    <DndContext
      sensors={sensors}
      collisionDetection={closestCorners}
      modifiers={[restrictToVerticalAxis]}
      onDragStart={handleDragStart}
      onDragEnd={handleDragEnd}
    >
      <SortableContext items={blocks.map((b) => b.id)} strategy={verticalListSortingStrategy}>
        <div className="space-y-6 pl-10">
          {blocks.map((block) => (
            <SortableBlock key={block.id} block={block} onSave={onSave} onDelete={onDelete} isAnyDragging={activeBlock !== null} />
          ))}
        </div>
      </SortableContext>
      <DragOverlay>{activeBlock && <DragOverlayBlock block={activeBlock} />}</DragOverlay>
    </DndContext>
  );
}

export function LessonPreview({ blocks, onSave, onDelete, onReorder }: LessonPreviewProps) {
  const sortedBlocks = [...blocks].sort((a, b) => a.position - b.position);

  if (sortedBlocks.length === 0) {
    return (
      <div className="flex h-64 items-center justify-center text-muted-foreground">
        <p>No blocks yet. Add your first block using the toolbar above.</p>
      </div>
    );
  }

  return <BlockList blocks={sortedBlocks} onSave={onSave} onDelete={onDelete} onReorder={onReorder} />;
}
