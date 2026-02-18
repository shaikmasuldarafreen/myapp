<?php
require_once '../src/config.php';
require_once '../src/Database.php';
require_once '../src/Auth.php';

$db = new Database();
$auth = new Auth($db);

$token = $_GET['token'] ?? $_SESSION['reset_token'] ?? '';
$errors = [];
$success = false;
$invalid_token = false;

// Verify token exists and is valid
if (empty($token)) {
    $invalid_token = true;
} else {
    $user_id = $auth->verifyResetToken($token);
    if (!$user_id) {
        $invalid_token = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$invalid_token) {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $result = $auth->resetPassword($token, $new_password, $confirm_password);

    if ($result['success']) {
        $success = true;
        unset($_SESSION['reset_token']);
    } else {
        $errors = $result['errors'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - PHP App</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .auth-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .auth-container h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        .password-requirements {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn:active {
            transform: translateY(0);
        }
        .links {
            margin-top: 20px;
            text-align: center;
        }
        .links a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            display: block;
            margin: 5px 0;
        }
        .links a:hover {
            text-decoration: underline;
        }
        .error-box {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .success-box {
            background: #efe;
            border: 1px solid #cfc;
            color: #3c3;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1>Reset Password</h1>

        <?php if ($invalid_token): ?>
            <div class="error-box">
                Invalid or expired reset token. Please <a href="forgot-password.php" style="color: inherit; text-decoration: underline;">request a new one</a>.
            </div>
            <div class="links">
                <a href="login.php">Back to login</a>
            </div>
        <?php elseif ($success): ?>
            <div class="success-box">
                Password reset successfully! <a href="login.php" style="color: inherit; text-decoration: underline;">Click here to login</a>
            </div>
        <?php else: ?>
            <?php if (!empty($errors)): ?>
                <div class="error-box">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="reset-password.php">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input 
                        type="password" 
                        id="new_password" 
                        name="new_password" 
                        required
                        minlength="8"
                    >
                    <div class="password-requirements">
                        Minimum 8 characters required
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        required
                        minlength="8"
                    >
                </div>

                <button type="submit" class="btn">Reset Password</button>
            </form>

            <div class="links">
                <a href="login.php">Back to login</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
