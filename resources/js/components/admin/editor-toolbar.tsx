import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import type { BlockType } from '@/types';
import { router } from '@inertiajs/react';
import { ArrowLeft, FileText, Link2, ListChecks, Loader2, Plus, Save, Video } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';

interface EditorToolbarProps {
  lessonName: string;
  backHref: string;
  onAddBlock: (type: BlockType) => void;
  onSave: (name: string, shouldNavigate?: boolean) => void;
  isSaving?: boolean;
  hasPendingChanges?: boolean;
}

const blockTypes: { type: BlockType; label: string; icon: React.ReactNode }[] = [
  { type: 'video', label: 'Video', icon: <Video className="h-4 w-4" /> },
  { type: 'text', label: 'Text', icon: <FileText className="h-4 w-4" /> },
  { type: 'resources', label: 'Resources', icon: <Link2 className="h-4 w-4" /> },
  { type: 'assignment', label: 'Assignment', icon: <ListChecks className="h-4 w-4" /> },
  { type: 'quiz', label: 'Quiz', icon: <ListChecks className="h-4 w-4" /> },
];

export function EditorToolbar({ lessonName, backHref, onAddBlock, onSave, isSaving, hasPendingChanges }: EditorToolbarProps) {
  const [isEditing, setIsEditing] = useState(false);
  const [name, setName] = useState(lessonName);
  const [showUnsavedDialog, setShowUnsavedDialog] = useState(false);
  const inputRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
    setName(lessonName);
  }, [lessonName]);

  useEffect(() => {
    if (isEditing && inputRef.current) {
      inputRef.current.focus();
      inputRef.current.select();
    }
  }, [isEditing]);

  const handleTitleClick = () => {
    setIsEditing(true);
  };

  const handleBlur = () => {
    setIsEditing(false);
  };

  const handleKeyDown = (e: React.KeyboardEvent) => {
    if (e.key === 'Enter') {
      setIsEditing(false);
    } else if (e.key === 'Escape') {
      setName(lessonName);
      setIsEditing(false);
    }
  };

  const handleSave = () => {
    onSave(name);
  };

  const handleBackClick = () => {
    if (hasPendingChanges) {
      setShowUnsavedDialog(true);
    } else {
      router.visit(backHref);
    }
  };

  const handleSaveAndLeave = () => {
    setShowUnsavedDialog(false);
    onSave(name, true);
  };

  const handleLeaveWithoutSaving = () => {
    setShowUnsavedDialog(false);
    router.visit(backHref);
  };

  return (
    <div className="border-b bg-background">
      <div className="mx-auto flex h-14 max-w-7xl items-center justify-between px-4">
      <div className="flex items-center gap-2">
        <TooltipProvider delayDuration={0}>
          <Tooltip>
            <TooltipTrigger asChild>
              <Button variant="ghost" size="icon" onClick={handleBackClick}>
                <ArrowLeft className="h-4 w-4" />
              </Button>
            </TooltipTrigger>
            <TooltipContent>Back to lessons</TooltipContent>
          </Tooltip>
        </TooltipProvider>

        <Separator orientation="vertical" className="h-6" />

        <DropdownMenu>
          <TooltipProvider delayDuration={0}>
            <Tooltip>
              <TooltipTrigger asChild>
                <DropdownMenuTrigger asChild>
                  <Button variant="ghost" size="icon">
                    <Plus className="h-4 w-4" />
                  </Button>
                </DropdownMenuTrigger>
              </TooltipTrigger>
              <TooltipContent>Add block</TooltipContent>
            </Tooltip>
          </TooltipProvider>
          <DropdownMenuContent align="start">
            {blockTypes.map((block) => (
              <DropdownMenuItem key={block.type} onClick={() => onAddBlock(block.type)}>
                {block.icon}
                <span className="ml-2">{block.label}</span>
              </DropdownMenuItem>
            ))}
          </DropdownMenuContent>
        </DropdownMenu>
      </div>

      <div className="flex items-center">
        {isEditing ? (
          <Input
            ref={inputRef}
            value={name}
            onChange={(e) => setName(e.target.value)}
            onBlur={handleBlur}
            onKeyDown={handleKeyDown}
            className="h-8 w-64 text-center text-sm font-medium"
          />
        ) : (
          <button type="button" onClick={handleTitleClick} className="rounded px-2 py-1 text-sm font-medium hover:bg-muted">
            {name}
          </button>
        )}
      </div>

      <div className="flex items-center">
        <Button size="sm" onClick={handleSave} disabled={isSaving}>
          {isSaving ? (
            <>
              <Loader2 className="mr-2 h-4 w-4 animate-spin" />
              Saving...
            </>
          ) : (
            <>
              <Save className="mr-2 h-4 w-4" />
              Save
            </>
          )}
        </Button>
      </div>
      </div>

      <AlertDialog open={showUnsavedDialog} onOpenChange={setShowUnsavedDialog}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Unsaved changes</AlertDialogTitle>
            <AlertDialogDescription>
              You have unsaved changes. Would you like to save them before leaving?
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancel</AlertDialogCancel>
            <AlertDialogAction className="bg-red-600 text-white hover:bg-red-500" onClick={handleLeaveWithoutSaving}>
              No
            </AlertDialogAction>
            <AlertDialogAction onClick={handleSaveAndLeave}>Yes</AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
}
