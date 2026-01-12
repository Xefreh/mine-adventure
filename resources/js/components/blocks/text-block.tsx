import type { BlockText } from '@/types';
import ReactMarkdown from 'react-markdown';
import remarkGfm from 'remark-gfm';

interface TextBlockProps {
  text: BlockText;
}

export function TextBlock({ text }: TextBlockProps) {
  return (
    <div className="prose dark:prose-invert max-w-none">
      <ReactMarkdown remarkPlugins={[remarkGfm]}>{text.content}</ReactMarkdown>
    </div>
  );
}
