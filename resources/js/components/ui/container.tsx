import { cn } from '@/lib/utils';
import { forwardRef } from 'react';

interface ContainerProps extends React.HTMLAttributes<HTMLDivElement> {
  children?: React.ReactNode;
}

export const Container = forwardRef<HTMLDivElement, ContainerProps>(({ children, className, ...props }, ref) => {
  return (
    <div
      ref={ref}
      className={cn(
        'relative',
        '[background-image:radial-gradient(circle,hsl(var(--muted-foreground)/0.2)_1px,transparent_1px)]',
        '[background-size:24px_24px]',
        className
      )}
      {...props}
    >
      {children}
    </div>
  );
});

Container.displayName = 'Container';
