<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OJT Portal - Sign In</title>
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
        }
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }
        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .login-image {
            background: linear-gradient(45deg, #3498db, #2ecc71);
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-control {
            border-radius: 10px;
            padding: 0.8rem 1rem;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            border-color: #3498db;
        }
        .btn-primary {
            background: linear-gradient(45deg, #3498db, #2ecc71);
            border: none;
            border-radius: 10px;
            padding: 0.8rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row vh-100 align-items-center justify-content-center">
            <div class="col-12 col-lg-8 col-xl-6">
                <div class="login-container">
                    <div class="row g-0">
                        <div class="col-12 col-md-5 login-image text-white">
                            <div class="text-center">
                                <i class="bi bi-person-workspace display-1 mb-3"></i>
                                <h4>OJT Portal</h4>
                                <p class="small">Track your progress seamlessly</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-7 p-4 p-md-5">
                            <h2 class="mb-4">Welcome Back!</h2>
                            <?php 
                            if (isset($_SESSION['error_message'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php echo $_SESSION['error_message']; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php unset($_SESSION['error_message']); ?>
                            <?php endif; ?>
                            <form method="POST" action="../actions/login.php">
                                <div class="mb-4">
                                    <label class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <input type="email" class="form-control border-start-0" placeholder="Enter your email" name="email" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" class="form-control border-start-0" placeholder="Enter your password" name="password" required>
                                    </div>
                                </div>
                                <div class="mb-4 d-flex justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember">
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <a href="#" class="text-decoration-none">Forgot Password?</a>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mb-3">Sign In</button>
                                <p class="text-center mb-0">Don't have an account? <a href="signup.php" class="text-decoration-none">Sign Up</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
