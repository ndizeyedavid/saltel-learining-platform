<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP • saltel Rwanda</title>
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
        <form method="POST" action="php/verify-otp.php"
            class="z-10 flex flex-col gap-5 p-8 py-10 w-[450px] bg-white rounded-lg">
            <!-- Top content -->
            <div class="space-y-3 text-center">
                <img src="./assets/images/logo.png" alt="saltel Logo" class="w-auto h-20 mx-auto mb-4">
                <h3 class="text-2xl font-semibold text-saltel-charcoal">Verify Your Identity</h3>
                <p class="px-4 text-sm font-medium text-black/70">
                    We've sent a 6-digit verification code to your email address.
                    Please enter the code below to continue.
                </p>
                <p class="text-xs font-medium text-secondary" id="email-display">
                    <!-- Email will be displayed -->
                    someone@gmail.com
                </p>
            </div>

            <!-- OTP Input fields -->
            <div class="flex flex-col gap-4">
                <label class="text-sm text-[#202224] font-medium text-center">Enter Verification Code</label>
                <div class="grid w-full grid-cols-6 gap-3" id="otp-container">
                    <input type="text" maxlength="1" class="p-2 text-xl font-bold text-center transition-all border-2 rounded-lg outline-none md:p-4 otp-input border-saltel-cream bg-black/5 focus:border-secondary focus:ring-2 focus:ring-secondary/20" data-index="0">
                    <input type="text" maxlength="1" class="p-2 text-xl font-bold text-center transition-all border-2 rounded-lg outline-none md:p-4 otp-input border-saltel-cream bg-black/5 focus:border-secondary focus:ring-2 focus:ring-secondary/20" data-index="1">
                    <input type="text" maxlength="1" class="p-2 text-xl font-bold text-center transition-all border-2 rounded-lg outline-none md:p-4 otp-input border-saltel-cream bg-black/5 focus:border-secondary focus:ring-2 focus:ring-secondary/20" data-index="2">
                    <input type="text" maxlength="1" class="p-2 text-xl font-bold text-center transition-all border-2 rounded-lg outline-none md:p-4 otp-input border-saltel-cream bg-black/5 focus:border-secondary focus:ring-2 focus:ring-secondary/20" data-index="3">
                    <input type="text" maxlength="1" class="p-2 text-xl font-bold text-center transition-all border-2 rounded-lg outline-none md:p-4 otp-input border-saltel-cream bg-black/5 focus:border-secondary focus:ring-2 focus:ring-secondary/20" data-index="4">
                    <input type="text" maxlength="1" class="p-2 text-xl font-bold text-center transition-all border-2 rounded-lg outline-none md:p-4 otp-input border-saltel-cream bg-black/5 focus:border-secondary focus:ring-2 focus:ring-secondary/20" data-index="5">
                </div>
                <input type="hidden" name="otp" id="otp-value">
                <span class="hidden text-xs text-center text-red-500" id="otp-error">Please enter the complete verification code</span>
            </div>


            <!-- Submit Button -->
            <div class="flex items-center justify-center w-full">
                <button type="submit" id="verify-btn"
                    class="w-full p-3 font-semibold text-white transition-all rounded-md bg-secondary hover:bg-secondary/80 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="btn-text">Verify Code</span>
                    <span class="hidden" id="btn-spinner"><i class="fas fa-spinner fa-spin"></i></span>
                </button>
            </div>

            <!-- Back Link -->
            <div class="text-center">
                <p class="text-xs text-gray-500">
                    Didn't receive the code?
                    <a href="login.php" class="font-semibold text-secondary hover:text-primary hover:underline">
                        Try Different Email
                    </a>
                </p>
            </div>
        </form>

    </section>

    <script src="./assets/js/validation/otp.js"></script>
</body>

</html>