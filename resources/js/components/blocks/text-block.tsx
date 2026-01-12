import type { BlockText } from '@/types';

interface TextBlockProps {
  text: BlockText;
}

export function TextBlock({ text }: TextBlockProps) {
  return (
    <div className="prose dark:prose-invert max-w-none">
      <div dangerouslySetInnerHTML={{ __html: text.content }} />
    </div>
  );
}
