<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password • saltel Rwanda</title>
    <link rel="icon" href="./assets/images/fav.png">

    <link rel="stylesheet" href="./assets/css/main.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Toastr CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body>
    <section class="flex items-center justify-center p-2 md:p-0 w-full h-screen bg-[#667eea] relative overflow-hidden">
        <form method="POST" action="php/forget-password.php"
            class="z-10 flex flex-col gap-5 p-8 py-10 w-[450px] bg-white rounded-lg">
            <!-- Top content -->
            <div class="space-y-2 text-center">
                <img src="./assets/images/logo.png" alt="saltel Logo" class="w-auto h-24 mx-auto mb-6">
                <h3 class="text-2xl font-semibold">Password Reset</h3>
                <h5 class="text-sm font-medium text-black/70">Provide the email address associated with your account
                    to recover your password.</h5>
            </div>

            <!-- Input fields -->
            <div class="flex flex-col gap-2">
                <label for="email" class="text-sm text-[#202224] font-medium">Email</label>
                <input type="email" placeholder="someone@gmail.com" id="email" name="email" required
                    class="w-full p-2 transition-colors border rounded-lg border-saltel-cream outline-secondary bg-black/5 focus:border-secondary focus:ring-1 focus:ring-secondary"
                    autocomplete="off">
                <span class="hidden text-xs text-red-500" id="email-error">Please enter a valid email address</span>
            </div>


            <div class="flex items-center justify-center w-full">
                <button type="submit" id="login-btn"
                    class="p-3 font-semibold text-white transition-all rounded-md w-80 bg-secondary hover:bg-secondary/80 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="btn-text">Reset Password</span>
                    <span class="hidden" id="btn-spinner"><i class="fas fa-spinner fa-spin"></i></span>
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-500">
                    Remember your password?
                    <a href="login.php" class="font-semibold text-secondary hover:text-primary hover:underline">
                        Back to Login
                    </a>
                </p>
            </div>
        </form>
    </section>

    <script src="./assets/js/validation/forget-password.js"></script>
</body>

</html>