<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up • E-Learning Platform</title>
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

        .radio-custom {
            appearance: none;
            width: 1rem;
            height: 1rem;
            border: 2px solid #d1d5db;
            border-radius: 50%;
            position: relative;
            cursor: pointer;
        }

        .radio-custom:checked {
            border-color: #3b82f6;
            background-color: #3b82f6;
        }

        .radio-custom:checked::after {
            content: '';
            width: 0.375rem;
            height: 0.375rem;
            border-radius: 50%;
            background-color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body class="min-h-screen bg-[#667eea]">
    <div class="flex min-h-screen">


        <!-- Left side - Illustration -->
        <div class="relative hidden overflow-hidden bg-white lg:flex lg:w-1/2" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.9)),  url('./assets/images/auth/signup.png'); background-size: cover; background-repeat: no-repeat">
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

        <!-- Right side - Signup Form -->
        <div class="flex items-center justify-center flex-1 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-lg">
                <div class="p-8 bg-white shadow-xl rounded-2xl">
                    <div class="mb-8 text-center">
                        <img src="./assets/images/logo.png" alt="Logo" class="w-auto h-[70px] mx-auto">
                        <h2 class="mb-2 text-3xl font-bold text-gray-900">Create Account</h2>
                        <p class="text-gray-600">
                            Already have an account?
                            <a href="login.php" class="font-medium text-blue-600 transition-colors hover:text-blue-500">Sign in here</a>
                        </p>
                    </div>

                    <form method="POST" action="php/register.php" class="space-y-6">
                        <div class="space-y-4">
                            <!-- Name Fields Row -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-700">First Name *</label>
                                    <div class="relative">
                                        <input type="text" name="first_name" id="first_name" required maxlength="100"
                                            class="block w-full p-3 transition-all duration-200 border border-gray-300 rounded-lg input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/50"
                                            placeholder="First name">
                                    </div>
                                </div>
                                <div>
                                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-700">Last Name *</label>
                                    <div class="relative">
                                        <input type="text" name="last_name" id="last_name" required maxlength="100"
                                            class="block w-full p-3 transition-all duration-200 border border-gray-300 rounded-lg input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/50"
                                            placeholder="Last name">
                                    </div>
                                </div>
                            </div>

                            <!-- Gender Selection -->
                            <div>
                                <label class="block mb-3 text-sm font-medium text-gray-700">Gender *</label>
                                <div class="flex space-x-6">
                                    <div class="flex items-center">
                                        <input type="radio" name="gender" id="male" value="male" required class="radio-custom">
                                        <label for="male" class="ml-2 text-sm text-gray-700 cursor-pointer">Male</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="gender" id="female" value="female" required class="radio-custom">
                                        <label for="female" class="ml-2 text-sm text-gray-700 cursor-pointer">Female</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email Address *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="text-gray-400 fas fa-envelope"></i>
                                    </div>
                                    <input type="email" name="email" id="email" required maxlength="150"
                                        class="block w-full py-3 pl-10 pr-3 transition-all duration-200 border border-gray-300 rounded-lg input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/50"
                                        placeholder="Enter your email address">
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block mb-2 text-sm font-medium text-gray-700">Phone Number *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="text-gray-400 fas fa-phone"></i>
                                    </div>
                                    <input type="tel" name="phone" id="phone" required maxlength="20"
                                        class="block w-full py-3 pl-10 pr-3 transition-all duration-200 border border-gray-300 rounded-lg input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/50"
                                        placeholder="Enter your phone number">
                                </div>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="text-gray-400 fas fa-lock"></i>
                                    </div>
                                    <input type="password" name="password" id="password" required
                                        class="block w-full py-3 pl-10 pr-12 transition-all duration-200 border border-gray-300 rounded-lg input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/50"
                                        placeholder="Create a password">
                                    <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 transition-colors hover:text-gray-600">
                                        <i class="fas fa-eye" id="eye-icon"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-700">Confirm Password *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="text-gray-400 fas fa-lock"></i>
                                    </div>
                                    <input type="password" name="confirm_password" id="confirm_password" required
                                        class="block w-full py-3 pl-10 pr-12 transition-all duration-200 border border-gray-300 rounded-lg input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/50"
                                        placeholder="Confirm your password">
                                    <button type="button" id="toggle-confirm-password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 transition-colors hover:text-gray-600">
                                        <i class="fas fa-eye" id="confirm-eye-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-center">
                            <input id="terms" name="terms" type="checkbox" required
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="terms" class="block ml-2 text-sm text-gray-700">
                                I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">Terms of Service</a> and <a href="#" class="text-blue-600 hover:text-blue-500">Privacy Policy</a>
                            </label>
                        </div>

                        <div>
                            <button type="submit" id="register-btn"
                                class="flex justify-center w-full px-4 py-3 text-sm font-medium text-white transition-all duration-200 bg-blue-600 border border-transparent rounded-lg shadow-sm btn-hover hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <span id="btn-text">Create Account</span>
                                <span class="hidden ml-2" id="btn-spinner"><i class="fas fa-spinner fa-spin"></i></span>
                            </button>
                        </div>

                    </form>
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

        // Confirm password toggle functionality
        document.getElementById('toggle-confirm-password').addEventListener('click', function() {
            const confirmPasswordInput = document.getElementById('confirm_password');
            const confirmEyeIcon = document.getElementById('confirm-eye-icon');

            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                confirmEyeIcon.classList.remove('fa-eye');
                confirmEyeIcon.classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                confirmEyeIcon.classList.remove('fa-eye-slash');
                confirmEyeIcon.classList.add('fa-eye');
            }
        });

        // Phone number formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.length <= 3) {
                    value = `(${value}`;
                } else if (value.length <= 6) {
                    value = `(${value.slice(0, 3)}) ${value.slice(3)}`;
                } else {
                    value = `(${value.slice(0, 3)}) ${value.slice(3, 6)}-${value.slice(6, 10)}`;
                }
            }
            e.target.value = value;
        });

        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;

            if (confirmPassword && password !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.setCustomValidity('');
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });

        // Form submission with loading state
        document.getElementById('register-btn').addEventListener('click', function(e) {
            const form = document.querySelector('form');
            const btnText = document.getElementById('btn-text');
            const btnSpinner = document.getElementById('btn-spinner');

            // Check if form is valid
            if (form.checkValidity()) {
                btnText.textContent = 'Creating Account...';
                btnSpinner.classList.remove('hidden');
            }
        });

        // Real-time validation for required fields
        const requiredFields = ['first_name', 'last_name', 'email', 'phone', 'password'];
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            field.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.classList.add('border-red-500');
                    this.classList.remove('border-gray-300');
                } else {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-gray-300');
                }
            });
        });

        // Email validation
        document.getElementById('email').addEventListener('input', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.setCustomValidity('Please enter a valid email address');
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.setCustomValidity('');
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });
    </script>
    <script src="./assets/js/validation/register.js"></script>
</body>

</html>