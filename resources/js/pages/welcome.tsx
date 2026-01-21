import { dashboard, login } from '@/routes';
import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { AppFooter } from '@/components/app-footer';
import { Info } from 'lucide-react';

export default function Welcome() {
  const { auth } = usePage<SharedData>().props;

  return (
    <div className="mx-auto flex min-h-screen max-w-7xl flex-col bg-[#FDFDFC] px-4 py-6 text-[#1b1b18] dark:bg-[#0a0a0a]">
      <Head title="Welcome">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
      </Head>
      <div className="flex-1 p-6">
        <header className="text-sm not-has-[nav]:hidden">
          <nav className="flex justify-end gap-4">
            {auth.user ? (
              <Link
                href={dashboard()}
                className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
              >
                Dashboard
              </Link>
            ) : (
              <>
                <Link
                  href={login()}
                  className="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                >
                  Log in
                </Link>
              </>
            )}
          </nav>
        </header>

        {!auth.user && (
          <div className="mt-8 flex items-center gap-3 rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-900 dark:bg-blue-950">
            <Info className="h-5 w-5 shrink-0 text-blue-600 dark:text-blue-400" />
            <p className="text-sm text-blue-800 dark:text-blue-200">
              Please{' '}
              <Link href={login()} className="font-medium underline hover:no-underline">
                log in
              </Link>{' '}
              to access the platform and explore our courses.
            </p>
          </div>
        )}
      </div>
      <AppFooter />
    </div>
  );
}
