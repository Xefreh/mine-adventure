import { Head, Link } from '@inertiajs/react';
import { AppFooter } from '@/components/app-footer';

export default function Privacy() {
  return (
    <div className="mx-auto flex min-h-screen max-w-7xl flex-col bg-[#FDFDFC] px-4 py-6 text-[#1b1b18] dark:bg-[#0a0a0a]">
      <Head title="Privacy Policy" />
      <div className="flex-1 p-6">
        <Link
          href="/"
          className="text-muted-foreground hover:text-foreground mb-8 inline-flex items-center gap-2 text-sm transition-colors"
        >
          &larr; Back to home
        </Link>

        <article className="prose prose-neutral dark:prose-invert max-w-none">
          <h1>Privacy Policy</h1>

          <p>
            At Mine Adventure, we place great importance on protecting your personal data. This privacy policy explains how we collect, use, and protect your information.
          </p>

          <h2>Data Controller</h2>
          <p>
            The data controller is <strong>William Strainchamps</strong>.
          </p>

          <h2>Data Collected</h2>
          <p>We collect the following data:</p>
          <ul>
            <li><strong>Identification data</strong>: name, first name, email address</li>
            <li><strong>Connection data</strong>: IP address, connection logs</li>
            <li><strong>Usage data</strong>: course progress, preferences</li>
          </ul>

          <h2>Purpose of Processing</h2>
          <p>Your data is collected to:</p>
          <ul>
            <li>Manage your user account</li>
            <li>Provide access to courses and educational content</li>
            <li>Track your learning progress</li>
            <li>Improve our services and your user experience</li>
            <li>Send you account-related communications (if you have consented)</li>
          </ul>

          <h2>Legal Basis for Processing</h2>
          <p>
            The processing of your data is based on the performance of the contract that binds us when you use our services, as well as your consent for certain purposes such as marketing communications.
          </p>

          <h2>Data Retention Period</h2>
          <p>
            Your personal data is retained for the duration of your registration on the platform, then for a period of 3 years after your last activity. Billing data is retained in accordance with legal obligations (10 years).
          </p>

          <h2>Data Hosting</h2>
          <p>
            Your data is hosted on <strong>Laravel Cloud</strong>, a secure cloud infrastructure. Appropriate technical and organizational measures are implemented to ensure the security of your data.
          </p>

          <h2>Your Rights</h2>
          <p>In accordance with GDPR, you have the following rights:</p>
          <ul>
            <li><strong>Right of access</strong>: obtain a copy of your personal data</li>
            <li><strong>Right of rectification</strong>: correct inaccurate data</li>
            <li><strong>Right to erasure</strong>: request deletion of your data</li>
            <li><strong>Right to portability</strong>: receive your data in a structured format</li>
            <li><strong>Right to object</strong>: object to the processing of your data</li>
            <li><strong>Right to restriction</strong>: limit the processing of your data</li>
          </ul>
          <p>
            To exercise these rights, contact us at: <strong>contact@mine-adventure.com</strong>
          </p>

          <h2>Cookies</h2>
          <p>
            Our website uses essential cookies for the operation of the service (session cookies, preferences). We do not use advertising or third-party tracking cookies without your explicit consent.
          </p>

          <h2>Changes</h2>
          <p>
            We reserve the right to modify this privacy policy. Any changes will be published on this page with an update date.
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
