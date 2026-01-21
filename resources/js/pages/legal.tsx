import { Head, Link } from '@inertiajs/react';
import { AppFooter } from '@/components/app-footer';

export default function Legal() {
  return (
    <div className="mx-auto flex min-h-screen max-w-7xl flex-col bg-[#FDFDFC] px-4 py-6 text-[#1b1b18] dark:bg-[#0a0a0a]">
      <Head title="Legal Notice" />
      <div className="flex-1 p-6">
        <Link
          href="/"
          className="text-muted-foreground hover:text-foreground mb-8 inline-flex items-center gap-2 text-sm transition-colors"
        >
          &larr; Back to home
        </Link>

        <article className="prose prose-neutral dark:prose-invert max-w-none">
          <h1>Legal Notice</h1>

          <h2>Website Publisher</h2>
          <p>
            The Mine Adventure website is published by <strong>William Strainchamps</strong>.
          </p>
          <ul>
            <li>Publication Director: William Strainchamps</li>
            <li>Contact: contact@mine-adventure.com</li>
          </ul>

          <h2>Hosting</h2>
          <p>
            This website is hosted by <strong>Laravel Cloud</strong>, a cloud hosting platform provided by Laravel Holdings Inc.
          </p>
          <ul>
            <li>Laravel Holdings Inc.</li>
            <li>Website: <a href="https://cloud.laravel.com" target="_blank" rel="noopener noreferrer">cloud.laravel.com</a></li>
          </ul>

          <h2>Intellectual Property</h2>
          <p>
            All content on the Mine Adventure website (texts, images, logos, videos, graphics) is protected by copyright and remains the exclusive property of William Strainchamps, unless otherwise stated.
          </p>
          <p>
            Any reproduction, representation, modification, publication, or adaptation of all or part of the website elements, regardless of the means or process used, is prohibited without prior written authorization from William Strainchamps.
          </p>

          <h2>Limitation of Liability</h2>
          <p>
            The information contained on this website is as accurate as possible, and the site is periodically updated. However, inaccuracies or omissions may occur. Users are encouraged to verify the accuracy of information with William Strainchamps and to report any modifications they deem useful.
          </p>
          <p>
            Mine Adventure shall not be held liable for any direct or indirect damage caused to the user's equipment when accessing the site or resulting from the use of non-compliant equipment.
          </p>

          <h2>Applicable Law</h2>
          <p>
            These legal notices are governed by French law. In case of dispute, French courts shall have sole jurisdiction.
          </p>

          <p className="text-muted-foreground mt-8 text-sm">
            Last updated: January 2026
          </p>
        </article>
      </div>
      <AppFooter />
    </div>
  );
}
