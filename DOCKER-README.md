# AliExprass - Configuration Docker

## 🐳 Environnement Docker pour Symfony E-commerce

Cette configuration Docker remplace Laragon et vous permet de faire tourner le projet AliExprass dans des conteneurs isolés.

## 📋 Prérequis

- **Docker Desktop** : [Télécharger ici](https://www.docker.com/products/docker-desktop/)
- **Git** (pour cloner le projet)

## 🚀 Démarrage rapide

### 1. Installation automatique (Windows)
```bash
# Double-cliquez simplement sur le fichier :
start-docker.bat
```

### 2. Installation manuelle

#### Étape 1 : Configuration de l'environnement
```bash
# Copiez le fichier de configuration
copy docker.env .env
```

#### Étape 2 : Construction et démarrage
```bash
# Construction des images et démarrage des conteneurs
docker-compose up -d --build

# Installation des dépendances
docker-compose exec php composer install

# Préparation de la base de données
docker-compose exec php php bin/console doctrine:database:create --if-not-exists
docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction

# Nettoyage du cache
docker-compose exec php php bin/console cache:clear
```

## 🌐 URLs disponibles

- **Application web** : http://localhost:8000
- **Interface emails** (MailCatcher) : http://localhost:1080  
- **Base de données MySQL** : localhost:3306

## 🛠️ Services Docker

| Service | Description | Port |
|---------|-------------|------|
| `nginx` | Serveur web | 8000 |
| `php` | PHP 8.3 + Symfony | 9000 (interne) |
| `database` | MySQL 8.0 | 3306 |
| `mailcatcher` | Interface emails | 1080 |

## 📁 Structure des fichiers

```
├── docker/
│   └── nginx/
│       └── nginx.conf          # Configuration Nginx
├── docker-compose.yml          # Configuration des services
├── Dockerfile                  # Image PHP personnalisée
├── .dockerignore              # Fichiers exclus du build
├── docker.env                 # Template de configuration
├── start-docker.bat           # Script de démarrage Windows
└── stop-docker.bat           # Script d'arrêt Windows
```

## 🔧 Commandes utiles

### Gestion des conteneurs
```bash
# Démarrer les services
docker-compose up -d

# Arrêter les services  
docker-compose down

# Voir les logs en temps réel
docker-compose logs -f

# Redémarrer un service spécifique
docker-compose restart php
```

### Accès aux conteneurs
```bash
# Shell PHP (pour Symfony CLI, Composer, etc.)
docker-compose exec php bash

# Shell MySQL
docker-compose exec database mysql -u symfony -p aliexprass

# Voir les processus
docker-compose ps
```

### Base de données
```bash
# Créer la base de données
docker-compose exec php php bin/console doctrine:database:create

# Lancer les migrations
docker-compose exec php php bin/console doctrine:migrations:migrate

# Charger les fixtures (si disponibles)
docker-compose exec php php bin/console doctrine:fixtures:load
```

### Symfony
```bash
# Nettoyage du cache
docker-compose exec php php bin/console cache:clear

# Installation des dépendances
docker-compose exec php composer install

# Mise à jour des dépendances
docker-compose exec php composer update
```

## 🔒 Configuration

### Variables d'environnement (.env)
```env
# Base de données (automatique avec Docker)
DATABASE_URL="mysql://symfony:symfony123@database:3306/aliexprass?serverVersion=8.0"

# Mailer (MailCatcher)
MAILER_DSN=smtp://mailcatcher:1025

# Stripe (à personnaliser)
STRIPE_PUBLIC_KEY=pk_test_your_key
STRIPE_SECRET_KEY=sk_test_your_key
```

## 🚨 Résolution de problèmes

### Le port 8000 est occupé
```bash
# Changer le port dans docker-compose.yml
ports:
  - "8080:80"  # Utiliser le port 8080 au lieu de 8000
```

### Erreur de permissions
```bash
# Sur Linux/Mac, ajustez les permissions
sudo chown -R $USER:$USER var/
```

### Problème de base de données
```bash
# Recréer la base de données
docker-compose exec php php bin/console doctrine:database:drop --force
docker-compose exec php php bin/console doctrine:database:create
docker-compose exec php php bin/console doctrine:migrations:migrate
```

### Reset complet
```bash
# Arrêt et nettoyage complet
docker-compose down -v
docker-compose up -d --build
```

## 📊 Monitoring et logs

```bash
# Voir l'utilisation des ressources
docker stats

# Logs d'un service spécifique
docker-compose logs nginx
docker-compose logs php
docker-compose logs database

# Suivre les logs en temps réel
docker-compose logs -f --tail=100
```

## 🎯 Avantages par rapport à Laragon

- ✅ **Isolation** : Chaque service dans son conteneur
- ✅ **Portabilité** : Fonctionne sur Windows, Linux, Mac
- ✅ **Reproductibilité** : Même environnement pour toute l'équipe
- ✅ **Versions fixes** : PHP 8.3, MySQL 8.0, Nginx stable
- ✅ **Simplicité** : Un seul script pour tout démarrer
- ✅ **Monitoring** : Interface MailCatcher intégrée

## 🆘 Support

En cas de problème :
1. Vérifiez que Docker Desktop est démarré
2. Consultez les logs avec `docker-compose logs -f`
3. Tentez un reset complet si nécessaire

---

**Note** : Cette configuration remplace complètement Laragon. Vous pouvez désinstaller Laragon une fois que Docker fonctionne correctement. 