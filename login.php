<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login • E-Learning Platform</title>
    <link rel="icon" href="./assets/images/fav.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        .glass-effect {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(209, 213, 219, 0.3);
        }

        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>

<body class="min-h-screen bg-[#667eea]">
    <?php session_start(); ?>

    <!-- Session Messages Banner -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="fixed top-0 left-0 right-0 z-50 p-4 text-sm text-green-800 bg-green-100 border border-green-200" id="success-banner">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span><?php echo $_SESSION['success_message'];
                            unset($_SESSION['success_message']); ?></span>
                </div>
                <button onclick="document.getElementById('success-banner').style.display='none'" class="text-green-600 hover:text-green-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="fixed top-0 left-0 right-0 z-50 p-4 text-sm text-red-800 bg-red-100 border border-red-200" id="error-banner">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span><?php echo $_SESSION['error'];
                            unset($_SESSION['error']); ?></span>
                </div>
                <button onclick="document.getElementById('error-banner').style.display='none'" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['warning'])): ?>
        <div class="fixed top-0 left-0 right-0 z-50 p-4 text-sm text-yellow-800 bg-yellow-100 border border-yellow-200" id="warning-banner">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span><?php echo $_SESSION['warning'];
                            unset($_SESSION['warning']); ?></span>
                </div>
                <button onclick="document.getElementById('warning-banner').style.display='none'" class="text-yellow-600 hover:text-yellow-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <div class="flex min-h-screen" style="<?php echo (isset($_SESSION['success_message']) || isset($_SESSION['error']) || isset($_SESSION['warning'])) ? 'margin-top: 60px;' : ''; ?>">
        <!-- Left side - Illustration -->
        <div class="relative hidden overflow-hidden bg-white lg:flex lg:w-1/2" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.9)),  url('./assets/images/auth/login.png'); background-size: cover; background-repeat: no-repeat">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-purple-600/10"></div>
            <div class="relative z-10 flex flex-col items-center justify-center w-full p-12">
                <div class="mb-8 text-center">
                    <h1 class="mb-4 text-4xl font-bold leading-tight text-white">
                        Enhanced interaction between <br /> <span class="text-blue-600">Trainers</span> and <span class="text-purple-600">Trainees</span> online!
                    </h1>

                </div>

                <!-- Decorative elements -->
                <div class="absolute w-32 h-32 bg-blue-200 rounded-full top-20 right-20 opacity-20 "></div>
                <div class="absolute w-24 h-24 bg-purple-200 rounded-full bottom-20 left-20 opacity-20 " style="animation-delay: 1s;"></div>
                <div class="absolute w-16 h-16 bg-indigo-200 rounded-full top-1/2 right-1/3 opacity-20 " style="animation-delay: 2s;"></div>

                <!-- Learning illustration placeholder -->
                <!-- <div class="flex items-center justify-center w-full h-[400px] bg-white/20 rounded-3xl backdrop-blur-sm">
                    <img src="./assets/images/auth/login.png" alt="Preview" class="object-cover w-full h-full">
                </div> -->
            </div>
        </div>

        <!-- Right side - Login Form -->
        <div class="flex items-center justify-center flex-1 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                <div class="p-8 bg-white shadow-xl rounded-2xl">
                    <div class="mb-8 text-center">
                        <img src="./assets/images/logo.png" alt="Logo" class="w-auto h-[70px] mx-auto">
                        <h2 class="mb-2 text-3xl font-bold text-gray-900">Welcome Back!</h2>
                        <p class="text-gray-600">
                            New to our platform?
                            <a href="register.php" class="font-medium text-blue-600 transition-colors hover:text-blue-500">Sign up for free</a>
                        </p>
                    </div>

                    <form method="POST" action="php/login.php" class="space-y-6" id="login-form">
                        <div class="space-y-4">
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="text-gray-400 fas fa-envelope"></i>
                                    </div>
                                    <input type="email" name="email" id="email" required
                                        class="block w-full py-3 pl-10 pr-3 transition-all duration-200 border border-gray-300 rounded-lg input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/50"
                                        placeholder="Enter your email address"
                                        value="<?php echo isset($_SESSION['unverified_email']) ? $_SESSION['unverified_email'] : ''; ?>"><?php unset($_SESSION['unverified_email']); ?>
                                </div>
                                <span class="hidden text-xs text-red-500" id="email-error">Please enter a valid email address</span>
                            </div>

                            <div>
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="text-gray-400 fas fa-lock"></i>
                                    </div>
                                    <input type="password" name="password" id="password" required
                                        class="block w-full py-3 pl-10 pr-12 transition-all duration-200 border border-gray-300 rounded-lg input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/50"
                                        placeholder="Enter your password">
                                    <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 transition-colors hover:text-gray-600">
                                        <i class="fas fa-eye" id="eye-icon"></i>
                                    </button>
                                </div>
                                <span class="hidden text-xs text-red-500" id="password-error">Password is required</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember_me" type="checkbox"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="remember_me" class="block ml-2 text-sm text-gray-700">
                                    Remember me
                                </label>
                            </div>
                            <div class="text-sm">
                                <a href="forget-password.php" class="font-medium text-blue-600 transition-colors hover:text-blue-500">
                                    Forgot password?
                                </a>
                            </div>
                        </div>

                        <div>
                            <button type="submit" id="login-btn"
                                class="flex justify-center w-full px-4 py-3 text-sm font-medium text-white transition-all duration-200 bg-blue-600 border border-transparent rounded-lg shadow-sm btn-hover hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <span id="btn-text">Sign In</span>
                                <span class="hidden ml-2" id="btn-spinner"><i class="fas fa-spinner fa-spin"></i></span>
                            </button>
                        </div>

                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-xs text-gray-500">
                            By continuing, you agree to our
                            <a href="#" class="text-blue-600 transition-colors hover:text-blue-500">Terms of Service</a> and
                            <a href="#" class="text-blue-600 transition-colors hover:text-blue-500">Privacy Policy</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Form validation and submission
        document.getElementById('login-form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            
            let isValid = true;
            
            // Reset errors
            emailError.classList.add('hidden');
            passwordError.classList.add('hidden');
            
            // Validate email
            if (!email || !email.includes('@')) {
                emailError.classList.remove('hidden');
                isValid = false;
            }
            
            // Validate password
            if (!password || password.length < 1) {
                passwordError.classList.remove('hidden');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            const btnText = document.getElementById('btn-text');
            const btnSpinner = document.getElementById('btn-spinner');
            const loginBtn = document.getElementById('login-btn');
            
            btnText.textContent = 'Signing in...';
            btnSpinner.classList.remove('hidden');
            loginBtn.disabled = true;
        });
    </script>
    <script src="./assets/js/validation/login.js"></script>
</body>

</html>