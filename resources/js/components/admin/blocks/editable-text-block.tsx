import { Card, CardContent } from '@/components/ui/card';
import { Textarea } from '@/components/ui/textarea';
import type { LessonBlock } from '@/types';
import { useState } from 'react';

interface EditableTextBlockProps {
  block: LessonBlock;
  onSave: (blockId: number, data: Record<string, unknown>) => void;
}

export function EditableTextBlock({ block, onSave }: EditableTextBlockProps) {
  const [content, setContent] = useState(block.text?.content ?? '');

  const handleBlur = () => {
    if (content !== block.text?.content) {
      onSave(block.id, { content });
    }
  };

  return (
    <Card>
      <CardContent className="p-4">
        <Textarea
          value={content}
          onChange={(e) => setContent(e.target.value)}
          onBlur={handleBlur}
          placeholder="Enter your text content..."
          className="min-h-[120px] resize-y border-none bg-transparent p-0 text-base focus-visible:ring-0"
        />
      </CardContent>
    </Card>
  );
}
