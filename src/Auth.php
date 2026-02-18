<?php

class Auth {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Register a new user
     */
    public function signup($email, $password, $confirm_password) {
        $errors = [];

        // Validation
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters";
        }

        if ($password !== $confirm_password) {
            $errors[] = "Passwords do not match";
        }

        // Check if user already exists
        if (empty($errors)) {
            $result = $this->db->query("SELECT id FROM users WHERE email = ?", [$email]);
            if (!empty($result)) {
                $errors[] = "Email already registered";
            }
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Hash password and insert user
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $inserted = $this->db->insert('users', [
            'email' => $email,
            'password' => $hashed_password,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if ($inserted) {
            return ['success' => true, 'message' => 'Registration successful. Please login.'];
        } else {
            return ['success' => false, 'errors' => ['Database error. Please try again.']];
        }
    }

    /**
     * Login user
     */
    public function login($email, $password) {
        $errors = [];

        if (empty($email)) {
            $errors[] = "Email is required";
        }
        if (empty($password)) {
            $errors[] = "Password is required";
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $result = $this->db->query("SELECT id, email, password FROM users WHERE email = ?", [$email]);
        
        if (empty($result)) {
            return ['success' => false, 'errors' => ['Invalid email or password']];
        }

        $user = $result[0];

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'errors' => ['Invalid email or password']];
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];

        return ['success' => true, 'message' => 'Login successful'];
    }

    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get current user info
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email']
        ];
    }

    /**
     * Logout user
     */
    public function logout() {
        session_destroy();
        return ['success' => true, 'message' => 'Logged out successfully'];
    }

    /**
     * Request password reset
     */
    public function requestPasswordReset($email) {
        $errors = [];

        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $result = $this->db->query("SELECT id FROM users WHERE email = ?", [$email]);
        
        if (empty($result)) {
            // Don't reveal if email exists for security
            return ['success' => true, 'message' => 'If email exists, reset link will be sent'];
        }

        $user_id = $result[0]['id'];
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry

        $this->db->insert('password_resets', [
            'user_id' => $user_id,
            'token' => $token,
            'expires_at' => $expires
        ]);

        // In production, send email with reset link
        // For now, return token for testing
        return [
            'success' => true,
            'message' => 'If email exists, reset link will be sent',
            'token' => $token // Remove in production after implementing email
        ];
    }

    /**
     * Verify reset token
     */
    public function verifyResetToken($token) {
        $result = $this->db->query(
            "SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW() LIMIT 1",
            [$token]
        );

        return !empty($result) ? $result[0]['user_id'] : false;
    }

    /**
     * Reset password with token
     */
    public function resetPassword($token, $new_password, $confirm_password) {
        $errors = [];

        if (empty($new_password)) {
            $errors[] = "Password is required";
        } elseif (strlen($new_password) < 8) {
            $errors[] = "Password must be at least 8 characters";
        }

        if ($new_password !== $confirm_password) {
            $errors[] = "Passwords do not match";
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $user_id = $this->verifyResetToken($token);
        
        if (!$user_id) {
            return ['success' => false, 'errors' => ['Invalid or expired token']];
        }

        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $this->db->update('users', ['password' => $hashed_password], "id = ?", [$user_id]);
        $this->db->delete('password_resets', "user_id = ?", [$user_id]);

        return ['success' => true, 'message' => 'Password reset successful. Please login.'];
    }
}
?>
