import type { Course } from '@/types';
import { create } from 'zustand';

interface CourseStore {
  courses: Course[];
  isLoaded: boolean;
  setCourses: (courses: Course[]) => void;
  addCourse: (course: Course) => void;
  updateCourse: (id: number, data: Partial<Course>) => void;
  removeCourse: (id: number) => void;
  reset: () => void;
}

export const useCourseStore = create<CourseStore>((set) => ({
  courses: [],
  isLoaded: false,

  setCourses: (courses) =>
    set({
      courses,
      isLoaded: true,
    }),

  addCourse: (course) =>
    set((state) => ({
      courses: [...state.courses, course],
    })),

  updateCourse: (id, data) =>
    set((state) => ({
      courses: state.courses.map((course) => (course.id === id ? { ...course, ...data } : course)),
    })),

  removeCourse: (id) =>
    set((state) => ({
      courses: state.courses.filter((course) => course.id !== id),
    })),

  reset: () =>
    set({
      courses: [],
      isLoaded: false,
    }),
}));
