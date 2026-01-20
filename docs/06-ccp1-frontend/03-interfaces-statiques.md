# 6.3 Réalisation des interfaces utilisateur statiques

> **Compétence visée :** Réaliser des interfaces utilisateur statiques web ou web mobile

## Objectif

Cette section présente la réalisation des interfaces statiques de Mine Adventure : structure HTML sémantique, styling avec Tailwind CSS, et composants UI réutilisables.

## Technologies

**React 19** et **TypeScript 5.x** pour la bibliothèque UI. **Tailwind CSS 4** pour le styling. **shadcn/ui** et **Radix UI** pour les composants accessibles. **Lucide React** pour les icônes.

## Structure HTML sémantique

```tsx
<div className="min-h-screen bg-background">
    <header>
        <nav aria-label="Navigation principale">{/* ... */}</nav>
    </header>
    <main id="main-content">{/* Contenu */}</main>
    <footer>{/* ... */}</footer>
</div>
```

Les balises `<article>` encapsulent les cartes de cours, `<section>` organise les sections, `<aside>` la sidebar admin.

## Architecture des composants

```
resources/js/components/
├── ui/           # Composants shadcn/ui (button, card, input...)
├── blocks/       # Blocs de leçon (video, text, quiz, assignment)
├── dashboard/    # Composants tableau de bord
└── navigation/   # Navbar, sidebar
```

## Exemple : CourseCard

```tsx
export function CourseCard({ course }: CourseCardProps) {
    return (
        <Card className="overflow-hidden hover:shadow-lg transition-shadow">
            <div className="aspect-video bg-muted">
                <img src={course.thumbnail} alt={course.name} className="object-cover" />
            </div>
            <CardHeader>
                <CardTitle>{course.name}</CardTitle>
                <Badge className={difficultyConfig[course.difficulty].className}>
                    {difficultyConfig[course.difficulty].label}
                </Badge>
            </CardHeader>
            <CardContent>
                <Progress value={course.progress} />
            </CardContent>
        </Card>
    );
}
```

## Tailwind CSS 4

```css
@import "../../node_modules/tailwindcss/dist/lib.d.mts";

@theme {
    --color-primary: oklch(0.65 0.15 250);
    --font-sans: "Inter", system-ui, sans-serif;
    --font-mono: "JetBrains Mono", monospace;
}
```

## Responsive Design

Breakpoints : `sm:` (640px), `md:` (768px), `lg:` (1024px), `xl:` (1280px).

```tsx
<div className="flex flex-col lg:flex-row">
    <aside className="hidden lg:block w-64">{/* Sidebar desktop */}</aside>
    <div className="lg:hidden">{/* Menu mobile */}</div>
    <main className="flex-1 p-4 md:p-6 lg:p-8">{/* Contenu */}</main>
</div>
```

## Mode sombre

```tsx
<div className="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
```

## Accessibilité

```tsx
<nav aria-label="Navigation principale">
<button aria-label="Fermer">{/* ... */}</button>
<Input id="email" aria-describedby="email-error" />
```

## Captures d'écran

![Tableau de bord](../imgs/tableau-bord.png)

![Catalogue des cours](../imgs/catalogue-cours.png)
