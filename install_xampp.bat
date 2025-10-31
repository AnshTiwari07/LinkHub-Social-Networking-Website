@echo off
echo ========================================
echo    XAMPP Installation Helper
echo ========================================
echo.

echo Step 1: Downloading XAMPP...
echo Please download XAMPP from: https://www.apachefriends.org/download.html
echo.
echo Step 2: After downloading, run the installer as Administrator
echo.
echo Step 3: During installation, select these components:
echo    - Apache
echo    - MySQL  
echo    - PHP
echo    - phpMyAdmin
echo.
echo Step 4: After installation, follow these steps:
echo.

echo Creating project setup script...
echo @echo off > setup_project.bat
echo echo Starting XAMPP services... >> setup_project.bat
echo cd /d "C:\xampp" >> setup_project.bat
echo xampp-control.exe >> setup_project.bat
echo. >> setup_project.bat
echo echo Copying project files... >> setup_project.bat
echo xcopy /E /I /Y "%~dp0" "C:\xampp\htdocs\Webkul\" >> setup_project.bat
echo. >> setup_project.bat
echo echo Opening browser... >> setup_project.bat
echo start http://localhost/Webkul/simple_index.php >> setup_project.bat
echo. >> setup_project.bat
echo echo Setup complete! >> setup_project.bat
echo echo Your project is now available at: http://localhost/Webkul/ >> setup_project.bat
echo pause >> setup_project.bat

echo.
echo ========================================
echo    Next Steps:
echo ========================================
echo.
echo 1. Download XAMPP from the link above
echo 2. Install XAMPP with recommended settings
echo 3. Run setup_project.bat to configure your project
echo 4. Start Apache and MySQL in XAMPP Control Panel
echo 5. Visit http://localhost/Webkul/simple_index.php
echo.
echo ========================================
pause





