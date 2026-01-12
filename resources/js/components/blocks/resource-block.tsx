import type { BlockResource } from '@/types';
import { ExternalLink, FileText } from 'lucide-react';

interface ResourceBlockProps {
  resource: BlockResource;
}

export function ResourceBlock({ resource }: ResourceBlockProps) {
  if (!resource.links || resource.links.length === 0) {
    return null;
  }

  return (
    <div className="rounded-lg border bg-card">
      <div className="border-b px-4 py-3">
        <div className="flex items-center gap-2">
          <FileText className="size-5 text-muted-foreground" />
          <h3 className="font-semibold">Resources</h3>
        </div>
      </div>
      <div className="divide-y">
        {resource.links.map((link, index) => (
          <a
            key={index}
            href={link.url}
            target="_blank"
            rel="noopener noreferrer"
            className="flex items-center justify-between px-4 py-3 transition-colors hover:bg-muted"
          >
            <span className="font-medium">{link.title}</span>
            <ExternalLink className="size-4 text-muted-foreground" />
          </a>
        ))}
      </div>
    </div>
  );
}
