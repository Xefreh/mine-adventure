import { Button } from '@/components/ui/button';
import type { BlockQuiz, BlockQuizQuestion } from '@/types';
import { CheckCircle2, Circle, HelpCircle, XCircle } from 'lucide-react';
import { useState } from 'react';

interface QuizBlockProps {
  quiz: BlockQuiz;
}

export function QuizBlock({ quiz }: QuizBlockProps) {
  const questions = quiz.questions || [];
  const [answers, setAnswers] = useState<Record<number, number>>({});
  const [submitted, setSubmitted] = useState(false);

  if (questions.length === 0) {
    return null;
  }

  const handleSelectAnswer = (questionId: number, optionIndex: number) => {
    if (submitted) return;
    setAnswers((prev) => ({ ...prev, [questionId]: optionIndex }));
  };

  const handleSubmit = () => {
    setSubmitted(true);
  };

  const handleReset = () => {
    setAnswers({});
    setSubmitted(false);
  };

  const correctCount = questions.filter((q) => answers[q.id] === q.correct_answer).length;
  const allAnswered = questions.every((q) => answers[q.id] !== undefined);

  return (
    <div className="rounded-lg border bg-card">
      <div className="border-b px-4 py-3">
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-2">
            <HelpCircle className="size-5 text-muted-foreground" />
            <h3 className="font-semibold">Quiz</h3>
          </div>
          {submitted && (
            <span className="text-sm font-medium">
              Score: {correctCount}/{questions.length}
            </span>
          )}
        </div>
      </div>
      <div className="divide-y">
        {questions.map((question, qIndex) => (
          <QuestionItem
            key={question.id}
            question={question}
            questionNumber={qIndex + 1}
            selectedAnswer={answers[question.id]}
            onSelectAnswer={(optionIndex) => handleSelectAnswer(question.id, optionIndex)}
            submitted={submitted}
          />
        ))}
      </div>
      <div className="border-t px-4 py-3">
        {submitted ? (
          <Button onClick={handleReset} variant="outline">
            Try Again
          </Button>
        ) : (
          <Button onClick={handleSubmit} disabled={!allAnswered}>
            Submit Answers
          </Button>
        )}
      </div>
    </div>
  );
}

interface QuestionItemProps {
  question: BlockQuizQuestion;
  questionNumber: number;
  selectedAnswer?: number;
  onSelectAnswer: (optionIndex: number) => void;
  submitted: boolean;
}

function QuestionItem({ question, questionNumber, selectedAnswer, onSelectAnswer, submitted }: QuestionItemProps) {
  return (
    <div className="p-4">
      <p className="mb-4 font-medium">
        {questionNumber}. {question.question}
      </p>
      <div className="space-y-2">
        {question.options.map((option, optionIndex) => {
          const isSelected = selectedAnswer === optionIndex;
          const isCorrect = optionIndex === question.correct_answer;
          const showResult = submitted;

          let bgColor = '';
          let Icon = Circle;

          if (showResult) {
            if (isCorrect) {
              bgColor = 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800';
              Icon = CheckCircle2;
            } else if (isSelected && !isCorrect) {
              bgColor = 'bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800';
              Icon = XCircle;
            }
          } else if (isSelected) {
            bgColor = 'bg-primary/10 border-primary';
          }

          return (
            <button
              key={optionIndex}
              onClick={() => onSelectAnswer(optionIndex)}
              disabled={submitted}
              className={`flex w-full items-center gap-3 rounded-lg border p-3 text-left transition-colors ${bgColor} ${
                !submitted && !isSelected ? 'hover:bg-muted' : ''
              } ${submitted ? 'cursor-default' : 'cursor-pointer'}`}
            >
              <Icon
                className={`size-5 shrink-0 ${
                  showResult && isCorrect
                    ? 'text-green-600'
                    : showResult && isSelected && !isCorrect
                      ? 'text-red-600'
                      : isSelected
                        ? 'text-primary'
                        : 'text-muted-foreground'
                }`}
              />
              <span>{option}</span>
            </button>
          );
        })}
      </div>
    </div>
  );
}
