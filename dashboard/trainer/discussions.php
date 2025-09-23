<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Discussions</title>
    <?php include '../../include/trainer-imports.php'; ?>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="flex h-full">
                    <!-- Main Discussion Area -->
                    <div class="flex flex-col flex-1">
                        <!-- Discussion Header -->
                        <div class="p-4 bg-white border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">Group Discussion</h1>
                                    <p class="mt-1 text-gray-600">Chat with students and trainers</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-500">12 online</span>
                                </div>
                            </div>
                        </div>

                        <!-- Messages Area -->
                        <div class="flex-1 p-6 space-y-6 overflow-y-auto" id="messagesContainer">
                            <!-- Trainer Message -->
                            <div class="flex items-start space-x-3">
                                <img class="w-10 h-10 rounded-full" src="../../assets/images/discussions/placeholder.png" alt="Trainer">
                                <div class="flex-1">
                                    <div class="flex items-center mb-1 space-x-2">
                                        <h3 class="font-semibold text-gray-900">Sarah</h3>
                                        <span class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">Trainer</span>
                                        <span class="text-sm text-gray-500">2 hours ago</span>
                                    </div>
                                    <div class="p-4 border-l-4 border-blue-400 rounded-r-lg bg-blue-50">
                                        <p class="text-gray-700">Welcome everyone to our discussion forum! This is a great place to ask questions, share insights, and collaborate with your fellow students. Don't hesitate to reach out if you need help with any assignments or concepts.</p>
                                    </div>
                                    <div class="flex items-center mt-2 space-x-4">
                                        <button class="flex items-center space-x-1 text-sm text-gray-500 hover:text-blue-600">
                                            <i class="fas fa-thumbs-up"></i>
                                            <span>12</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Student Message -->
                            <div class="flex items-start space-x-3">
                                <img class="w-10 h-10 rounded-full" src="../../assets/images/discussions/placeholder.png" alt="Student">
                                <div class="flex-1">
                                    <div class="flex items-center mb-1 space-x-2">
                                        <h3 class="font-semibold text-gray-900">Emma Wilson</h3>
                                        <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Student</span>
                                        <span class="text-sm text-gray-500">1 hour ago</span>
                                    </div>
                                    <div class="p-4 bg-white border border-gray-200 rounded-lg">
                                        <p class="text-gray-700">Thank you John! I have a question about the machine learning assignment. I'm having trouble understanding the difference between supervised and unsupervised learning. Could someone explain this with practical examples?</p>
                                    </div>
                                    <div class="flex items-center mt-2 space-x-4">
                                        <button class="flex items-center space-x-1 text-sm text-gray-500 hover:text-blue-600">
                                            <i class="fas fa-thumbs-up"></i>
                                            <span>5</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Another Student Message -->
                            <div class="flex items-start space-x-3">
                                <img class="w-10 h-10 rounded-full" src="../../assets/images/discussions/placeholder.png" alt="Student">
                                <div class="flex-1">
                                    <div class="flex items-center mb-1 space-x-2">
                                        <h3 class="font-semibold text-gray-900">Maria</h3>
                                        <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Student</span>
                                        <span class="text-sm text-gray-500">30 minutes ago</span>
                                    </div>
                                    <div class="p-4 bg-white border border-gray-200 rounded-lg">
                                        <p class="text-gray-700">Has anyone started working on the React project? I'd love to form a study group to work through it together. We could meet virtually this weekend.</p>
                                    </div>
                                    <div class="flex items-center mt-2 space-x-4">
                                        <button class="flex items-center space-x-1 text-sm text-gray-500 hover:text-blue-600">
                                            <i class="fas fa-thumbs-up"></i>
                                            <span>3</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Message Input Area -->
                        <div class="p-4 bg-white border-t border-gray-200">
                            <div class="flex items-end space-x-3">
                                <img class="w-10 h-10 rounded-full" src="../../assets/images/discussions/placeholder.png" alt="You">
                                <div class="flex-1">
                                    <div class="relative">
                                        <textarea id="messageInput" rows="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Type your message here..."></textarea>
                                        <div class="absolute flex items-center space-x-2 bottom-3 right-3">
                                            <button class="p-1 text-gray-400 rounded hover:text-gray-600">
                                                <i class="fas fa-paperclip"></i>
                                            </button>
                                            <button class="p-1 text-gray-400 rounded hover:text-gray-600">
                                                <i class="fas fa-smile"></i>
                                            </button>
                                            <button class="p-1 text-gray-400 rounded hover:text-gray-600">
                                                <i class="fas fa-image"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between mt-2">
                                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                                            <span>Shift + Enter for new line</span>
                                        </div>
                                        <button id="sendMessageBtn" class="px-6 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                            <i class="mr-2 fas fa-paper-plane"></i>
                                            Send
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>

</html>