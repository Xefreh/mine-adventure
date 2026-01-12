import { AppContent } from '@/components/app-content';
import { AppFooter } from '@/components/app-footer';
import { AppHeader } from '@/components/app-header';
import { AppShell } from '@/components/app-shell';
import { type BreadcrumbItem } from '@/types';
import type { PropsWithChildren } from 'react';

interface AppHeaderLayoutProps {
  breadcrumbs?: BreadcrumbItem[];
  fullWidth?: boolean;
}

export default function AppHeaderLayout({ children, breadcrumbs, fullWidth = false }: PropsWithChildren<AppHeaderLayoutProps>) {
  return (
    <AppShell>
      <AppHeader breadcrumbs={breadcrumbs} fullWidth={fullWidth} />
      <AppContent fullWidth={fullWidth}>{children}</AppContent>
      <AppFooter />
    </AppShell>
  );
}
