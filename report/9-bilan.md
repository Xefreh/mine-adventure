# 10. Bilan

## Objectif

Cette section conclut le dossier de projet en présentant les compétences acquises, les difficultés rencontrées et leurs solutions, ainsi que les perspectives d'évolution de Mine Adventure.

## Compétences acquises

### Compétences techniques

#### Front-end

| Compétence                   | Niveau avant  | Niveau après  | Contexte d'acquisition                 |
|------------------------------|---------------|---------------|----------------------------------------|
| React + TypeScript           | Intermédiaire | Avancé        | Développement de toutes les interfaces |
| Tailwind CSS                 | Débutant      | Avancé        | Styling de l'application complète      |
| Gestion d'état (Zustand)     | Débutant      | Intermédiaire | Store de l'éditeur de code             |
| Inertia.js                   | Débutant      | Avancé        | Architecture complète du projet        |
| Composants UI (Radix/shadcn) | Débutant      | Avancé        | Bibliothèque de composants             |

#### Back-end

| Compétence   | Niveau avant  | Niveau après  | Contexte d'acquisition             |
|--------------|---------------|---------------|------------------------------------|
| Laravel 12   | Intermédiaire | Avancé        | Développement du backend complet   |
| Eloquent ORM | Intermédiaire | Avancé        | Modélisation et requêtes complexes |
| API REST     | Intermédiaire | Avancé        | Intégration Judge0                 |
| Tests (Pest) | Débutant      | Intermédiaire | Tests unitaires et fonctionnels    |
| Docker       | Débutant      | Intermédiaire | Conteneurisation de l'application  |

#### DevOps

| Compétence               | Niveau avant | Niveau après  | Contexte d'acquisition   |
|--------------------------|--------------|---------------|--------------------------|
| CI/CD (GitHub Actions)   | Débutant     | Intermédiaire | Pipeline automatisé      |
| Déploiement (Coolify)    | Débutant     | Intermédiaire | Mise en production       |
| Gestion de serveur (VPS) | Débutant     | Intermédiaire | Configuration production |

### Compétences transversales

| Compétence                  | Description                                            |
|-----------------------------|--------------------------------------------------------|
| **Gestion de projet**       | Planification, priorisation, suivi de l'avancement     |
| **Résolution de problèmes** | Debugging, recherche de solutions, adaptation          |
| **Veille technologique**    | Identification et évaluation de nouvelles technologies |
| **Documentation**           | Rédaction technique, commentaires de code              |
| **Autonomie**               | Travail en solo sur un projet complet                  |

## Difficultés rencontrées et solutions

### 1. Intégration de Judge0

**Problème :**
L'intégration de l'API Judge0 pour l'exécution de code Java s'est avérée complexe, notamment pour le parsing des résultats JUnit.

**Difficultés spécifiques :**
- Format de sortie JUnit variable selon les versions
- Gestion des timeouts et des erreurs de compilation
- Encodage base64 des entrées/sorties

**Solution :**
```php
// Création d'un service dédié avec parsing robuste
class TestSubmissionService
{
    private function parseTestResults(string $output): array
    {
        // Parsing flexible gérant plusieurs formats
        // Gestion des cas d'erreur et de compilation
        // ...
    }
}
```

**Apprentissage :** L'importance d'isoler la logique complexe dans des services dédiés et de prévoir tous les cas d'erreur.

### 2. Relations polymorphiques Laravel

**Problème :**
La structure des blocs de leçon (5 types différents) nécessitait une relation polymorphique, concept nouveau pour moi.

**Solution :**
```php
// Utilisation des relations morphTo/morphOne
class LessonBlock extends Model
{
    public function blockable(): MorphTo
    {
        return $this->morphTo();
    }
}

class BlockVideo extends Model
{
    public function lessonBlock(): MorphOne
    {
        return $this->morphOne(LessonBlock::class, 'blockable');
    }
}
```

**Apprentissage :** Les relations polymorphiques de Laravel sont puissantes pour modéliser des structures flexibles.

### 3. Performance du rendu des leçons

**Problème :**
Le chargement des leçons avec de nombreux blocs était lent à cause des requêtes N+1.

**Diagnostic :**
```php
// Avant - N+1 queries
$lesson = Lesson::find($id);
foreach ($lesson->blocks as $block) {
    echo $block->blockable->content; // Nouvelle requête à chaque fois
}
```

**Solution :**
```php
// Après - Eager loading
$lesson = Lesson::with(['blocks.blockable'])->find($id);
```

**Apprentissage :** Toujours vérifier les requêtes générées et utiliser l'eager loading systématiquement.

### 4. Gestion du state dans l'éditeur de code

**Problème :**
L'éditeur de code nécessitait une gestion d'état complexe (code, résultats, loading states) partagée entre plusieurs composants.

**Solution :**
Adoption de Zustand pour un store dédié :
```tsx
const useCodeEditorStore = create((set) => ({
    code: '',
    output: '',
    isRunning: false,
    testResults: [],
    setCode: (code) => set({ code }),
    // ...
}));
```

**Apprentissage :** Un store externe simplifie la gestion d'état complexe par rapport au prop drilling.

### 5. Configuration Docker multi-stage

**Problème :**
L'image Docker initiale était trop volumineuse (>1GB) et le build était lent.

**Solution :**
Implémentation d'un build multi-stage :
- Stage 1 : Build des assets frontend
- Stage 2 : Installation des dépendances PHP
- Stage 3 : Image de production légère

**Résultat :** Image finale < 200MB, build time réduit de 50%

**Apprentissage :** Les builds multi-stage sont essentiels pour des images Docker optimisées.

## Ce que je ferais différemment

| Aspect            | Approche initiale    | Approche améliorée                |
|-------------------|----------------------|-----------------------------------|
| **Tests**         | Écrits après le code | TDD ou tests en parallèle         |
| **Documentation** | En fin de projet     | Continue, au fil du développement |
| **Validation**    | Parfois côté client  | Toujours côté serveur d'abord     |
| **Accessibilité** | Ajoutée après        | Intégrée dès le début             |

## Perspectives d'évolution

### Fonctionnalités à court terme

1. **Système de commentaires**
   - Permettre aux apprenants de poser des questions sur les leçons
   - Réponses des instructeurs

2. **Certificats de complétion**
   - Génération PDF à la fin d'un cours
   - Partage sur LinkedIn

3. **Mode hors-ligne (PWA)**
   - Consultation des leçons sans connexion
   - Synchronisation au retour en ligne

### Fonctionnalités à moyen terme

1. **Éditeur de code amélioré**
   - Autocomplétion contextuelle
   - Détection d'erreurs en temps réel (LSP)

2. **Gamification**
   - Badges et achievements
   - Classements entre apprenants
   - Points d'expérience

3. **Parcours personnalisés**
   - Recommandations basées sur le niveau
   - Tests de positionnement

### Évolutions techniques

1. **Migration Base UI**
   - À considérer quand l'écosystème sera plus mature
   - Meilleure maintenance long terme

2. **Tests end-to-end**
   - Playwright ou Cypress
   - Couverture des parcours critiques

3. **Monitoring et observabilité**
   - Sentry pour le tracking d'erreurs
   - Analytics d'usage

## Conclusion personnelle

Le développement de Mine Adventure a été une expérience formatrice complète, me permettant de :

- **Maîtriser un stack moderne** : Laravel + React + TypeScript + Tailwind
- **Gérer un projet de A à Z** : De l'analyse des besoins au déploiement
- **Résoudre des problèmes complexes** : Exécution de code, polymorphisme, performance
- **Adopter les bonnes pratiques** : Tests, sécurité, documentation, CI/CD

Ce projet démontre ma capacité à concevoir, développer et déployer une application web complète, tout en maintenant une qualité de code professionnelle et en restant à jour sur les technologies.

Je suis confiant dans ma capacité à contribuer efficacement à des projets de développement web, que ce soit en entreprise ou en tant que développeur indépendant.

---

**Projet réalisé par William Strainchamps**

**Formation : Titre Professionnel Développeur Web et Web Mobile**

**Période : 5 mai 2025 - 2 février 2026**
