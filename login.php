<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login • Caritas Rwanda</title>
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
        <form method="POST" action="php/login.php"
            class="z-10 flex flex-col gap-5 p-8 py-10 w-[450px] bg-white rounded-lg">
            <!-- Top content -->
            <div class="space-y-2 text-center">
                <img src="./assets/image/logo.gif" alt="Caritas Logo" class="w-auto h-24 mx-auto mb-6">
                <h3 class="text-2xl font-semibold">Login to Account</h3>
                <h5 class="text-sm font-medium text-black/70">Please enter your email and password to continue</h5>
            </div>

            <!-- Input fields -->
            <div class="flex flex-col gap-2">
                <label for="email" class="text-sm text-[#202224] font-medium">Email</label>
                <input type="email" placeholder="someone@gmail.com" id="email" name="email" required
                    class="w-full p-2 transition-colors border rounded-lg border-caritas-cream outline-secondary bg-black/5 focus:border-secondary focus:ring-1 focus:ring-secondary"
                    autocomplete="off">
                <span class="hidden text-xs text-red-500" id="email-error">Please enter a valid email address</span>
            </div>

            <div class="flex flex-col gap-2 mt-3">
                <div class="flex items-center justify-between text-sm">
                    <label for="password" class="text-[#202224] font-medium">Password</label>
                    <a href="./forget-password.php"
                        class="font-semibold text-black/60 hover:text-primary hover:underline active:text-primary-900">Forgot
                        Password?</a>
                </div>
                <input type="password" placeholder="••••••••" id="password" name="password" required
                    class="w-full p-2 transition-colors border rounded-lg border-caritas-cream outline-secondary bg-black/5 focus:border-secondary focus:ring-1 focus:ring-secondary"
                    autocomplete="off">
                <span class="hidden text-xs text-red-500" id="password-error">Password is required</span>
            </div>

            <div class="flex items-center justify-center w-full">
                <button type="submit" id="login-btn"
                    class="p-3 font-semibold text-white transition-all rounded-md w-80 bg-secondary hover:bg-secondary/80 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="btn-text">Sign In</span>
                    <span class="hidden" id="btn-spinner"><i class="fas fa-spinner fa-spin"></i></span>
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-[#202224]">Reserved for authorized personnel only!</p>
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

    <script src="./assets/js/validation/login.js"></script>
</body>

</html>