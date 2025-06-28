@echo off
echo =========================================
echo    Démarrage de l'environnement Docker
echo    AliExprass E-commerce Symfony
echo =========================================
echo.

REM Vérifier si Docker est installé
docker --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERREUR: Docker n'est pas installé ou n'est pas dans le PATH
    echo Veuillez installer Docker Desktop pour Windows
    pause
    exit /b 1
)

REM Vérifier si le fichier .env existe
if not exist .env (
    echo Configuration de l'environnement...
    if exist docker.env (
        copy docker.env .env
        echo Fichier .env créé à partir de docker.env
    ) else (
        echo ATTENTION: Aucun fichier de configuration trouvé
        echo Veuillez créer un fichier .env avec la configuration DATABASE_URL
    )
)

echo Arrêt des conteneurs existants...
docker-compose down

echo Construction et démarrage des conteneurs...
docker-compose up -d --build

echo.
echo Attente du démarrage des services...
timeout /t 10 /nobreak >nul

echo.
echo Installation des dépendances Composer...
docker-compose exec php composer install

echo.
echo Nettoyage du cache Symfony...
docker-compose exec php php bin/console cache:clear

echo.
echo Migration de la base de données...
docker-compose exec php php bin/console doctrine:database:create --if-not-exists
docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction

echo.
echo =========================================
echo    Environnement Docker prêt !
echo =========================================
echo.
echo URLs disponibles:
echo - Application web: http://localhost:8000
echo - Emails (MailCatcher): http://localhost:1080
echo - Base de données MySQL: localhost:3306
echo.
echo Pour voir les logs: docker-compose logs -f
echo Pour arrêter: docker-compose down
echo.
pause 