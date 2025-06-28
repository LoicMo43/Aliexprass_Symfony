@echo off
echo =========================================
echo    Arrêt de l'environnement Docker
echo    AliExprass E-commerce Symfony  
echo =========================================
echo.

echo Arrêt des conteneurs...
docker-compose down

echo.
echo Nettoyage des images inutilisées (optionnel)...
set /p cleanup="Voulez-vous nettoyer les images Docker inutilisées ? (y/N): "
if /i "%cleanup%"=="y" (
    docker system prune -f
    echo Nettoyage terminé !
)

echo.
echo Environnement Docker arrêté avec succès !
echo.
pause 