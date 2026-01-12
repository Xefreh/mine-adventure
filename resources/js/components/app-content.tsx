import { SidebarInset } from '@/components/ui/sidebar';
import * as React from 'react';

interface AppContentProps extends React.ComponentProps<'main'> {
  variant?: 'header' | 'sidebar';
  fullWidth?: boolean;
}

export function AppContent({ variant = 'header', fullWidth = false, children, ...props }: AppContentProps) {
  if (variant === 'sidebar') {
    return <SidebarInset {...props}>{children}</SidebarInset>;
  }

  return (
    <main
      className={`flex h-full w-full flex-1 flex-col gap-4 ${fullWidth ? '' : 'mx-auto max-w-7xl px-4'}`}
      {...props}
    >
      {children}
    </main>
  );
}
