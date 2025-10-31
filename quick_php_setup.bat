@echo off
echo ========================================
echo    Quick PHP Setup for Social Network
echo ========================================
echo.

echo This script will help you set up PHP for your project.
echo.

echo Checking current directory...
if not exist "index.php" (
    echo ERROR: Please run this script from your project directory
    echo Make sure you're in the folder containing index.php
    pause
    exit /b 1
)

echo ✅ Project files found
echo.

echo ========================================
echo    Installation Options:
echo ========================================
echo.
echo 1. XAMPP (Recommended for beginners)
echo 2. WAMP Server
echo 3. Manual PHP installation
echo 4. Check current PHP installation
echo.

set /p choice="Enter your choice (1-4): "

if "%choice%"=="1" goto xampp
if "%choice%"=="2" goto wamp
if "%choice%"=="3" goto manual
if "%choice%"=="4" goto check
goto invalid

:xampp
echo.
echo ========================================
echo    XAMPP Installation
echo ========================================
echo.
echo 1. Download XAMPP from: https://www.apachefriends.org/download.html
echo 2. Install XAMPP with these components:
echo    - Apache
echo    - MySQL
echo    - PHP
echo    - phpMyAdmin
echo.
echo 3. After installation:
echo    - Start XAMPP Control Panel
echo    - Start Apache and MySQL services
echo    - Copy this project to C:\xampp\htdocs\Webkul\
echo    - Visit: http://localhost/Webkul/simple_index.php
echo.
goto end

:wamp
echo.
echo ========================================
echo    WAMP Server Installation
echo ========================================
echo.
echo 1. Download WAMP from: https://www.wampserver.com/
echo 2. Install WAMP Server
echo 3. Start WAMP Server
echo 4. Copy this project to C:\wamp64\www\Webkul\
echo 5. Visit: http://localhost/Webkul/simple_index.php
echo.
goto end

:manual
echo.
echo ========================================
echo    Manual PHP Installation
echo ========================================
echo.
echo 1. Download PHP from: https://windows.php.net/download/
echo 2. Extract to C:\php\
echo 3. Download Apache from: https://httpd.apache.org/
echo 4. Configure Apache to use PHP
echo 5. Install MySQL separately
echo.
echo This is more complex - consider using XAMPP instead.
echo.
goto end

:check
echo.
echo ========================================
echo    Checking Current Installation
echo ========================================
echo.
echo Opening PHP installation checker...
start check_php_installation.php
echo.
echo If PHP is working, you should see a web page with installation details.
echo If not, you need to install PHP first.
echo.
goto end

:invalid
echo.
echo Invalid choice. Please run the script again and choose 1-4.
echo.
goto end

:end
echo.
echo ========================================
echo    Quick Test Commands:
echo ========================================
echo.
echo After setting up PHP, test with these commands:
echo.
echo 1. Check PHP: php -v
echo 2. Test project: start simple_index.php
echo 3. Setup database: start init_database.php
echo.
echo ========================================
pause





