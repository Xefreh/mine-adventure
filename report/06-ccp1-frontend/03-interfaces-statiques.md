# 6.3 Réalisation des interfaces utilisateur statiques

> **Compétence visée :** Réaliser des interfaces utilisateur statiques web ou web mobile

## Objectif

Cette section présente la réalisation des interfaces statiques de Mine Adventure, incluant la structure HTML sémantique, le styling avec Tailwind CSS, et les composants UI réutilisables.

## Technologies utilisées

| Technologie | Version | Rôle |
|-------------|---------|------|
| **React** | 19 | Bibliothèque UI |
| **TypeScript** | 5.x | Typage statique |
| **Tailwind CSS** | 4 | Framework CSS utilitaire |
| **shadcn/ui** | - | Composants UI pré-construits |
| **Radix UI** | - | Primitives accessibles |
| **Lucide React** | - | Bibliothèque d'icônes |

## Structure HTML sémantique

### Principes appliqués

L'application utilise des balises HTML sémantiques pour améliorer l'accessibilité et le référencement :

```tsx
// Layout principal (app-layout.tsx)
<div className="min-h-screen bg-background">
    <header>
        <nav aria-label="Navigation principale">
            {/* Navigation */}
        </nav>
    </header>

    <main id="main-content">
        {/* Contenu principal */}
    </main>

    <footer>
        {/* Pied de page */}
    </footer>
</div>
```

### Balises sémantiques utilisées

| Balise | Usage dans le projet |
|--------|---------------------|
| `<header>` | En-tête avec navigation |
| `<nav>` | Menus de navigation |
| `<main>` | Contenu principal de la page |
| `<article>` | Cartes de cours, blocs de leçon |
| `<section>` | Sections de page |
| `<aside>` | Sidebar administration |
| `<footer>` | Pied de page |
| `<figure>` | Images avec légendes |

## Système de composants

### Architecture des composants

```
resources/js/components/
├── ui/                     # Composants shadcn/ui de base
│   ├── button.tsx
│   ├── card.tsx
│   ├── input.tsx
│   ├── badge.tsx
│   ├── progress.tsx
│   └── ...
├── blocks/                 # Composants de blocs de leçon
│   ├── video-block.tsx
│   ├── text-block.tsx
│   ├── quiz-block.tsx
│   └── assignment-block.tsx
├── dashboard/              # Composants du tableau de bord
│   ├── stats-card.tsx
│   └── course-progress.tsx
└── navigation/             # Composants de navigation
    ├── navbar.tsx
    └── sidebar.tsx
```

### Exemple de composant : Card de cours

```tsx
// components/course-card.tsx
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Progress } from '@/components/ui/progress';

interface CourseCardProps {
    course: {
        id: number;
        name: string;
        thumbnail: string | null;
        difficulty: 'easy' | 'medium' | 'hard';
        chaptersCount: number;
        lessonsCount: number;
        progress?: number;
    };
}

const difficultyConfig = {
    easy: { label: 'Facile', className: 'bg-green-100 text-green-800' },
    medium: { label: 'Moyen', className: 'bg-yellow-100 text-yellow-800' },
    hard: { label: 'Difficile', className: 'bg-red-100 text-red-800' },
};

export function CourseCard({ course }: CourseCardProps) {
    const difficulty = difficultyConfig[course.difficulty];

    return (
        <Card className="overflow-hidden hover:shadow-lg transition-shadow">
            {/* Image de couverture */}
            <div className="aspect-video bg-muted">
                {course.thumbnail ? (
                    <img
                        src={course.thumbnail}
                        alt={`Couverture du cours ${course.name}`}
                        className="w-full h-full object-cover"
                    />
                ) : (
                    <div className="w-full h-full flex items-center justify-center">
                        <span className="text-muted-foreground">Pas d'image</span>
                    </div>
                )}
            </div>

            <CardHeader>
                <div className="flex items-start justify-between">
                    <CardTitle className="text-lg">{course.name}</CardTitle>
                    <Badge className={difficulty.className}>
                        {difficulty.label}
                    </Badge>
                </div>
            </CardHeader>

            <CardContent>
                <p className="text-sm text-muted-foreground mb-4">
                    {course.chaptersCount} chapitres · {course.lessonsCount} leçons
                </p>

                {course.progress !== undefined && (
                    <div className="space-y-2">
                        <div className="flex justify-between text-sm">
                            <span>Progression</span>
                            <span>{course.progress}%</span>
                        </div>
                        <Progress value={course.progress} />
                    </div>
                )}
            </CardContent>
        </Card>
    );
}
```

## Styling avec Tailwind CSS 4

### Configuration Tailwind

```css
/* resources/css/app.css */
@import "tailwindcss";

@theme {
    /* Couleurs personnalisées */
    --color-primary: oklch(0.65 0.15 250);
    --color-primary-foreground: oklch(0.98 0 0);

    /* Polices */
    --font-sans: "Inter", system-ui, sans-serif;
    --font-mono: "JetBrains Mono", monospace;

    /* Rayons de bordure */
    --radius-lg: 0.75rem;
    --radius-md: 0.5rem;
    --radius-sm: 0.25rem;
}
```

### Classes utilitaires Tailwind

#### Layout et espacement

```tsx
{/* Conteneur responsive */}
<div className="container mx-auto px-4 sm:px-6 lg:px-8">

{/* Grille de cartes responsive */}
<div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

{/* Flexbox avec espacement */}
<div className="flex items-center gap-4">
```

#### Typographie

```tsx
{/* Hiérarchie de titres */}
<h1 className="text-3xl font-bold tracking-tight">
<h2 className="text-2xl font-semibold">
<h3 className="text-xl font-medium">
<p className="text-base text-muted-foreground">
<small className="text-sm text-muted-foreground">
```

#### États et interactions

```tsx
{/* Bouton avec états */}
<button className="
    bg-primary text-primary-foreground
    hover:bg-primary/90
    focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2
    disabled:opacity-50 disabled:cursor-not-allowed
    transition-colors
">

{/* Carte avec hover */}
<div className="
    bg-card rounded-lg border
    hover:shadow-lg hover:border-primary/50
    transition-all duration-200
">
```

### Mode sombre

Le mode sombre est géré via la classe `dark` sur l'élément `<html>` :

```tsx
// Composant avec support dark mode
<div className="
    bg-white dark:bg-gray-900
    text-gray-900 dark:text-gray-100
    border-gray-200 dark:border-gray-800
">
```

## Responsive Design

### Breakpoints Tailwind

| Préfixe | Largeur min | Usage |
|---------|-------------|-------|
| `sm:` | 640px | Petits écrans |
| `md:` | 768px | Tablettes |
| `lg:` | 1024px | Ordinateurs portables |
| `xl:` | 1280px | Grands écrans |
| `2xl:` | 1536px | Très grands écrans |

### Exemple de layout responsive

```tsx
// Page de cours avec sidebar responsive
<div className="flex flex-col lg:flex-row min-h-screen">
    {/* Sidebar - cachée sur mobile, visible sur desktop */}
    <aside className="
        hidden lg:block
        w-64 shrink-0
        border-r bg-muted/30
    ">
        <nav className="p-4 space-y-2">
            {/* Navigation du cours */}
        </nav>
    </aside>

    {/* Menu mobile */}
    <div className="lg:hidden p-4 border-b">
        <Sheet>
            <SheetTrigger asChild>
                <Button variant="outline" size="icon">
                    <Menu className="h-5 w-5" />
                </Button>
            </SheetTrigger>
            <SheetContent side="left">
                {/* Même navigation que sidebar */}
            </SheetContent>
        </Sheet>
    </div>

    {/* Contenu principal */}
    <main className="flex-1 p-4 md:p-6 lg:p-8">
        {/* Contenu de la leçon */}
    </main>
</div>
```

## Accessibilité

### Attributs ARIA

```tsx
{/* Navigation avec landmarks */}
<nav aria-label="Navigation principale">

{/* Boutons avec labels */}
<button aria-label="Fermer le menu">
    <X className="h-5 w-5" />
</button>

{/* Formulaires accessibles */}
<div>
    <Label htmlFor="email">Email</Label>
    <Input
        id="email"
        type="email"
        aria-describedby="email-error"
    />
    <p id="email-error" className="text-destructive text-sm">
        {errors.email}
    </p>
</div>

{/* États de chargement */}
<button disabled={loading} aria-busy={loading}>
    {loading ? 'Chargement...' : 'Soumettre'}
</button>
```

### Focus visible

```tsx
{/* Focus ring visible pour la navigation clavier */}
<a className="
    focus:outline-none
    focus-visible:ring-2
    focus-visible:ring-primary
    focus-visible:ring-offset-2
">
```

## Optimisation des assets

### Images

```tsx
{/* Images avec lazy loading et dimensions */}
<img
    src={course.thumbnail}
    alt={`Couverture du cours ${course.name}`}
    loading="lazy"
    width={400}
    height={225}
    className="w-full h-auto object-cover"
/>
```

### Icônes (Lucide)

```tsx
import { BookOpen, Clock, CheckCircle } from 'lucide-react';

// Icônes utilisées avec tailles cohérentes
<BookOpen className="h-5 w-5" />
<Clock className="h-4 w-4 text-muted-foreground" />
<CheckCircle className="h-5 w-5 text-green-500" />
```

## Captures d'écran

[INSÉREZ ICI DES CAPTURES D'ÉCRAN MONTRANT :]
- La page d'accueil en version desktop
- La même page en version mobile
- Le catalogue de cours avec les cartes
- Une page de leçon avec les différents blocs
- L'interface en mode sombre
