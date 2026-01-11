import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { LessonBlock } from '@/types';
import { Pencil, Video } from 'lucide-react';
import { useState } from 'react';

interface EditableVideoBlockProps {
  block: LessonBlock;
  onSave: (blockId: number, data: Record<string, unknown>) => void;
  isDragging?: boolean;
}

export function EditableVideoBlock({ block, onSave, isDragging }: EditableVideoBlockProps) {
  const [isOpen, setIsOpen] = useState(false);
  const [url, setUrl] = useState(block.video?.url ?? '');

  const video = block.video;
  const isYouTube = video?.url?.includes('youtube.com') || video?.url?.includes('youtu.be');
  const isVimeo = video?.url?.includes('vimeo.com');
  const hasValidUrl = video?.url && (isYouTube || isVimeo);

  const getEmbedUrl = () => {
    if (!video?.url) return '';
    if (isYouTube) {
      const videoId = video.url.includes('youtu.be') ? video.url.split('/').pop() : new URLSearchParams(new URL(video.url).search).get('v');
      return `https://www.youtube.com/embed/${videoId}`;
    }
    if (isVimeo) {
      const videoId = video.url.split('/').pop();
      return `https://player.vimeo.com/video/${videoId}`;
    }
    return video.url;
  };

  const handleSave = () => {
    onSave(block.id, { url });
    setIsOpen(false);
  };

  const handleOpenChange = (open: boolean) => {
    setIsOpen(open);
    if (open) {
      setUrl(block.video?.url ?? '');
    }
  };

  if (!hasValidUrl) {
    return (
      <Dialog open={isOpen} onOpenChange={handleOpenChange}>
        <DialogTrigger asChild>
          <Card className="cursor-pointer transition-colors hover:bg-muted/50">
            <CardContent className="flex flex-col items-center justify-center gap-4 py-12">
              <Video className="h-12 w-12 text-muted-foreground" />
              <p className="text-sm text-muted-foreground">Click to configure video</p>
            </CardContent>
          </Card>
        </DialogTrigger>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Configure Video</DialogTitle>
          </DialogHeader>
          <div className="space-y-4 py-4">
            <div className="space-y-2">
              <Label htmlFor="video-url">Video URL</Label>
              <Input id="video-url" value={url} onChange={(e) => setUrl(e.target.value)} placeholder="https://youtube.com/watch?v=..." />
              <p className="text-xs text-muted-foreground">Supports YouTube and Vimeo URLs</p>
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setIsOpen(false)}>
              Cancel
            </Button>
            <Button onClick={handleSave}>Save</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    );
  }

  return (
    <div className="group relative">
      <div className="aspect-video w-full overflow-hidden rounded-lg">
        <iframe
          src={getEmbedUrl()}
          className={`h-full w-full ${isDragging ? 'pointer-events-none' : ''}`}
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowFullScreen
          title="Video"
        />
      </div>
      <Dialog open={isOpen} onOpenChange={handleOpenChange}>
        <DialogTrigger asChild>
          <Button size="icon" variant="secondary" className="absolute right-2 top-2 opacity-0 transition-opacity group-hover:opacity-100">
            <Pencil className="h-4 w-4" />
          </Button>
        </DialogTrigger>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Configure Video</DialogTitle>
          </DialogHeader>
          <div className="space-y-4 py-4">
            <div className="space-y-2">
              <Label htmlFor="video-url">Video URL</Label>
              <Input id="video-url" value={url} onChange={(e) => setUrl(e.target.value)} placeholder="https://youtube.com/watch?v=..." />
              <p className="text-xs text-muted-foreground">Supports YouTube and Vimeo URLs</p>
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setIsOpen(false)}>
              Cancel
            </Button>
            <Button onClick={handleSave}>Save</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  );
}
