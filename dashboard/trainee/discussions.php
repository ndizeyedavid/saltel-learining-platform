<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussions - Saltel • Trainee Dashboard</title>
    <?php include '../../include/imports.php'; ?>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainee-Sidebar.php'; ?>

        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->
            <?php include '../../components/Trainee-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 overflow-hidden">
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
                                <img class="w-10 h-10 rounded-full" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&h=40&fit=crop&crop=face" alt="Trainer">
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
                                <img class="w-10 h-10 rounded-full" src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=40&h=40&fit=crop&crop=face" alt="Student">
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
                                <img class="w-10 h-10 rounded-full" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=40&h=40&fit=crop&crop=face" alt="Student">
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
    <script>
        // Simple Discussion Page JavaScript
        class SimpleDiscussion {
            constructor() {
                this.messages = [{
                        id: 1,
                        user: 'Sarah',
                        role: 'trainer',
                        avatar: '../../assets/images/discussions/placeholder.png',
                        message: 'Welcome everyone to our discussion forum! This is a great place to ask questions, share insights, and collaborate with your fellow students.',
                        timestamp: '2 hours ago',
                        likes: 12
                    },
                    {
                        id: 2,
                        user: 'Wilson',
                        role: 'student',
                        avatar: '../../assets/images/discussions/placeholder.png',

                        message: 'Thank you Johnson! I have a question about the machine learning assignment. I\'m having trouble understanding the difference between supervised and unsupervised learning.',
                        timestamp: '1 hour ago',
                        likes: 5
                    },
                    {
                        id: 3,
                        user: 'Maria',
                        role: 'student',
                        avatar: '../../assets/images/discussions/placeholder.png',

                        message: 'Has anyone started working on the React project? I\'d love to form a study group to work through it together.',
                        timestamp: '30 minutes ago',
                        likes: 3
                    }
                ];
                this.initializeEventListeners();
                this.loadMessages();
            }

            initializeEventListeners() {
                // Message input handling
                const messageInput = document.getElementById('messageInput');
                const sendBtn = document.getElementById('sendMessageBtn');

                messageInput.addEventListener('input', () => {
                    sendBtn.disabled = messageInput.value.trim() === '';
                });

                messageInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        this.sendMessage();
                    }
                });

                sendBtn.addEventListener('click', () => {
                    this.sendMessage();
                });
            }

            loadMessages() {
                const container = document.getElementById('messagesContainer');
                let messagesHTML = '';

                this.messages.forEach(message => {
                    messagesHTML += this.renderMessage(message);
                });

                container.innerHTML = messagesHTML;
                this.attachMessageListeners();
                container.scrollTop = container.scrollHeight;
            }

            renderMessage(message) {
                const roleClass = message.role === 'trainer' ? 'bg-blue-50 border-l-4 border-blue-400' : 'bg-white border border-gray-200';
                const roleBadge = message.role === 'trainer' ?
                    '<span class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">Trainer</span>' :
                    '<span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Student</span>';

                return `
                    <div class="flex items-start space-x-3">
                        <img class="w-10 h-10 rounded-full" src="${message.avatar}" alt="${message.user}">
                        <div class="flex-1">
                            <div class="flex items-center mb-1 space-x-2">
                                <h3 class="font-semibold text-gray-900">${message.user}</h3>
                                ${roleBadge}
                                <span class="text-sm text-gray-500">${message.timestamp}</span>
                            </div>
                            <div class="${roleClass} p-4 rounded-lg">
                                <p class="text-gray-700">${message.message}</p>
                            </div>
                            <div class="flex items-center mt-2 space-x-4">
                                <button class="flex items-center space-x-1 text-sm text-gray-500 hover:text-blue-600 like-btn" data-message-id="${message.id}">
                                    <i class="fas fa-thumbs-up"></i>
                                    <span>${message.likes}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }


            attachMessageListeners() {
                // Like buttons
                document.querySelectorAll('.like-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        this.likeMessage(e.currentTarget.dataset.messageId);
                    });
                });
            }

            sendMessage() {
                const messageInput = document.getElementById('messageInput');
                const message = messageInput.value.trim();

                if (message === '') return;

                const newMessage = {
                    id: Date.now(),
                    user: 'You',
                    role: 'student',
                    avatar: '../../assets/images/discussions/placeholder.png',
                    message: message,
                    timestamp: 'Just now',
                    likes: 0
                };

                this.messages.push(newMessage);
                messageInput.value = '';
                document.getElementById('sendMessageBtn').disabled = true;

                this.loadMessages();
                this.showSuccessMessage('Message sent successfully!');
            }

            likeMessage(messageId) {
                const message = this.messages.find(m => m.id == messageId);
                if (message) {
                    message.likes++;
                    this.loadMessages();
                }
            }

            showSuccessMessage(message) {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-100 border-green-400 text-green-700 border px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm';
                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="mr-2 fas fa-check-circle"></i>
                        <span>${message}</span>
                        <button class="ml-2 font-bold" onclick="this.parentElement.parentElement.remove()">&times;</button>
                    </div>
                `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 3000);
            }
        }

        // Initialize simple discussion when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new SimpleDiscussion();
        });
    </script>
</body>

</html>