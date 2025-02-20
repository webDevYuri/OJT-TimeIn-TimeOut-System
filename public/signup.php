<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OJT Portal - Sign Up</title>
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .signup-container {
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
        .signup-image {
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
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="signup-container">
                    <div class="row g-0">
                        <div class="col-12 col-md-4 signup-image text-white">
                            <div class="text-center">
                                <i class="bi bi-person-plus display-1 mb-3"></i>
                                <h4>Join OJT Portal</h4>
                                <p class="small">Start tracking your progress today</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-8 p-4 p-md-5">
                            <h2 class="mb-4">Create Account</h2>
                            <?php
                                if (isset($_SESSION['error_message'])) {
                                    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                                    unset($_SESSION['error_message']);  // Clear the error message after showing it
                                }
                                
                                if (isset($_SESSION['success_message'])) {
                                    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                                    unset($_SESSION['success_message']);  
                                }
                            ?>
                            <form action="../actions/register.php" method="POST" class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control" placeholder="Enter first name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" placeholder="Enter last name" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Company/Organization</label>
                                    <input type="text" name="company" class="form-control" placeholder="Enter company name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Required OJT Hours</label>
                                    <input type="number" name="ojt_hours" class="form-control" placeholder="Enter total hours" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password" required>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input type="checkbox" name="terms" class="form-check-input" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100 mb-3">Create Account</button>
                                    <p class="text-center mb-0">Already have an account? <a href="index.php" class="text-decoration-none">Sign In</a></p>
                                </div>
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
