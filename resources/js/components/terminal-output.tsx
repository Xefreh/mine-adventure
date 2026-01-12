import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { Loader2, Terminal, X } from 'lucide-react';
import { useEffect, useRef } from 'react';

interface TerminalOutputProps {
  output: string;
  isRunning: boolean;
  error?: string | null;
  onClear?: () => void;
  className?: string;
}

export function TerminalOutput({ output, isRunning, error, onClear, className }: TerminalOutputProps) {
  const contentRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (contentRef.current) {
      contentRef.current.scrollTop = contentRef.current.scrollHeight;
    }
  }, [output, error]);

  const hasContent = output || error || isRunning;

  return (
    <div className={cn('overflow-hidden rounded-b-lg', className)}>
      <div className="flex items-center justify-between border-t bg-zinc-800 px-4 py-2">
        <div className="flex items-center gap-2">
          <Terminal className="size-4 text-zinc-400" />
          <span className="text-sm font-medium text-zinc-200">Output</span>
        </div>
        {hasContent && onClear && (
          <Button onClick={onClear} size="sm" variant="ghost" className="h-6 px-2 text-zinc-400 hover:text-zinc-200">
            <X className="size-3" />
            Clear
          </Button>
        )}
      </div>

      <div
        ref={contentRef}
        className="max-h-48 min-h-24 overflow-y-auto bg-zinc-900 p-4 font-mono text-sm text-zinc-100"
      >
        {isRunning && (
          <div className="flex items-center gap-2 text-zinc-400">
            <Loader2 className="size-4 animate-spin" />
            <span>Running...</span>
          </div>
        )}

        {error && !isRunning && <pre className="whitespace-pre-wrap text-red-400">{error}</pre>}

        {output && !isRunning && !error && <pre className="whitespace-pre-wrap">{output}</pre>}

        {!hasContent && <span className="text-zinc-500">Run your code to see output here</span>}
      </div>
    </div>
  );
}
