# Authentication System Setup Guide

This guide will help you set up and use the complete authentication system (login, signup, forgot password) for your PHP Learning Project.

## 📋 Files Created

### Backend Files
- **`src/Auth.php`** - Authentication class handling all auth logic
- **`src/Database.php`** - Updated with insert(), update(), delete() methods
- **`src/config.php`** - Updated with session configuration
- **`database.sql`** - Database schema for users and password_resets tables

### Frontend Pages
- **`public/login.php`** - User login page
- **`public/signup.php`** - User registration page
- **`public/forgot-password.php`** - Password reset request page
- **`public/reset-password.php`** - Password reset form page
- **`public/dashboard.php`** - Protected dashboard (requires login)
- **`public/index.php`** - Updated to redirect to login/dashboard

## 🚀 Setup Steps

### Step 1: Create the Database
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database (e.g., `php_learning`)
3. Open the SQL tab and paste the contents of `database.sql`
4. Click "Go" to create the tables

### Step 2: Update Configuration
Edit `src/config.php` and update database credentials:
```php
define('DB_USERNAME', 'root');      // Your MySQL username
define('DB_PASSWORD', '');          // Your MySQL password
define('DB_NAME', 'php_learning');  // Your database name
```

## 🔐 Feature Overview

### 1. User Registration (Signup)
- **URL**: `/php-learning-project/public/signup.php`
- **Features**:
  - Email validation
  - Password strength validation (minimum 8 characters)
  - Password confirmation check
  - Duplicate email prevention
  - Password hashing with bcrypt
  - User-friendly error messages

### 2. User Login
- **URL**: `/php-learning-project/public/login.php`
- **Features**:
  - Email and password validation
  - Session management
  - Redirect to dashboard on success
  - Secure password verification
  - Remember login via session

### 3. Forgot Password
- **URL**: `/php-learning-project/public/forgot-password.php`
- **Features**:
  - Email verification
  - Secure token generation
  - Token expiration (1 hour)
  - User privacy (doesn't reveal if email exists)
  - Testing token display for development

### 4. Password Reset
- **URL**: `/php-learning-project/public/reset-password.php?token={TOKEN}`
- **Features**:
  - Token validation
  - Expiration checking
  - Password strength validation
  - Secure password update
  - Automatic token cleanup
  - Redirect to login after reset

### 5. Dashboard
- **URL**: `/php-learning-project/public/dashboard.php`
- **Features**:
  - Protected page (requires login)
  - User information display
  - Logout functionality
  - Session management

## 📝 User Flow Diagram

```
Start
  ↓
[Index Page]
  ↓
  ├─→ Logged In? → [Dashboard] → Logout → [Login]
  └─→ Not Logged In? → [Login Page]
              ↓
          Login Success? → Yes → [Dashboard]
                      ↓ No
                   Try Again
                      ↓
                   [Signup] or [Forgot Password]
                      ↓
                   [Reset Password] → [Login]
```

## 🔧 API Reference - Auth Class

### Methods Available

```php
$auth = new Auth($database);

// Registration
$result = $auth->signup($email, $password, $confirm_password);
// Returns: ['success' => bool, 'message'|'errors' => string|array]

// Login
$result = $auth->login($email, $password);
// Returns: ['success' => bool, 'message'|'errors' => string|array]

// Check if logged in
$is_logged_in = $auth->isLoggedIn();
// Returns: bool

// Get current user
$user = $auth->getCurrentUser();
// Returns: ['id' => int, 'email' => string, 'created_at' => string]

// Logout
$result = $auth->logout();
// Returns: ['success' => bool, 'message' => string]

// Request password reset
$result = $auth->requestPasswordReset($email);
// Returns: ['success' => bool, 'message' => string, 'token' => string (for testing)]

// Verify reset token
$user_id = $auth->verifyResetToken($token);
// Returns: int (user_id) or false

// Reset password with token
$result = $auth->resetPassword($token, $new_password, $confirm_password);
// Returns: ['success' => bool, 'message'|'errors' => string|array]
```

## 🗄️ Database Schema

### users Table
```sql
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email)
)
```

### password_resets Table
```sql
CREATE TABLE password_resets (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  token VARCHAR(255) UNIQUE NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_token (token),
  INDEX idx_user_id (user_id)
)
```

## 🧪 Testing the System

### Test Signup
1. Go to `http://localhost/php-learning-project/public/signup.php`
2. Enter email: `test@example.com`
3. Enter password: `password123` (minimum 8 characters)
4. Confirm password: `password123`
5. Click "Create Account"

### Test Login
1. Go to `http://localhost/php-learning-project/public/login.php`
2. Enter email: `test@example.com`
3. Enter password: `password123`
4. Click "Login"
5. You should be redirected to dashboard.php

### Test Forgot Password (Development Mode)
1. Go to `http://localhost/php-learning-project/public/forgot-password.php`
2. Enter your registered email
3. A reset token will be displayed (for testing)
4. Copy and use it in the reset-password.php URL

### Test Password Reset
1. Use the token from forgot password
2. Go to `http://localhost/php-learning-project/public/reset-password.php?token={TOKEN}`
3. Enter new password: `newpassword123`
4. Confirm password: `newpassword123`
5. Click "Reset Password"
6. Login with new password

## 🔒 Security Features Implemented

1. **Password Hashing**: bcrypt (PHP's password_hash function)
2. **SQL Injection Prevention**: PDO prepared statements
3. **XSS Prevention**: htmlspecialchars() for output
4. **CSRF Protection**: Session-based (implement token-based for production)
5. **Token Expiration**: Reset tokens expire in 1 hour
6. **Secure Token Generation**: random_bytes(32)
7. **Session Management**: Automatic timeout (configurable)
8. **Password Requirements**: Minimum 8 characters
9. **Email Validation**: Server-side validation
10. **Privacy**: Forgot password doesn't reveal email existence

## 📧 Email Integration (Production)

For production use, implement email sending in `src/Auth.php`:

```php
// In requestPasswordReset() method, add:
$reset_url = BASE_URL . 'reset-password.php?token=' . $token;
// Use PHPMailer or similar to send email with Reset URL to user
```

### Sending email on XAMPP (development)

By default on Windows/XAMPP, PHP's `mail()` may not be configured. Two approaches:

- Quick dev option (uses PHP `mail()` with sendmail):
  1. Locate `sendmail` in your XAMPP installation (e.g. `C:\xampp\sendmail`).
  2. Edit `sendmail\sendmail.ini` with your SMTP provider settings (SMTP server, port, username, password).
  3. Edit `php.ini` (used by Apache) and set the `sendmail_path` or the SMTP settings to point to sendmail. Restart Apache.
  4. With `sendmail` configured, the `mail()` call in `src/Auth.php` will attempt to send the reset link.

- Recommended: Use PHPMailer with SMTP (reliable and easy to configure)
  1. Install Composer if you don't have it: https://getcomposer.org/download/
  2. From project root run:
     ```bash
     composer require phpmailer/phpmailer
     ```
  3. Update `src/Auth.php` to send via SMTP using PHPMailer. Example:
     ```php
     use PHPMailer\PHPMailer\PHPMailer;
     use PHPMailer\PHPMailer\Exception;

     $mail = new PHPMailer(true);
     try {
         $mail->isSMTP();
         $mail->Host = 'smtp.example.com';
         $mail->SMTPAuth = true;
         $mail->Username = 'smtp_user@example.com';
         $mail->Password = 'smtp_password';
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
         $mail->Port = 587;

         $mail->setFrom('no-reply@example.com', 'Your App');
         $mail->addAddress($email);
         $mail->isHTML(false);
         $mail->Subject = 'Password reset request';
         $mail->Body = "Click to reset: " . $reset_url;
         $mail->send();
     } catch (Exception $e) {
         error_log('PHPMailer error: ' . $mail->ErrorInfo);
     }
     ```

  4. PHPMailer handles SMTP authentication and TLS, so you can use Gmail, SendGrid, Mailgun, or your own SMTP server.

Security note: never commit SMTP credentials to the repository. Store them in environment variables or a protected config file outside version control.

## 🐛 Troubleshooting

### "Connection error" on login page
- Check database credentials in `src/config.php`
- Ensure MySQL server is running
- Verify database name exists

### "Email already registered" error on signup
- Clear your test database or use a different email
- Check the `users` table in phpMyAdmin

### Reset token not working
- Ensure you're using the token from the same session
- Check that token hasn't expired (1 hour)
- Verify `password_resets` table exists

### Session not persisting
- Check that Apache/PHP session path has write permissions
- Verify `php.ini` session settings

## 📚 Next Steps - Enhancement Ideas

1. **Email Notifications**: Send account confirmation emails
2. **Two-Factor Authentication**: Add SMS or authenticator app support
3. **User Profile**: Add profile management page
4. **Activity Logging**: Track login history
5. **Account Deletion**: Allow users to delete their account
6. **Email Verification**: Require email confirmation on signup
7. **Password Strength Meter**: Visual feedback on password strength
8. **Social Login**: Add Google/GitHub authentication
9. **Rate Limiting**: Prevent brute force attacks
10. **Remember Me**: Persistent login option

## 📞 Support

For issues or questions:
1. Check the `database.sql` file to ensure tables exist
2. Verify database credentials in `config.php`
3. Check browser console for JavaScript errors
4. Review Apache error logs in `c:\xampp\apache\logs\error.log`
5. Check PHP error logs in `c:\xampp\php\errorlog.txt`

---

Happy coding! 🚀
