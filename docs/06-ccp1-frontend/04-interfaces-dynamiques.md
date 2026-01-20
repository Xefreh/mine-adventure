# 6.4 Développement des interfaces dynamiques

> **Compétence visée :** Développer la partie dynamique des interfaces utilisateur web ou web mobile

## Objectif

Cette section présente le développement des fonctionnalités dynamiques de Mine Adventure : gestion d'état, interactions utilisateur, formulaires, et intégration avec le backend via Inertia.js.

## Stack technologique

Les interfaces dynamiques reposent sur **React 19** et **Inertia.js v2** pour la communication client-serveur. **Zustand** gère l'état global et **Wayfinder** génère des routes type-safe.

## Navigation avec Inertia

```tsx
import { Link, router } from '@inertiajs/react';
import { show } from '@/actions/App/Http/Controllers/CourseController';

// Navigation déclarative
<Link href="/courses">Voir les cours</Link>

// Navigation programmatique type-safe
router.visit(show.url(courseId));
```

## Formulaires avec Inertia

```tsx
import { Form } from '@inertiajs/react';
import { store } from '@/actions/App/Http/Controllers/Admin/CourseController';

export function CreateCourseForm() {
    return (
        <Form {...store.form()}>
            {({ errors, processing }) => (
                <>
                    <Input name="name" className={errors.name ? 'border-destructive' : ''} />
                    {errors.name && <p className="text-destructive">{errors.name}</p>}
                    <Button disabled={processing}>
                        {processing ? 'Création...' : 'Créer'}
                    </Button>
                </>
            )}
        </Form>
    );
}
```

## Gestion d'état avec Zustand

```tsx
import { create } from 'zustand';

interface CodeEditorState {
    code: string;
    output: string;
    isRunning: boolean;
    setCode: (code: string) => void;
    setOutput: (output: string) => void;
}

export const useCodeEditorStore = create<CodeEditorState>((set) => ({
    code: '',
    output: '',
    isRunning: false,
    setCode: (code) => set({ code }),
    setOutput: (output) => set({ output }),
}));
```

## Exécution de code asynchrone

```tsx
export function useCodeExecution(assignmentId: number) {
    const store = useCodeEditorStore();

    async function runCode() {
        store.setRunning(true);
        router.post(run.url(assignmentId), { code: store.code }, {
            preserveState: true,
            onSuccess: (page) => store.setOutput(page.props.output),
            onFinish: () => store.setRunning(false),
        });
    }

    return { runCode };
}
```

## Drag and Drop

Le réordonnancement des chapitres et leçons utilise `@dnd-kit/core` pour une expérience fluide et accessible.

```tsx
import { DndContext, closestCenter } from '@dnd-kit/core';
import { SortableContext, arrayMove } from '@dnd-kit/sortable';

function handleDragEnd(event) {
    const newItems = arrayMove(items, oldIndex, newIndex);
    router.post(`/admin/courses/${courseId}/chapters/reorder`, {
        order: newItems.map((item) => item.id),
    });
}
```

## Mode sombre

```tsx
export function useTheme() {
    const [theme, setTheme] = useState<'light' | 'dark'>('light');

    useEffect(() => {
        document.documentElement.classList.toggle('dark', theme === 'dark');
        localStorage.setItem('theme', theme);
    }, [theme]);

    return { theme, toggleTheme: () => setTheme(t => t === 'light' ? 'dark' : 'light') };
}
```

## Captures d'écran

![Éditeur de code avec résultats de tests](../imgs/editeur-code-composant.png)
