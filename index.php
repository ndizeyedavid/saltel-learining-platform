<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to saltel Rwanda</title>
    <link rel="icon" href="./assets/images/fav.png">

    <link rel="stylesheet" href="./assets/css/main.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
</head>

<body class="bg-saltel-cream">
    <!-- Main Welcome Container -->
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-4xl">
            <!-- Welcome Card -->
            <div class="overflow-hidden bg-white shadow-2xl rounded-2xl">
                <!-- Header Section with Logo -->
                <div class="py-8 text-center text-white md:py-16 md:px-8 bg-gradient-to-r from-saltel to-secondary">
                    <div class="mb-8">
                        <img src="./assets/images/logo.png" alt="saltel Logo" class="w-auto h-16 p-3 mx-auto mb-6 bg-white rounded-md md:h-24">
                    </div>
                    <h1 class="mb-4 text-[20px] font-bold md:text-4xl">Welcome to saltel Rwanda</h1>
                </div>

                <!-- Content Section -->
                <div class="py-12 text-center md:px-8">
                    <div class="max-w-2xl mx-auto">
                        <h2 class="mb-6 text-[15px]  md:text-3xl font-semibold text-saltel-charcoal">
                            E-Learning Platform
                        </h2>

                        <!-- Login Button -->
                        <div class="space-y-4">
                            <div class="flex gap-5 mx-auto w-fit">
                                <a href="login.php"
                                    class="inline-block px-8 py-4 font-semibold text-white transition-all duration-300 transform rounded-lg shadow-lg bg-saltel hover:bg-saltel-charcoal hover:scale-105 hover:shadow-xl">
                                    Trainee
                                </a>
                                <a href="dashboard/trainer/login.php"
                                    class="inline-block px-8 py-4 font-semibold text-white transition-all duration-300 transform rounded-lg shadow-lg bg-saltel hover:bg-saltel-charcoal hover:scale-105 hover:shadow-xl">
                                    Trainer
                                </a>
                            </div>
                            <p class="text-xs text-gray-500">
                                This system is reserved for authorized users only.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-xs text-saltel-charcoal opacity-70">
                    saltel Rwanda Copyright ©
                    <?php echo date("Y") ?> All Rights Reserved.

                </p>
            </div>
        </div>
    </div>
</body>