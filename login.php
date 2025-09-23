<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login • E-Learning Platform</title>
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
    <div class="flex min-h-screen">


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

                    <form method="POST" action="php/login.php" class="space-y-6">
                        <div class="space-y-4">
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="text-gray-400 fas fa-envelope"></i>
                                    </div>
                                    <input type="email" name="email" id="email" required
                                        class="block w-full py-3 pl-10 pr-3 transition-all duration-200 border border-gray-300 rounded-lg input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/50"
                                        placeholder="Enter your email address">
                                </div>
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

        // Form submission with loading state
        document.getElementById('login-btn').addEventListener('click', function() {
            const btnText = document.getElementById('btn-text');
            const btnSpinner = document.getElementById('btn-spinner');

            btnText.textContent = 'Signing in...';
            btnSpinner.classList.remove('hidden');
        });
    </script>
    <script src="./assets/js/validation/login.js"></script>
</body>

</html>