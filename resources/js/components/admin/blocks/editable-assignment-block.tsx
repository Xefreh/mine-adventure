import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import type { LessonBlock } from '@/types';
import MDEditor from '@uiw/react-md-editor';
import { ChevronDown, Code, FlaskConical } from 'lucide-react';
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
  const [testClassName, setTestClassName] = useState(block.assignment?.test?.class_name ?? '');
  const [testFileContent, setTestFileContent] = useState(block.assignment?.test?.file_content ?? '');
  const [isTestOpen, setIsTestOpen] = useState(!!block.assignment?.test);
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

  const saveData = (
    newData: Partial<{
      instructions: string;
      starter_code: string;
      language: string;
      test_class_name: string;
      test_file_content: string;
    }>,
  ) => {
    onSave(block.id, {
      instructions: newData.instructions ?? instructions,
      starter_code: newData.starter_code ?? starterCode,
      language: newData.language ?? language,
      test_class_name: newData.test_class_name ?? testClassName,
      test_file_content: newData.test_file_content ?? testFileContent,
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

  const handleTestClassNameBlur = () => {
    if (testClassName !== block.assignment?.test?.class_name) {
      saveData({ test_class_name: testClassName });
    }
  };

  const handleTestFileContentBlur = () => {
    if (testFileContent !== block.assignment?.test?.file_content) {
      saveData({ test_file_content: testFileContent });
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

        <Collapsible open={isTestOpen} onOpenChange={setIsTestOpen} className="pt-4 border-t">
          <CollapsibleTrigger className="flex w-full items-center justify-between py-2 text-sm font-medium hover:underline">
            <span className="flex items-center gap-2">
              <FlaskConical className="h-4 w-4" />
              Test Configuration
            </span>
            <ChevronDown className={`h-4 w-4 transition-transform ${isTestOpen ? 'rotate-180' : ''}`} />
          </CollapsibleTrigger>
          <CollapsibleContent className="space-y-4 pt-4">
            <div className="space-y-2">
              <Label>Test Class Name</Label>
              <Input
                value={testClassName}
                onChange={(e) => setTestClassName(e.target.value)}
                onBlur={handleTestClassNameBlur}
                placeholder="e.g., HelloWorldTest"
                className="font-mono text-sm"
              />
            </div>
            <div className="space-y-2">
              <Label>Test File Content (PHPUnit)</Label>
              <Textarea
                value={testFileContent}
                onChange={(e) => setTestFileContent(e.target.value)}
                onBlur={handleTestFileContentBlur}
                placeholder={`<?php\n\nuse PHPUnit\\Framework\\TestCase;\n\nclass HelloWorldTest extends TestCase\n{\n    public function test_outputs_hello_world(): void\n    {\n        ob_start();\n        include __DIR__ . '/../solution.php';\n        $output = ob_get_clean();\n\n        $this->assertEquals('Hello, World!', trim($output));\n    }\n}`}
                className="min-h-[250px] resize-y font-mono text-sm"
              />
            </div>
          </CollapsibleContent>
        </Collapsible>
      </CardContent>
    </Card>
  );
}
