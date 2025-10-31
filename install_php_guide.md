# PHP Installation Guide for Windows

## Option 1: XAMPP (Recommended for Beginners)

### Download and Install XAMPP
1. Go to https://www.apachefriends.org/download.html
2. Download XAMPP for Windows (PHP 8.2+ recommended)
3. Run the installer as Administrator
4. Select components: Apache, MySQL, PHP, phpMyAdmin
5. Install to default location (usually C:\xampp)

### Configure XAMPP
1. Start XAMPP Control Panel
2. Start Apache and MySQL services
3. Open browser and go to http://localhost
4. You should see XAMPP dashboard

### Move Your Project
1. Copy your project folder to `C:\xampp\htdocs\`
2. Access your project at: `http://localhost/Webkul/`

## Option 2: WAMP Server

### Download and Install WAMP
1. Go to https://www.wampserver.com/
2. Download WAMP Server (64-bit recommended)
3. Install with default settings
4. Start WAMP Server

### Configure WAMP
1. Right-click WAMP icon in system tray
2. Start All Services
3. Go to http://localhost
4. Copy project to `C:\wamp64\www\`

## Option 3: Manual PHP Installation

### Download PHP
1. Go to https://windows.php.net/download/
2. Download Thread Safe version
3. Extract to `C:\php\`

### Configure PHP
1. Copy `php.ini-development` to `php.ini`
2. Edit `php.ini` and uncomment:
   - extension=pdo_mysql
   - extension=mysqli
   - extension=gd
   - extension=fileinfo

### Install Apache
1. Download Apache from https://httpd.apache.org/
2. Configure httpd.conf to use PHP
3. Add: LoadModule php_module "C:/php/php8apache2_4.dll"
4. Add: AddType application/x-httpd-php .php
5. Add: PHPIniDir "C:/php"

## Option 4: Using Chocolatey (Advanced)

### Install Chocolatey
```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
```

### Install PHP and MySQL
```powershell
choco install php
choco install mysql
choco install apache-httpd
```

## Verification Steps

### 1. Check PHP Installation
Create a file called `phpinfo.php`:
```php
<?php
phpinfo();
?>
```
Visit: http://localhost/phpinfo.php

### 2. Check Required Extensions
Look for these extensions in phpinfo():
- PDO
- PDO_MySQL
- MySQLi
- GD
- Fileinfo

### 3. Test Database Connection
Use the debug.php file to test database connectivity.

## Troubleshooting

### Common Issues:
1. **Port 80 already in use**: Change Apache port to 8080
2. **PHP not working**: Check Apache configuration
3. **Database connection failed**: Start MySQL service
4. **Permission denied**: Run as Administrator

### Quick Fixes:
1. Restart services
2. Check firewall settings
3. Verify file permissions
4. Check error logs

## Recommended Setup for This Project

### For Development:
1. Install XAMPP
2. Start Apache and MySQL
3. Copy project to htdocs
4. Import database schema
5. Test with simple_index.php

### For Production:
1. Use IIS with PHP
2. Configure proper security
3. Set up SSL certificates
4. Configure database properly





