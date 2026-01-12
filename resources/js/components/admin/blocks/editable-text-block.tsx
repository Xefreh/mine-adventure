import { Card, CardContent } from '@/components/ui/card';
import type { LessonBlock } from '@/types';
import MDEditor from '@uiw/react-md-editor';
import { useEffect, useRef, useState } from 'react';

interface EditableTextBlockProps {
  block: LessonBlock;
  onSave: (blockId: number, data: Record<string, unknown>) => void;
}

export function EditableTextBlock({ block, onSave }: EditableTextBlockProps) {
  const [content, setContent] = useState(block.text?.content ?? '');
  const [isDark, setIsDark] = useState(false);
  const containerRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const checkDarkMode = () => {
      setIsDark(document.documentElement.classList.contains('dark'));
    };
    checkDarkMode();

    const observer = new MutationObserver(checkDarkMode);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    return () => observer.disconnect();
  }, []);

  const handleBlur = () => {
    if (content !== block.text?.content) {
      onSave(block.id, { content });
    }
  };

  return (
    <Card>
      <CardContent className="p-4">
        <div ref={containerRef} data-color-mode={isDark ? 'dark' : 'light'} onBlur={handleBlur}>
          <MDEditor
            value={content}
            onChange={(val) => setContent(val || '')}
            preview="live"
            height={300}
          />
        </div>
      </CardContent>
    </Card>
  );
}
