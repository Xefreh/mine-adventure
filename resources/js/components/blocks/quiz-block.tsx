import { Button } from '@/components/ui/button';
import { Progress } from '@/components/ui/progress';
import type { BlockQuiz } from '@/types';
import { CheckCircle2, Circle, HelpCircle, RotateCcw, XCircle } from 'lucide-react';
import { useState } from 'react';

interface QuizBlockProps {
  quiz: BlockQuiz;
}

export function QuizBlock({ quiz }: QuizBlockProps) {
  const questions = quiz.questions || [];
  const [currentIndex, setCurrentIndex] = useState(0);
  const [answers, setAnswers] = useState<Record<number, number>>({});
  const [showResult, setShowResult] = useState(false);

  if (questions.length === 0) {
    return null;
  }

  const currentQuestion = questions[currentIndex];
  const isLastQuestion = currentIndex === questions.length - 1;
  const hasAnswered = answers[currentQuestion?.id] !== undefined;
  const correctCount = questions.filter((q) => answers[q.id] === q.correct_answer).length;
  const progress = ((currentIndex + (showResult ? 1 : 0)) / questions.length) * 100;

  const handleSelectAnswer = (optionIndex: number) => {
    if (hasAnswered) return;
    setAnswers((prev) => ({ ...prev, [currentQuestion.id]: optionIndex }));
  };

  const handleNext = () => {
    if (isLastQuestion) {
      setShowResult(true);
    } else {
      setCurrentIndex((prev) => prev + 1);
    }
  };

  const handleReset = () => {
    setAnswers({});
    setCurrentIndex(0);
    setShowResult(false);
  };

  return (
    <div className="rounded-lg border bg-card">
      <div className="border-b px-4 py-3">
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-2">
            <HelpCircle className="size-5 text-muted-foreground" />
            <h3 className="font-semibold">Quiz</h3>
          </div>
          {!showResult && (
            <span className="text-muted-foreground text-sm">
              {currentIndex + 1} / {questions.length}
            </span>
          )}
        </div>
        <Progress value={progress} className="mt-2 h-1" />
      </div>

      {showResult ? (
        <ResultsScreen
          questions={questions}
          answers={answers}
          correctCount={correctCount}
          onReset={handleReset}
        />
      ) : (
        <div className="p-4">
          <p className="mb-4 text-lg font-medium">{currentQuestion.question}</p>
          <div className="space-y-2">
            {currentQuestion.options.map((option, optionIndex) => {
              const isSelected = answers[currentQuestion.id] === optionIndex;

              return (
                <button
                  key={optionIndex}
                  onClick={() => handleSelectAnswer(optionIndex)}
                  className={`flex w-full items-center gap-3 rounded-lg border p-3 text-left transition-colors ${
                    isSelected ? 'bg-primary/10 border-primary' : 'hover:bg-muted'
                  }`}
                >
                  <Circle
                    className={`size-5 shrink-0 ${isSelected ? 'text-primary' : 'text-muted-foreground'}`}
                  />
                  <span>{option}</span>
                </button>
              );
            })}
          </div>

          {hasAnswered && (
            <div className="mt-4 flex justify-end">
              <Button onClick={handleNext}>
                {isLastQuestion ? 'See Results' : 'Next Question'}
              </Button>
            </div>
          )}
        </div>
      )}
    </div>
  );
}

interface ResultsScreenProps {
  questions: BlockQuiz['questions'];
  answers: Record<number, number>;
  correctCount: number;
  onReset: () => void;
}

function ResultsScreen({ questions, answers, correctCount, onReset }: ResultsScreenProps) {
  const percentage = Math.round((correctCount / questions.length) * 100);
  const isPassing = percentage >= 70;

  return (
    <div className="p-6">
      <div className="mb-6 text-center">
        <div
          className={`mx-auto mb-3 flex size-16 items-center justify-center rounded-full ${
            isPassing ? 'bg-green-100 dark:bg-green-900/30' : 'bg-orange-100 dark:bg-orange-900/30'
          }`}
        >
          {isPassing ? (
            <CheckCircle2 className="size-8 text-green-600" />
          ) : (
            <RotateCcw className="size-8 text-orange-600" />
          )}
        </div>
        <h3 className="text-xl font-semibold">
          {isPassing ? 'Great job!' : 'Keep practicing!'}
        </h3>
        <p className="text-muted-foreground mt-1">
          You got {correctCount} out of {questions.length} correct ({percentage}%)
        </p>
      </div>

      <div className="mb-6 space-y-3">
        {questions.map((question, index) => {
          const userAnswer = answers[question.id];
          const isCorrect = userAnswer === question.correct_answer;

          return (
            <div
              key={question.id}
              className={`rounded-lg border p-3 ${
                isCorrect
                  ? 'border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/20'
                  : 'border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20'
              }`}
            >
              <div className="flex items-start gap-2">
                {isCorrect ? (
                  <CheckCircle2 className="mt-0.5 size-4 shrink-0 text-green-600" />
                ) : (
                  <XCircle className="mt-0.5 size-4 shrink-0 text-red-600" />
                )}
                <div className="min-w-0 flex-1">
                  <p className="text-sm font-medium">
                    {index + 1}. {question.question}
                  </p>
                  {!isCorrect && (
                    <p className="text-muted-foreground mt-1 text-xs">
                      Correct answer: {question.options[question.correct_answer]}
                    </p>
                  )}
                </div>
              </div>
            </div>
          );
        })}
      </div>

      <div className="flex justify-center">
        <Button onClick={onReset} variant="outline">
          <RotateCcw className="mr-2 size-4" />
          Try Again
        </Button>
      </div>
    </div>
  );
}
