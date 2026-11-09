<?php
require_once '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hash);

    if ($stmt->num_rows === 1 && $stmt->fetch() && password_verify($password, $hash)) {
        $_SESSION['admin_id'] = $id;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass-bg: rgba(255, 255, 255, 0.07);
            --glass-border: rgba(255, 255, 255, 0.15);
        }

        body {
            min-height: 100vh;
            background: var(--gradient);
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
            color: #fff;
        }

        .login-container {
            max-width: 420px;
            width: 100%;
            padding: 2rem;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2.5rem;
            animation: fadeInDown 1s ease-out;
        }

        .logo {
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transition: transform 0.4s ease;
        }

        .logo:hover {
            transform: scale(1.08) rotate(5deg);
        }

        .logo i {
            font-size: 2.8rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.25);
            animation: fadeInUp 0.8s ease-out;
        }

        .card-body {
            padding: 2.5rem 2rem;
        }

        h3 {
            font-weight: 700;
            margin-bottom: 1.8rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-label {
            color: rgba(255,255,255,0.9);
            font-weight: 500;
        }

        .form-control {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.25);
            color: white;
            border-radius: 12px;
            padding: 0.75rem 1.25rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255,255,255,0.18);
            border-color: #a78bfa;
            box-shadow: 0 0 0 0.25rem rgba(167,139,250,0.25);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255,255,255,0.5);
        }

        .btn-primary {
            background: var(--gradient);
            border: none;
            border-radius: 12px;
            padding: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102,126,234,0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102,126,234,0.5);
        }

        .alert {
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 576px) {
            .card-body { padding: 2rem 1.5rem; }
            .logo { width: 80px; height: 80px; }
            .logo i { font-size: 2.4rem; }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="logo-container">
        <div class="logo">
            <i class="bi bi-box-seam"></i>
        </div>
        <h4 class="fw-bold mb-1">CSBM Campus</h4>
        <p class="text-white-75 small">Inventory System</p>
    </div>

    <div class="card">
        <div class="card-body">

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-4">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required autofocus placeholder="Enter username">
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold">
                    Sign In
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>