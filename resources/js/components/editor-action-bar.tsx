import { Button } from '@/components/ui/button';
import { Eye, EyeOff, Loader2, Play, Send } from 'lucide-react';

interface EditorActionBarProps {
  onRun: () => void;
  onSubmit: () => void;
  onRevealSolution: () => void;
  isRunning: boolean;
  isSubmitting: boolean;
  solutionRevealed: boolean;
  hasSolution: boolean;
}

export function EditorActionBar({
  onRun,
  onSubmit,
  onRevealSolution,
  isRunning,
  isSubmitting,
  solutionRevealed,
  hasSolution,
}: EditorActionBarProps) {
  const isDisabled = isRunning || isSubmitting;

  return (
    <div className="flex items-center justify-between gap-2 border-t bg-muted/30 px-4 py-3">
      <div className="flex gap-2">
        <Button onClick={onRun} disabled={isDisabled} size="sm" variant="secondary">
          {isRunning ? <Loader2 className="size-4 animate-spin" /> : <Play className="size-4" />}
          Run
        </Button>
        <Button onClick={onSubmit} disabled={isDisabled} size="sm">
          {isSubmitting ? <Loader2 className="size-4 animate-spin" /> : <Send className="size-4" />}
          Submit
        </Button>
      </div>

      {hasSolution && (
        <Button onClick={onRevealSolution} disabled={isDisabled} size="sm" variant="ghost">
          {solutionRevealed ? <EyeOff className="size-4" /> : <Eye className="size-4" />}
          {solutionRevealed ? 'Hide Solution' : 'Reveal Solution'}
        </Button>
      )}
    </div>
  );
}
