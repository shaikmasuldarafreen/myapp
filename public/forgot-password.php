<?php
require_once '../src/config.php';
require_once '../src/Database.php';
require_once '../src/Auth.php';

$db = new Database();
$auth = new Auth($db);

$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    $result = $auth->requestPasswordReset($email);

    if ($result['success']) {
        $message = $result['message'];
        // In a real app, you would send an email here
        // For testing, display the token
        if (isset($result['token'])) {
            $_SESSION['reset_token'] = $result['token'];
        }
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
    <title>Forgot Password - PHP App</title>
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
            margin-bottom: 15px;
            font-size: 28px;
        }
        .subtitle {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
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
        .token-box {
            background: #eef;
            border: 1px solid #ccf;
            padding: 12px;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 12px;
            word-break: break-all;
            color: #333;
        }
        .token-label {
            color: #666;
            font-weight: 600;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1>Forgot Password</h1>
        <p class="subtitle">Enter your email to receive a password reset link</p>

        <?php if (empty($errors) && !empty($message)): ?>
            <div class="success-box">
                <?php echo htmlspecialchars($message); ?>
            </div>

            <?php if (isset($_SESSION['reset_token'])): ?>
                <div class="token-box">
                    <div class="token-label">For testing, your reset token is:</div>
                    <?php echo htmlspecialchars($_SESSION['reset_token']); ?>
                    <p style="margin-top: 10px; font-size: 11px;">
                        <a href="reset-password.php?token=<?php echo urlencode($_SESSION['reset_token']); ?>" style="color: #667eea; text-decoration: underline;">
                            Click here to reset password
                        </a>
                    </p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($message) || !empty($errors)): ?>
            <form method="POST" action="forgot-password.php">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    >
                </div>

                <button type="submit" class="btn">Send Reset Link</button>
            </form>
        <?php endif; ?>

        <div class="links">
            <a href="login.php">Back to login</a>
            <a href="signup.php">Create new account</a>
        </div>
    </div>
</body>
</html>
