import { InertiaLinkProps } from '@inertiajs/react';
import { LucideIcon } from 'lucide-react';

export interface Auth {
  user: User;
}

export interface BreadcrumbItem {
  title: string;
  href: string;
}

export interface NavGroup {
  title: string;
  items: NavItem[];
}

export interface NavItem {
  title: string;
  href: NonNullable<InertiaLinkProps['href']>;
  icon?: LucideIcon | null;
  isActive?: boolean;
}

export interface SharedData {
  name: string;
  quote: { message: string; author: string };
  auth: Auth;
  sidebarOpen: boolean;
  [key: string]: unknown;
}

export interface User {
  id: number;
  name: string;
  email: string;
  avatar?: string;
  email_verified_at: string | null;
  is_admin: boolean;
  created_at: string;
  updated_at: string;
  [key: string]: unknown;
}

// LMS Types
export type CourseDifficulty = 'easy' | 'medium' | 'hard';
export type BlockType = 'video' | 'text' | 'resources' | 'assignment' | 'quiz';

export interface Course {
  id: number;
  name: string;
  thumbnail: string;
  description: string | null;
  difficulty: CourseDifficulty;
  chapters?: Chapter[];
  faqs?: CourseFaq[];
  chapters_count?: number;
  lessons_count?: number;
  created_at: string;
  updated_at: string;
}

export interface CourseFaq {
  id: number;
  course_id: number;
  question: string;
  answer: string;
  order: number;
  created_at: string;
  updated_at: string;
}

export interface Chapter {
  id: number;
  name: string;
  course_id: number;
  position: number;
  course?: Course;
  lessons?: Lesson[];
  lessons_count?: number;
  created_at: string;
  updated_at: string;
}

export interface Lesson {
  id: number;
  name: string;
  chapter_id: number;
  chapter?: Chapter;
  blocks?: LessonBlock[];
  blocks_count?: number;
  created_at: string;
  updated_at: string;
}

export interface LessonBlock {
  id: number;
  lesson_id: number;
  type: BlockType;
  position: number;
  video?: BlockVideo;
  text?: BlockText;
  resource?: BlockResource;
  assignment?: BlockAssignment;
  quiz?: BlockQuiz;
  created_at: string;
  updated_at: string;
}

export interface BlockVideo {
  id: number;
  block_id: number;
  url: string;
  duration: number | null;
}

export interface BlockText {
  id: number;
  block_id: number;
  content: string;
}

export interface BlockResource {
  id: number;
  block_id: number;
  links: ResourceLink[];
}

export interface ResourceLink {
  title: string;
  url: string;
}

export interface BlockAssignment {
  id: number;
  block_id: number;
  instructions: string;
  starter_code: string | null;
  solution?: string | null;
  language: string;
  test?: BlockAssignmentTest | null;
}

export interface BlockAssignmentTest {
  id: number;
  block_assignment_id: number;
  file_content: string;
  class_name: string;
}

export interface BlockQuiz {
  id: number;
  block_id: number;
  questions?: BlockQuizQuestion[];
}

export interface BlockQuizQuestion {
  id: number;
  block_quiz_id: number;
  question: string;
  options: string[];
  correct_answer: number;
  position: number;
}

// Dashboard Progress Types
export interface LessonProgress {
  id: number;
  name: string;
  isComplete: boolean;
  isCurrent: boolean;
  isLocked: boolean;
}

export interface ChapterProgress {
  id: number;
  name: string;
  totalLessons: number;
  lessonsCompleted: number;
  isComplete: boolean;
  isCurrent: boolean;
  isLocked: boolean;
  lessons: LessonProgress[];
}

export interface CurrentCourseProgress {
  course: Course;
  progressPercentage: number;
  completedLessons: number;
  totalLessons: number;
  chapters: ChapterProgress[];
}

export interface DashboardStats {
  totalCoursesStarted: number;
  totalCoursesCompleted: number;
  totalLessonsCompleted: number;
  currentStreak: number;
  lastActivityAt: string | null;
}
