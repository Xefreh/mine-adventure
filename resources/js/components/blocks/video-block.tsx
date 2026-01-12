import type { BlockVideo } from '@/types';

interface VideoBlockProps {
  video: BlockVideo;
}

function getEmbedUrl(url: string): string | null {
  // YouTube
  const youtubeMatch = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
  if (youtubeMatch) {
    return `https://www.youtube.com/embed/${youtubeMatch[1]}`;
  }

  // Vimeo
  const vimeoMatch = url.match(/vimeo\.com\/(\d+)/);
  if (vimeoMatch) {
    return `https://player.vimeo.com/video/${vimeoMatch[1]}`;
  }

  // Direct video URL (mp4, webm, etc.)
  if (url.match(/\.(mp4|webm|ogg)$/i)) {
    return url;
  }

  return null;
}

function formatDuration(seconds: number): string {
  const minutes = Math.floor(seconds / 60);
  const remainingSeconds = seconds % 60;
  return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
}

export function VideoBlock({ video }: VideoBlockProps) {
  const embedUrl = getEmbedUrl(video.url);
  const isDirectVideo = video.url.match(/\.(mp4|webm|ogg)$/i);

  return (
    <div className="overflow-hidden rounded-lg border bg-card">
      <div className="aspect-video bg-black">
        {isDirectVideo ? (
          <video src={video.url} controls className="h-full w-full" controlsList="nodownload">
            Your browser does not support the video tag.
          </video>
        ) : embedUrl ? (
          <iframe
            src={embedUrl}
            title="Video"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowFullScreen
            className="h-full w-full"
          />
        ) : (
          <div className="flex h-full items-center justify-center text-muted-foreground">
            <p>Unable to load video</p>
          </div>
        )}
      </div>
      {video.duration && (
        <div className="border-t px-4 py-2">
          <span className="text-muted-foreground text-sm">Duration: {formatDuration(video.duration)}</span>
        </div>
      )}
    </div>
  );
}
