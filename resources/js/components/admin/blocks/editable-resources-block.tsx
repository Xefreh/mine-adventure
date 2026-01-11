import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import type { LessonBlock, ResourceLink } from '@/types';
import { ExternalLink, Eye, Pencil, Plus, Trash2 } from 'lucide-react';
import { useState } from 'react';

interface EditableResourcesBlockProps {
  block: LessonBlock;
  onSave: (blockId: number, data: Record<string, unknown>) => void;
}

export function EditableResourcesBlock({ block, onSave }: EditableResourcesBlockProps) {
  const [isEditing, setIsEditing] = useState(false);
  const [links, setLinks] = useState<ResourceLink[]>(block.resource?.links ?? []);

  const handleSave = () => {
    onSave(block.id, { links });
    setIsEditing(false);
  };

  const handleAddLink = () => {
    setLinks([...links, { title: '', url: '' }]);
  };

  const handleRemoveLink = (index: number) => {
    setLinks(links.filter((_, i) => i !== index));
  };

  const handleLinkChange = (index: number, field: 'title' | 'url', value: string) => {
    setLinks(links.map((link, i) => (i === index ? { ...link, [field]: value } : link)));
  };

  const handleToggleEdit = () => {
    if (isEditing) {
      handleSave();
    } else {
      setLinks(block.resource?.links ?? []);
      setIsEditing(true);
    }
  };

  return (
    <Card>
      <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
        <CardTitle className="text-lg">Resources</CardTitle>
        <Button variant="ghost" size="icon" onClick={handleToggleEdit}>
          {isEditing ? <Eye className="h-4 w-4" /> : <Pencil className="h-4 w-4" />}
        </Button>
      </CardHeader>
      <CardContent>
        {isEditing ? (
          <div className="space-y-4">
            {links.map((link, index) => (
              <div key={index} className="flex items-center gap-2">
                <Input value={link.title} onChange={(e) => handleLinkChange(index, 'title', e.target.value)} placeholder="Link title" className="flex-1" />
                <Input value={link.url} onChange={(e) => handleLinkChange(index, 'url', e.target.value)} placeholder="https://..." className="flex-1" />
                <Button variant="ghost" size="icon" onClick={() => handleRemoveLink(index)}>
                  <Trash2 className="h-4 w-4 text-destructive" />
                </Button>
              </div>
            ))}
            <Button variant="outline" size="sm" onClick={handleAddLink}>
              <Plus className="mr-2 h-4 w-4" />
              Add Link
            </Button>
          </div>
        ) : (
          <ul className="space-y-2">
            {links.length === 0 ? (
              <p className="text-sm text-muted-foreground">No resources yet. Click the edit button to add links.</p>
            ) : (
              links.map((link, index) => (
                <li key={index}>
                  <a href={link.url} target="_blank" rel="noopener noreferrer" className="flex items-center gap-2 text-primary hover:underline">
                    <ExternalLink className="h-4 w-4" />
                    {link.title || link.url}
                  </a>
                </li>
              ))
            )}
          </ul>
        )}
      </CardContent>
    </Card>
  );
}
