@echo off
echo Installing OSA Membership System dependencies...
cd /d "%~dp0"

set PHP=C:\wamp64\bin\php\php8.3.28\php.exe
if not exist "%PHP%" (
    echo PHP not found at %PHP%
    echo Please edit install.bat with your WAMP PHP path.
    pause
    exit /b 1
)

if not exist composer.phar (
    echo Downloading Composer...
    "%PHP%" -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    "%PHP%" composer-setup.php --quiet
    del composer-setup.php 2>nul
)

echo Running composer install...
"%PHP%" composer.phar install --no-interaction

if exist vendor\autoload.php (
    echo.
    echo SUCCESS! Dependencies installed.
    echo Open: http://localhost/osa/public/apply
) else (
    echo.
    echo FAILED. Check errors above.
)

pause
