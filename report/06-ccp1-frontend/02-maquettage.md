# 6.2 Maquettage des interfaces utilisateur

> **Compétence visée :** Maquetter des interfaces utilisateur web ou web mobile

## Objectif

Cette section présente la démarche de maquettage adoptée pour Mine Adventure.

## Outil : Figma

**Figma** a été choisi pour son aspect **collaboratif**, son **système de composants réutilisables**, ses fonctionnalités de **prototypage**, et sa **prévisualisation responsive**.

## Démarche de conception

### Parcours utilisateurs

**Apprenant :** Connexion → Dashboard → Catalogue → Cours → Leçon → Exercice

**Administrateur :** Connexion → Admin → Gestion cours → Création contenu

### Charte graphique

L'application utilise **OKLCH** pour les couleurs et supporte un thème clair/sombre.

- **Mode clair** : fond blanc, texte noir, bordures grises
- **Mode sombre** : fond noir, texte blanc, bordures sombres
- **Typographie** : Inter (texte), JetBrains Mono (code)

### Composants Figma

- **Boutons** : Primary, Secondary, Outline, Ghost, Destructive
- **Cartes** : Default, Hover, Selected
- **Inputs** : Default, Focus, Error, Disabled
- **Badges** : Easy (vert), Medium (jaune), Hard (rouge)
- **Navigation** : Desktop, Mobile, Breadcrumb

## Responsive Design

Trois breakpoints : **Mobile** (<768px), **Tablet** (768-1024px), **Desktop** (>1024px).

Adaptations mobile : navigation hamburger, cards en liste verticale, éditeur plein écran.

![Version mobile du tableau de bord](../imgs/tableau-bord-mobile.png)

## Accessibilité (RGAA)

- **Contrastes** : ratio >4.5:1 pour le texte, >3:1 pour les éléments interactifs
- **Tailles** : police min 16px, zones cliquables min 44x44px
- **Focus** : états de focus visibles pour la navigation clavier

## Processus itératif

1. Wireframes (structure)
2. Maquettes avec charte graphique
3. Ajustements post-développement
4. Version finale avec mode sombre
