import type { BlockText } from '@/types';
import type { Components } from 'react-markdown';
import ReactMarkdown from 'react-markdown';
import { Prism as SyntaxHighlighter } from 'react-syntax-highlighter';
import { vscDarkPlus } from 'react-syntax-highlighter/dist/esm/styles/prism';
import remarkGfm from 'remark-gfm';

interface TextBlockProps {
    text: BlockText;
}

export function TextBlock({ text }: TextBlockProps) {
    const components: Components = {
        pre({ children }) {
            return <>{children}</>;
        },
        code({ className, children, ...props }) {
            const match = /language-(\w+)/.exec(className || '');
            const codeString = String(children).replace(/\n$/, '');

            if (match) {
                return (
                    <SyntaxHighlighter
                        style={vscDarkPlus}
                        language={match[1]}
                        PreTag="div"
                        customStyle={{
                            margin: 0,
                            borderRadius: '0.5rem',
                            padding: '1rem',
                        }}
                    >
                        {codeString}
                    </SyntaxHighlighter>
                );
            }

            return (
                <code className={className} {...props}>
                    {children}
                </code>
            );
        },
    };

    return (
        <div className="prose dark:prose-invert max-w-none">
            <ReactMarkdown remarkPlugins={[remarkGfm]} components={components}>
                {text.content}
            </ReactMarkdown>
        </div>
    );
}
