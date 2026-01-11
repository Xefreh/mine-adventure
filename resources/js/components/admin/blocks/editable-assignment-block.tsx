import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import type { LessonBlock } from '@/types';
import { Code } from 'lucide-react';
import { useState } from 'react';

interface EditableAssignmentBlockProps {
  block: LessonBlock;
  onSave: (blockId: number, data: Record<string, unknown>) => void;
}

export function EditableAssignmentBlock({ block, onSave }: EditableAssignmentBlockProps) {
  const [instructions, setInstructions] = useState(block.assignment?.instructions ?? '');
  const [starterCode, setStarterCode] = useState(block.assignment?.starter_code ?? '');

  const handleInstructionsBlur = () => {
    if (instructions !== block.assignment?.instructions) {
      onSave(block.id, { instructions, starter_code: starterCode });
    }
  };

  const handleStarterCodeBlur = () => {
    if (starterCode !== block.assignment?.starter_code) {
      onSave(block.id, { instructions, starter_code: starterCode });
    }
  };

  return (
    <Card>
      <CardHeader>
        <CardTitle className="flex items-center gap-2 text-lg">
          <Code className="h-5 w-5" />
          Assignment
        </CardTitle>
      </CardHeader>
      <CardContent className="space-y-4">
        <div className="space-y-2">
          <Label>Instructions</Label>
          <Textarea
            value={instructions}
            onChange={(e) => setInstructions(e.target.value)}
            onBlur={handleInstructionsBlur}
            placeholder="Enter assignment instructions..."
            className="min-h-[100px] resize-y"
          />
        </div>

        <div className="space-y-2">
          <Label>Starter Code</Label>
          <Textarea
            value={starterCode}
            onChange={(e) => setStarterCode(e.target.value)}
            onBlur={handleStarterCodeBlur}
            placeholder="Enter starter code (optional)..."
            className="min-h-[100px] resize-y font-mono text-sm"
          />
        </div>
      </CardContent>
    </Card>
  );
}
