import type { BlockAssignment } from '@/types';
import { Code2 } from 'lucide-react';

interface AssignmentBlockProps {
  assignment: BlockAssignment;
}

export function AssignmentBlock({ assignment }: AssignmentBlockProps) {
  return (
    <div className="rounded-lg border bg-card">
      <div className="border-b px-4 py-3">
        <div className="flex items-center gap-2">
          <Code2 className="size-5 text-muted-foreground" />
          <h3 className="font-semibold">Assignment</h3>
        </div>
      </div>
      <div className="p-4">
        <div className="prose dark:prose-invert max-w-none">
          <div dangerouslySetInnerHTML={{ __html: assignment.instructions }} />
        </div>
      </div>
    </div>
  );
}
