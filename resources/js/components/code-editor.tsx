import { useAppearance } from '@/hooks/use-appearance';
import Editor from '@monaco-editor/react';
import type { editor } from 'monaco-editor';
import { useEffect, useState } from 'react'; // useState used by useResolvedTheme

interface CodeEditorProps {
  language: string;
  value?: string;
  onChange?: (value: string) => void;
  readOnly?: boolean;
  height?: string;
}

function useResolvedTheme() {
  const { appearance } = useAppearance();
  const [isDark, setIsDark] = useState(false);

  useEffect(() => {
    const checkDark = () => {
      if (appearance === 'dark') {
        return true;
      }
      if (appearance === 'light') {
        return false;
      }
      // appearance === 'system'
      return window.matchMedia('(prefers-color-scheme: dark)').matches;
    };

    setIsDark(checkDark());

    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    const handleChange = () => {
      if (appearance === 'system') {
        setIsDark(mediaQuery.matches);
      }
    };

    mediaQuery.addEventListener('change', handleChange);
    return () => mediaQuery.removeEventListener('change', handleChange);
  }, [appearance]);

  return isDark;
}

export function CodeEditor({ language, value = '', onChange, readOnly = false, height = '400px' }: CodeEditorProps) {
  const isDark = useResolvedTheme();

  const handleChange = (newValue: string | undefined) => {
    onChange?.(newValue ?? '');
  };

  const editorOptions: editor.IStandaloneEditorConstructionOptions = {
    minimap: { enabled: false },
    fontSize: 14,
    fontFamily: "'JetBrains Mono', monospace",
    fontLigatures: true,
    lineNumbers: 'on',
    readOnly,
    scrollBeyondLastLine: false,
    wordWrap: 'on',
    automaticLayout: true,
    tabSize: 4,
    insertSpaces: true,
    padding: { top: 16, bottom: 16 },
  };

  return (
    <Editor
      height={height}
      language={language}
      value={value}
      theme={isDark ? 'vs-dark' : 'light'}
      onChange={handleChange}
      options={editorOptions}
      loading={<div className="flex h-full items-center justify-center">Loading editor...</div>}
    />
  );
}
