# Page Web — Institut Universitaire NEVIL Prodige

Site web de présentation de l'institut avec système de candidature en ligne et tableau de bord administrateur.

## Fonctionnalités

### Site public (`institut.html`)
- Page d'accueil avec section héro et animations
- Présentation des facultés, admissions, recherche et hébergement
- Formulaire de candidature avec upload de documents (PDF)
- Mode sombre / clair (basculable)
- Design responsive (mobile-first)
- Barre de progression du scroll
- Menu hamburger sur mobile

### Traitement (`candidature.php`)
- Validation côté serveur des champs du formulaire
- Upload sécurisé des fichiers PDF (relevés de notes + documents académiques)
- Stockage des candidatures dans `candidatures.json`
- Réponse JSON pour soumission AJAX
- Page de confirmation / gestion des erreurs

### Administration (`admin.php`)
- Tableau de bord listant toutes les candidatures
- Recherche en temps réel
- Modale de détail par candidature
- Accès direct aux fichiers uploadés
- Lien retour vers le site public

## Technologies

| Fichier | Technologies |
|---------|-------------|
| `institut.html` | HTML5, CSS3 (variables, animations, responsive), JavaScript vanilla |
| `candidature.php` | PHP 8+, manipulation JSON, upload fichiers |
| `admin.php` | PHP 8+, HTML/CSS/JS intégrés |

## Installation

```bash
# Cloner le dépôt
git clone https://github.com/Josias-Nev/page-web-institut.git
cd page-web-institut

# Lancer un serveur PHP local
php -S localhost:8000

# Ouvrir dans le navigateur
# http://localhost:8000/institut.html   → Site public
# http://localhost:8000/admin.php       → Administration
```

## Structure des fichiers

```
├── institut.html        # Page principale du site
├── candidature.php      # Traitement du formulaire + page de confirmation
├── admin.php            # Tableau de bord administrateur
├── candidatures.json    # Données des candidatures (auto-généré)
└── uploads/             # Fichiers PDF uploadés (auto-généré)
```

## Auteur

**Josias NEVIL Prodige** — Institut universitaire NEVIL Prodige
