import type { LessonBlock } from '@/types';
import { EditableQuizBlock } from './editable-quiz-block';
import { EditableResourcesBlock } from './editable-resources-block';
import { EditableAssignmentBlock } from './editable-assignment-block';
import { EditableTextBlock } from './editable-text-block';
import { EditableVideoBlock } from './editable-video-block';

interface EditableBlockRendererProps {
  block: LessonBlock;
  onSave: (blockId: number, data: Record<string, unknown>) => void;
  isDragging?: boolean;
}

export function EditableBlockRenderer({ block, onSave, isDragging }: EditableBlockRendererProps) {
  switch (block.type) {
    case 'video':
      return <EditableVideoBlock block={block} onSave={onSave} isDragging={isDragging} />;
    case 'text':
      return <EditableTextBlock block={block} onSave={onSave} />;
    case 'resources':
      return <EditableResourcesBlock block={block} onSave={onSave} />;
    case 'assignment':
      return <EditableAssignmentBlock block={block} onSave={onSave} />;
    case 'quiz':
      return <EditableQuizBlock block={block} onSave={onSave} />;
    default:
      return null;
  }
}
