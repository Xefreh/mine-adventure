import type { LessonBlock } from '@/types';

import { AssignmentBlock } from './assignment-block';
import { QuizBlock } from './quiz-block';
import { ResourceBlock } from './resource-block';
import { TextBlock } from './text-block';
import { VideoBlock } from './video-block';

interface BlockRendererProps {
  block: LessonBlock;
}

export function BlockRenderer({ block }: BlockRendererProps) {
  switch (block.type) {
    case 'video':
      return block.video ? <VideoBlock video={block.video} /> : null;
    case 'text':
      return block.text ? <TextBlock text={block.text} /> : null;
    case 'resources':
      return block.resource ? <ResourceBlock resource={block.resource} /> : null;
    case 'assignment':
      return block.assignment ? <AssignmentBlock assignment={block.assignment} /> : null;
    case 'quiz':
      return block.quiz ? <QuizBlock quiz={block.quiz} /> : null;
    default:
      return null;
  }
}
