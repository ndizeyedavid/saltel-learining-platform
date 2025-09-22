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
    <section class="flex items-center justify-center p-2 md:p-0 w-full h-screen bg-[#631401] relative overflow-hidden">
        <form method="POST" action="php/verify-otp.php"
            class="z-10 flex flex-col gap-5 p-8 py-10 w-[450px] bg-white rounded-lg">
            <!-- Top content -->
            <div class="space-y-3 text-center">
                <img src="./assets/image/logo.gif" alt="saltel Logo" class="w-auto h-20 mx-auto mb-4">
                <div class="hidden mb-4 md:block">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-saltel-cream">
                        <i class="text-2xl fas fa-shield-alt text-saltel"></i>
                    </div>
                </div>
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

        <!-- arts -->
        <!-- top left corner -->
        <svg class="absolute -z-1 -top-[190px] -left-[30px] -rotate-[10deg]" width="621" height="511"
            viewBox="0 0 821 611" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path opacity="0.6" fill-rule="evenodd" clip-rule="evenodd"
                d="M219.513 -233.946C-12.7301 -149.416 -132.475 107.378 -47.9459 339.621C-2.75668 463.777 259.396 251.791 372.98 297.623C471.861 337.523 417.534 646.419 525.621 607.079C757.863 522.55 877.608 265.755 793.079 33.5126C708.55 -198.73 451.755 -318.475 219.513 -233.946Z"
                fill="#7C1800" />
        </svg>

        <!-- bottom left corner -->
        <svg class="absolute -z-1 -bottom-[80px] -left-14" width="620" height="485" viewBox="0 0 720 585" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M272.5 895C519.647 895 720 694.648 720 447.5C720 315.376 401.153 424.916 310.095 343C230.823 271.687 387.523 0.00012207 272.5 0.00012207C25.3526 0.00012207 -175 200.353 -175 447.5C-175 694.648 25.3526 895 272.5 895Z"
                fill="#7C1800" />
        </svg>

        <!-- top right corner -->
        <svg class="absolute -z-1 -top-20 right-2" width="584" height="396" viewBox="0 0 584 396" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path opacity="0.541829" fill-rule="evenodd" clip-rule="evenodd"
                d="M8.47073 -208.915C-41.4949 74.4538 147.716 344.675 431.085 394.64C582.573 421.352 521.44 33.6298 633.77 -54.2125C731.561 -130.685 1011.39 103.907 1034.64 -27.9738C1084.61 -311.343 895.395 -581.564 612.026 -631.529C328.657 -681.495 58.4363 -492.284 8.47073 -208.915Z"
                fill="#7C1800" />
        </svg>

        <!-- bottom right corner -->
        <svg class="absolute -z-1 -bottom-10 -right-2" width="475" height="606" viewBox="0 0 575 706" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M1141.38 667.96C1196.52 355.221 987.699 56.9922 674.96 1.84787C507.77 -27.6321 575.24 400.276 451.267 497.223C343.34 581.622 34.5122 322.715 8.84782 468.265C-46.2965 781.004 162.525 1079.23 475.265 1134.38C788.004 1189.52 1086.23 980.699 1141.38 667.96Z"
                fill="#7C1800" />
        </svg>

    </section>

    <script src="./assets/js/validation/otp.js"></script>
</body>

</html>