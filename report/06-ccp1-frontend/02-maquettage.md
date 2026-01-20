# 6.2 Maquettage des interfaces utilisateur

> **Compétence visée :** Maquetter des interfaces utilisateur web ou web mobile

## Objectif

Cette section présente la démarche de maquettage adoptée pour le projet Mine Adventure, incluant les wireframes, maquettes haute fidélité et le respect des principes d'ergonomie et d'accessibilité.

## Outil utilisé : Figma

**Figma** a été choisi comme outil de maquettage pour ses nombreux avantages :

| Avantage | Description |
|----------|-------------|
| **Collaboratif** | Travail en temps réel, partage de liens |
| **Gratuit** | Plan gratuit suffisant pour le projet |
| **Composants** | Système de composants réutilisables |
| **Prototypage** | Création de prototypes interactifs |
| **Responsive** | Prévisualisation multi-écrans |
| **Plugins** | Écosystème riche (icônes, images, etc.) |

## Démarche de conception

### 1. Analyse des besoins UX

Avant de commencer le maquettage, j'ai identifié les parcours utilisateurs principaux :

**Parcours apprenant :**
```
Connexion → Dashboard → Catalogue cours → Cours → Leçon → Exercice → Progression
```

**Parcours administrateur :**
```
Connexion → Admin Dashboard → Gestion cours → Création contenu → Publication
```

### 2. Wireframes (basse fidélité)

Les wireframes permettent de définir la structure et l'organisation des éléments sans se soucier du design visuel.

#### Principes appliqués :

- **Hiérarchie visuelle** : Les éléments importants sont mis en avant
- **Cohérence** : Navigation et layouts similaires entre les pages
- **Simplicité** : Interfaces épurées, focus sur le contenu

#### Exemples de wireframes réalisés :

[INSÉREZ ICI VOS WIREFRAMES FIGMA :]
- Wireframe de la page d'accueil
- Wireframe du catalogue de cours
- Wireframe d'une page de leçon
- Wireframe de l'interface d'administration

### 3. Maquettes haute fidélité

Les maquettes haute fidélité intègrent :
- La charte graphique complète
- Les couleurs et typographies finales
- Les composants UI détaillés
- Les états des éléments interactifs

#### Charte graphique

| Élément | Valeur |
|---------|--------|
| **Couleur primaire** | [Votre couleur principale] |
| **Couleur secondaire** | [Couleur secondaire] |
| **Couleur d'accent** | [Couleur d'accent] |
| **Fond clair** | #FFFFFF / #F5F5F5 |
| **Fond sombre** | #1A1A1A / #0A0A0A |
| **Typographie principale** | Inter |
| **Typographie code** | JetBrains Mono |

#### Système de composants Figma

J'ai créé une bibliothèque de composants réutilisables :

| Composant | Variantes |
|-----------|-----------|
| **Button** | Primary, Secondary, Outline, Ghost, Destructive |
| **Card** | Default, Hover, Selected |
| **Input** | Default, Focus, Error, Disabled |
| **Badge** | Easy (vert), Medium (jaune), Hard (rouge) |
| **Progress** | Linear, Circular |
| **Navigation** | Desktop, Mobile, Breadcrumb |

### 4. Maquettes des pages principales

#### Page d'accueil / Dashboard

[CAPTURE D'ÉCRAN MAQUETTE FIGMA]

**Éléments clés :**
- Message de bienvenue personnalisé
- Statistiques de progression (cours, leçons, streak)
- Accès rapide au dernier cours en cours
- Navigation vers le catalogue

#### Catalogue des cours

[CAPTURE D'ÉCRAN MAQUETTE FIGMA]

**Éléments clés :**
- Grille de cartes de cours
- Badges de difficulté colorés
- Indicateurs de progression
- Filtres par niveau

#### Page de leçon

[CAPTURE D'ÉCRAN MAQUETTE FIGMA]

**Éléments clés :**
- Barre de progression de la leçon
- Blocs de contenu (vidéo, texte, code)
- Navigation entre leçons
- Bouton de validation

#### Éditeur de code

[CAPTURE D'ÉCRAN MAQUETTE FIGMA]

**Éléments clés :**
- Zone d'énoncé
- Éditeur Monaco (coloration syntaxique)
- Boutons Exécuter / Soumettre
- Zone de résultats (console, tests)

#### Interface administration

[CAPTURE D'ÉCRAN MAQUETTE FIGMA]

**Éléments clés :**
- Sidebar de navigation
- Liste des cours avec actions CRUD
- Formulaires de création/édition
- Interface drag & drop pour l'ordre

### 5. Responsive Design

Les maquettes ont été conçues pour trois breakpoints principaux :

| Breakpoint | Largeur | Cible |
|------------|---------|-------|
| **Mobile** | < 768px | Smartphones |
| **Tablet** | 768px - 1024px | Tablettes |
| **Desktop** | > 1024px | Ordinateurs |

#### Adaptations mobile :

- Navigation hamburger
- Cards en liste verticale
- Éditeur de code en plein écran
- Boutons plus grands (touch-friendly)

[INSÉREZ ICI DES CAPTURES DES MAQUETTES MOBILE]

## Accessibilité (RGAA)

Conformément au Référentiel Général d'Amélioration de l'Accessibilité (RGAA), les maquettes intègrent :

### Contrastes

| Élément | Ratio de contraste | Conformité |
|---------|-------------------|------------|
| Texte sur fond clair | > 4.5:1 | ✅ AA |
| Texte sur fond sombre | > 4.5:1 | ✅ AA |
| Éléments interactifs | > 3:1 | ✅ AA |

### Tailles et espacements

- Taille de police minimale : 16px
- Zones cliquables minimum : 44x44px
- Espacement suffisant entre les éléments interactifs

### Indications visuelles

- États de focus visibles
- Indicateurs non basés uniquement sur la couleur
- Messages d'erreur explicites

## Prototypage interactif

Un prototype Figma a été créé pour simuler les interactions principales :

**Interactions prototypées :**
- Navigation entre pages
- Ouverture/fermeture de menus
- États des boutons (hover, active)
- Transitions entre écrans

[LIEN VERS LE PROTOTYPE FIGMA]

## Validation et itérations

Le maquettage a suivi un processus itératif :

1. **V1** : Wireframes validant la structure
2. **V2** : Maquettes avec charte graphique de base
3. **V3** : Ajustements suite aux premiers développements
4. **V4** : Version finale avec mode sombre

## Conclusion

Le travail de maquettage a permis de :
- Définir une vision claire de l'interface avant le développement
- Créer un système de design cohérent et réutilisable
- Anticiper les problématiques responsive et d'accessibilité
- Faciliter le développement front-end grâce aux spécifications visuelles précises
