# Social Network - Troubleshooting Guide

## Common Issues and Solutions

### 1. Database Connection Issues

**Problem**: "Connection error" or database connection fails

**Solutions**:
- Check if MySQL server is running
- Verify database credentials in `config/database.php`
- Ensure database `social_network` exists
- Run `init_database.php` to create tables

### 2. File Not Found Errors

**Problem**: "Failed to open stream" or "No such file or directory"

**Solutions**:
- Ensure you're running from the correct directory
- Check file paths in require statements
- Verify all files are in the correct locations

### 3. Permission Issues

**Problem**: Cannot upload files or create directories

**Solutions**:
- Make `uploads/` directory writable: `chmod 755 uploads/`
- Make subdirectories writable: `chmod 755 uploads/profiles uploads/posts`
- On Windows, ensure the web server has write permissions

### 4. Session Issues

**Problem**: Login doesn't work or sessions don't persist

**Solutions**:
- Ensure `session_start()` is called before any output
- Check if sessions are enabled in PHP
- Verify session directory is writable

### 5. AJAX Not Working

**Problem**: Posts, likes, or other AJAX features don't work

**Solutions**:
- Check browser console for JavaScript errors
- Verify jQuery is loading correctly
- Ensure API endpoints return proper JSON
- Check file paths in AJAX requests

### 6. Default Profile Picture Issues

**Problem**: Default profile picture doesn't display

**Solutions**:
- Ensure `uploads/profiles/default.png` exists
- Check file permissions on the file
- Verify the file is a valid image

## Step-by-Step Setup

### 1. Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE social_network;
exit

# Import schema
mysql -u root -p social_network < sql/schema.sql
```

### 2. File Permissions (Linux/Mac)
```bash
chmod 755 uploads/
chmod 755 uploads/profiles/
chmod 755 uploads/posts/
```

### 3. Web Server Configuration
- Ensure PHP 7.4+ is installed
- Enable PDO and PDO_MySQL extensions
- Set proper document root

## Debugging Steps

### 1. Run Debug Script
Visit `debug.php` to check:
- PHP version and extensions
- Database connection
- File permissions
- Required files

### 2. Check Error Logs
- PHP error log
- Web server error log
- Browser console for JavaScript errors

### 3. Test Individual Components
- Test database connection separately
- Test file uploads
- Test AJAX endpoints individually

## Quick Fixes

### Fix Database Connection
Update `config/database.php`:
```php
private $host = 'localhost';
private $db_name = 'social_network';
private $username = 'your_username';
private $password = 'your_password';
```

### Fix File Paths
Ensure all require statements use absolute paths:
```php
require_once __DIR__ . '/../config/database.php';
```

### Fix Upload Issues
Create upload directories and set permissions:
```bash
mkdir -p uploads/profiles uploads/posts
chmod 755 uploads/profiles uploads/posts
```

## Testing the Application

### 1. Basic Functionality Test
1. Visit `index.php`
2. Try to sign up with a new account
3. Login with the new account
4. Create a post
5. Test like/dislike functionality

### 2. File Upload Test
1. Try uploading a profile picture
2. Try uploading a post image
3. Check if files are saved in uploads directory

### 3. AJAX Test
1. Open browser developer tools
2. Try creating a post
3. Try liking a post
4. Check for any JavaScript errors

## Common Error Messages

### "Call to undefined function"
- Missing PHP extensions
- Check if required extensions are loaded

### "Access denied for user"
- Wrong database credentials
- User doesn't have proper permissions

### "Table doesn't exist"
- Database schema not imported
- Run `init_database.php`

### "Permission denied"
- File permission issues
- Check directory permissions

## Getting Help

If you're still having issues:

1. Run `debug.php` and check all outputs
2. Check PHP and web server error logs
3. Verify all requirements are met
4. Test with a simple PHP script first
5. Check browser console for JavaScript errors

## Requirements Checklist

- [ ] PHP 7.4 or higher
- [ ] MySQL 5.7 or higher
- [ ] PDO extension enabled
- [ ] PDO_MySQL extension enabled
- [ ] Web server (Apache/Nginx)
- [ ] Write permissions on uploads directory
- [ ] Database created and schema imported





