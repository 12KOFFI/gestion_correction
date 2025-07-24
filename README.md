# Gestion de Correction - Application Web

Application web de gestion des corrections d'épreuves pour les établissements scolaires.

## Fonctionnalités

- Gestion des professeurs
- Gestion des épreuves
- Gestion des examens
- Gestion des établissements
- Attribution des corrections aux professeurs

## Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx)
- Composer (pour la gestion des dépendances)

## Installation

1. Cloner le dépôt :
   ```bash
   git clone https://github.com/12KOFFI/gestion_correction.git
   cd gestion_correction
   ```

2. Installer les dépendances :
   ```bash
   composer install
   ```

3. Configurer la base de données :
   - Créer une base de données MySQL
   - Importer le fichier SQL (à fournir) ou exécuter les migrations
   - Configurer les paramètres de connexion dans `config/Database.php`

4. Configurer le serveur web :
   - Pointer la racine du document vers le répertoire `public/`
   - Configurer la réécriture d'URL si nécessaire

## Structure du projet

```
app/
├── config/           # Fichiers de configuration
├── public/           # Point d'entrée public
├── src/
│   ├── Controllers/  # Contrôleurs
│   ├── Models/       # Modèles
│   └── Views/        # Vues
└── vendor/           # Dépendances Composer
```

## Utilisation

1. Accéder à l'application via votre navigateur
2. Connectez-vous avec vos identifiants
3. Naviguez à travers les différentes sections :
   - **Professeurs** : Gérer les professeurs
   - **Épreuves** : Gérer les épreuves
   - **Examens** : Gérer les examens
   - **Établissements** : Gérer les établissements

## Développement

### Conventions de code

- Suivre les standards PSR-4 pour l'autoloading
- Utiliser la documentation PHPDoc pour les classes et méthodes
- Suivre les conventions de nommage PSR-1 et PSR-12

### Tests

Pour exécuter les tests :

```bash
composer test
```

## Déploiement

1. Mettre à jour la configuration de production dans `config/Database.php`
2. Vérifier les permissions des dossiers
3. Désactiver l'affichage des erreurs en production

## Contribution

Les contributions sont les bienvenues ! Pour contribuer :

1. Forkez le projet
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Poussez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.

## Auteur

- **Votre Nom** - [@12KOFFI](https://github.com/12KOFFI)

## Remerciements

- Toutes les personnes qui ont contribué à ce projet
- Les bibliothèques et frameworks utilisés
