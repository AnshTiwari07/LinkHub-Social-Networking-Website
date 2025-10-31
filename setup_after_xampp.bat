@echo off
echo ========================================
echo    Post-XAMPP Setup for Social Network
echo ========================================
echo.

echo This script will help you set up your project after installing XAMPP.
echo.

echo Step 1: Starting XAMPP Control Panel...
echo Please start Apache and MySQL services in XAMPP Control Panel.
echo.
pause

echo Step 2: Copying project files...
if not exist "C:\xampp\htdocs\Webkul" mkdir "C:\xampp\htdocs\Webkul"
xcopy /E /I /Y "%~dp0*" "C:\xampp\htdocs\Webkul\"

echo.
echo Step 3: Setting up database...
echo Please open phpMyAdmin at: http://localhost/phpmyadmin
echo Create a new database called 'social_network'
echo Import the schema from: C:\xampp\htdocs\Webkul\sql\schema.sql
echo.

echo Step 4: Testing your installation...
echo Opening project test page...
start http://localhost/Webkul/simple_index.php

echo.
echo ========================================
echo    Setup Complete!
echo ========================================
echo.
echo Your project is now available at:
echo http://localhost/Webkul/
echo.
echo Test pages:
echo - http://localhost/Webkul/simple_index.php (System test)
echo - http://localhost/Webkul/index.html (HTML version)
echo - http://localhost/Webkul/index.php (PHP version)
echo.
echo If you see any errors, run:
echo http://localhost/Webkul/debug.php
echo.
pause





