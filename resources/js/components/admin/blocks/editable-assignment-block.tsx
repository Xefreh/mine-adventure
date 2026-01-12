import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import type { LessonBlock } from '@/types';
import MDEditor from '@uiw/react-md-editor';
import { Code } from 'lucide-react';
import { useEffect, useState } from 'react';

interface EditableAssignmentBlockProps {
  block: LessonBlock;
  onSave: (blockId: number, data: Record<string, unknown>) => void;
}

const SUPPORTED_LANGUAGES = [
  { value: 'php', label: 'PHP' },
  { value: 'javascript', label: 'JavaScript' },
  { value: 'typescript', label: 'TypeScript' },
  { value: 'python', label: 'Python' },
  { value: 'go', label: 'Go' },
  { value: 'rust', label: 'Rust' },
  { value: 'java', label: 'Java' },
  { value: 'csharp', label: 'C#' },
  { value: 'html', label: 'HTML' },
  { value: 'css', label: 'CSS' },
  { value: 'sql', label: 'SQL' },
  { value: 'json', label: 'JSON' },
  { value: 'markdown', label: 'Markdown' },
];

export function EditableAssignmentBlock({ block, onSave }: EditableAssignmentBlockProps) {
  const [instructions, setInstructions] = useState(block.assignment?.instructions ?? '');
  const [starterCode, setStarterCode] = useState(block.assignment?.starter_code ?? '');
  const [language, setLanguage] = useState(block.assignment?.language ?? 'php');
  const [isDark, setIsDark] = useState(false);

  useEffect(() => {
    const checkDarkMode = () => {
      setIsDark(document.documentElement.classList.contains('dark'));
    };
    checkDarkMode();

    const observer = new MutationObserver(checkDarkMode);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    return () => observer.disconnect();
  }, []);

  const saveData = (newData: Partial<{ instructions: string; starter_code: string; language: string }>) => {
    onSave(block.id, {
      instructions: newData.instructions ?? instructions,
      starter_code: newData.starter_code ?? starterCode,
      language: newData.language ?? language,
    });
  };

  const handleInstructionsBlur = () => {
    if (instructions !== block.assignment?.instructions) {
      saveData({ instructions });
    }
  };

  const handleStarterCodeBlur = () => {
    if (starterCode !== block.assignment?.starter_code) {
      saveData({ starter_code: starterCode });
    }
  };

  const handleLanguageChange = (value: string) => {
    setLanguage(value);
    saveData({ language: value });
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
          <Label>Language</Label>
          <Select value={language} onValueChange={handleLanguageChange}>
            <SelectTrigger className="w-[200px]">
              <SelectValue placeholder="Select language" />
            </SelectTrigger>
            <SelectContent>
              {SUPPORTED_LANGUAGES.map((lang) => (
                <SelectItem key={lang.value} value={lang.value}>
                  {lang.label}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>

        <div className="space-y-2">
          <Label>Instructions (Markdown)</Label>
          <div data-color-mode={isDark ? 'dark' : 'light'} onBlur={handleInstructionsBlur}>
            <MDEditor
              value={instructions}
              onChange={(val) => setInstructions(val || '')}
              preview="live"
              height={200}
            />
          </div>
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
