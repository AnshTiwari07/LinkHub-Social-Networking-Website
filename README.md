# Social Network System

A simple social network system built with PHP, jQuery, MySQL, HTML, and CSS following Object-Oriented Programming principles.

## Features

### User Management
- **Sign Up**: Users can register with full name, email, password, age, and profile picture
- **Login**: Secure authentication with email and password
- **Profile Management**: Users can view and edit their profile information
- **Secure Password Storage**: Passwords are hashed using PHP's password_hash() function

### Post Management
- **Create Posts**: Users can add posts with descriptions and optional images
- **View Posts**: Display all user posts with timestamps
- **Delete Posts**: Users can remove their own posts
- **Like/Dislike System**: Users can like or dislike posts with real-time updates

### Technical Features
- **Object-Oriented Programming**: Clean PHP classes for User and Post management
- **AJAX Functionality**: Real-time updates without page reload
- **Client-side Validation**: jQuery validation for better user experience
- **Server-side Validation**: PHP validation for security
- **File Upload Security**: Secure file upload with type and size validation
- **Responsive Design**: Mobile-friendly interface
- **Database Security**: Prepared statements to prevent SQL injection

## Installation

1. **Database Setup**:
   - Create a MySQL database named `social_network`
   - Import the schema from `sql/schema.sql`
   - Update database credentials in `config/database.php`

2. **File Structure**:
   ```
   ├── api/
   │   ├── add_post.php
   │   ├── delete_post.php
   │   └── react_post.php
   ├── assets/
   │   ├── css/
   │   │   └── style.css
   │   └── js/
   │       ├── validation.js
   │       └── profile.js
   ├── classes/
   │   ├── User.php
   │   └── Post.php
   ├── config/
   │   └── database.php
   ├── sql/
   │   └── schema.sql
   ├── uploads/
   │   ├── profiles/
   │   └── posts/
   ├── index.php
   ├── login.php
   ├── logout.php
   ├── profile.php
   └── signup.php
   ```

3. **Web Server Setup**:
   - Place files in your web server directory (e.g., htdocs for XAMPP)
   - Ensure PHP 7.4+ is installed
   - Enable MySQL extension

## Usage

1. **Access the Application**:
   - Navigate to `http://localhost/your-project-folder/`
   - The system will redirect to login or profile page based on authentication status

2. **User Registration**:
   - Click "Sign up here" on the login page
   - Fill in all required fields
   - Upload a profile picture (optional)
   - Submit the form

3. **User Login**:
   - Enter email and password
   - Click "Login"

4. **Profile Management**:
   - View profile information
   - Click "Edit Profile" to modify details
   - Upload new profile picture

5. **Post Management**:
   - Click "Add New Post" to create posts
   - Add description and optional image
   - View all your posts
   - Like/dislike posts
   - Delete your own posts

## Database Schema

### Users Table
- `id`: Primary key
- `full_name`: User's full name
- `email`: Unique email address
- `password`: Hashed password
- `age`: User's age
- `profile_picture`: Path to profile image
- `created_at`: Registration timestamp
- `updated_at`: Last update timestamp

### Posts Table
- `id`: Primary key
- `user_id`: Foreign key to users table
- `description`: Post content
- `image`: Path to post image (optional)
- `likes`: Number of likes
- `dislikes`: Number of dislikes
- `created_at`: Post creation timestamp
- `updated_at`: Last update timestamp

### Post Reactions Table
- `id`: Primary key
- `post_id`: Foreign key to posts table
- `user_id`: Foreign key to users table
- `reaction_type`: 'like' or 'dislike'
- `created_at`: Reaction timestamp

## Security Features

- **Password Hashing**: Uses PHP's password_hash() with bcrypt
- **SQL Injection Prevention**: All queries use prepared statements
- **File Upload Security**: Validates file types and sizes
- **Input Sanitization**: All user inputs are sanitized
- **Session Management**: Secure session handling
- **XSS Prevention**: HTML entities are escaped

## Browser Compatibility

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- jQuery 3.6.0 (included via CDN)

## License

This project is created for educational purposes as part of a coding assignment.





