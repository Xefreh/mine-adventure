import { Link } from '@inertiajs/react';
import { legal, privacy } from '@/routes';

export function AppFooter() {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="border-t bg-background">
      <div className="mx-auto max-w-7xl px-4 py-6">
        <div className="flex flex-col items-center justify-between gap-4 md:flex-row">
          <p className="text-muted-foreground text-sm">
            &copy; {currentYear} Mine Adventure. All rights reserved.
          </p>
          <nav className="flex gap-6">
            <Link href="/courses" className="text-muted-foreground hover:text-foreground text-sm transition-colors">
              Courses
            </Link>
            <Link href={legal()} className="text-muted-foreground hover:text-foreground text-sm transition-colors">
              Legal
            </Link>
            <Link href={privacy()} className="text-muted-foreground hover:text-foreground text-sm transition-colors">
              Privacy
            </Link>
          </nav>
        </div>
      </div>
    </footer>
  );
}
