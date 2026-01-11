import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import type { LessonBlock } from '@/types';
import { ChevronDown, ChevronRight, Eye, HelpCircle, Pencil, Plus, Trash2 } from 'lucide-react';
import { useState } from 'react';

interface EditableQuizBlockProps {
  block: LessonBlock;
  onSave: (blockId: number, data: Record<string, unknown>) => void;
}

interface EditableQuestion {
  id?: number;
  question: string;
  options: string[];
  correct_answer: number;
  position: number;
}

export function EditableQuizBlock({ block, onSave }: EditableQuizBlockProps) {
  const [isEditing, setIsEditing] = useState(false);
  const [expandedIndex, setExpandedIndex] = useState<number | null>(null);
  const [questions, setQuestions] = useState<EditableQuestion[]>(
    block.quiz?.questions?.map((q) => ({
      id: q.id,
      question: q.question,
      options: [...q.options],
      correct_answer: q.correct_answer,
      position: q.position,
    })) ?? [],
  );

  const handleSave = () => {
    onSave(block.id, { questions });
    setIsEditing(false);
    setExpandedIndex(null);
  };

  const handleToggleEdit = () => {
    if (isEditing) {
      handleSave();
    } else {
      setQuestions(
        block.quiz?.questions?.map((q) => ({
          id: q.id,
          question: q.question,
          options: [...q.options],
          correct_answer: q.correct_answer,
          position: q.position,
        })) ?? [],
      );
      setIsEditing(true);
      setExpandedIndex(null);
    }
  };

  const handleAddQuestion = () => {
    const newIndex = questions.length;
    setQuestions([
      ...questions,
      {
        question: '',
        options: ['', '', '', ''],
        correct_answer: 0,
        position: newIndex + 1,
      },
    ]);
    setExpandedIndex(newIndex);
  };

  const handleRemoveQuestion = (index: number) => {
    setQuestions(questions.filter((_, i) => i !== index).map((q, i) => ({ ...q, position: i + 1 })));
    if (expandedIndex === index) {
      setExpandedIndex(null);
    } else if (expandedIndex !== null && expandedIndex > index) {
      setExpandedIndex(expandedIndex - 1);
    }
  };

  const handleQuestionChange = (index: number, value: string) => {
    setQuestions(questions.map((q, i) => (i === index ? { ...q, question: value } : q)));
  };

  const handleOptionChange = (qIndex: number, oIndex: number, value: string) => {
    setQuestions(questions.map((q, i) => (i === qIndex ? { ...q, options: q.options.map((o, j) => (j === oIndex ? value : o)) } : q)));
  };

  const handleCorrectAnswerChange = (qIndex: number, value: string) => {
    setQuestions(questions.map((q, i) => (i === qIndex ? { ...q, correct_answer: parseInt(value, 10) } : q)));
  };

  const toggleExpanded = (index: number) => {
    setExpandedIndex(expandedIndex === index ? null : index);
  };

  const originalQuestions = block.quiz?.questions ?? [];

  return (
    <Card>
      <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
        <CardTitle className="flex items-center gap-2 text-lg">
          <HelpCircle className="h-5 w-5" />
          Quiz
        </CardTitle>
        <Button variant="ghost" size="icon" onClick={handleToggleEdit}>
          {isEditing ? <Eye className="h-4 w-4" /> : <Pencil className="h-4 w-4" />}
        </Button>
      </CardHeader>
      <CardContent>
        {isEditing ? (
          <div className="space-y-3">
            {questions.map((question, qIndex) => {
              const isExpanded = expandedIndex === qIndex;

              return (
                <div key={qIndex} className="rounded-lg border">
                  <button
                    type="button"
                    className="flex w-full items-center justify-between p-3 text-left hover:bg-muted/50"
                    onClick={() => toggleExpanded(qIndex)}
                  >
                    <div className="flex items-center gap-2">
                      {isExpanded ? <ChevronDown className="h-4 w-4" /> : <ChevronRight className="h-4 w-4" />}
                      <span className="font-medium">Question {qIndex + 1}</span>
                      {question.question && <span className="text-sm text-muted-foreground">- {question.question.slice(0, 40)}{question.question.length > 40 ? '...' : ''}</span>}
                    </div>
                    <Button
                      variant="ghost"
                      size="icon"
                      className="h-8 w-8"
                      onClick={(e) => {
                        e.stopPropagation();
                        handleRemoveQuestion(qIndex);
                      }}
                    >
                      <Trash2 className="h-4 w-4 text-destructive" />
                    </Button>
                  </button>

                  {isExpanded && (
                    <div className="space-y-4 border-t p-4">
                      <div className="space-y-2">
                        <Label>Question</Label>
                        <Input value={question.question} onChange={(e) => handleQuestionChange(qIndex, e.target.value)} placeholder="Enter question..." />
                      </div>

                      <div className="space-y-2">
                        <Label>Options (select correct answer)</Label>
                        <RadioGroup value={String(question.correct_answer)} onValueChange={(v) => handleCorrectAnswerChange(qIndex, v)}>
                          {question.options.map((option, oIndex) => (
                            <div key={oIndex} className="flex items-center gap-2">
                              <RadioGroupItem value={String(oIndex)} id={`q${qIndex}-o${oIndex}`} />
                              <Input
                                value={option}
                                onChange={(e) => handleOptionChange(qIndex, oIndex, e.target.value)}
                                placeholder={`Option ${oIndex + 1}`}
                                className="flex-1"
                              />
                            </div>
                          ))}
                        </RadioGroup>
                      </div>
                    </div>
                  )}
                </div>
              );
            })}

            <Button variant="outline" size="sm" onClick={handleAddQuestion}>
              <Plus className="mr-2 h-4 w-4" />
              Add Question
            </Button>
          </div>
        ) : (
          <div className="space-y-4">
            {originalQuestions.length === 0 ? (
              <p className="text-sm text-muted-foreground">No questions yet. Click the edit button to add questions.</p>
            ) : (
              originalQuestions.map((question, qIndex) => (
                <div key={question.id} className="space-y-2">
                  <p className="font-medium">
                    {qIndex + 1}. {question.question}
                  </p>
                  <ul className="space-y-1 pl-4">
                    {question.options.map((option, oIndex) => (
                      <li key={oIndex} className={`text-sm ${oIndex === question.correct_answer ? 'font-medium text-green-600 dark:text-green-400' : 'text-muted-foreground'}`}>
                        {oIndex === question.correct_answer ? '✓ ' : '○ '}
                        {option}
                      </li>
                    ))}
                  </ul>
                </div>
              ))
            )}
          </div>
        )}
      </CardContent>
    </Card>
  );
}
