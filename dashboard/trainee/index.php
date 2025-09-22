<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Trainee Dashboard</title>
    <?php include '../../include/imports.php'; ?>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainee-Sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->
            <?php include '../../components/Trainee-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Hello Chicken 🐥</h1>
                    <p class="text-gray-600">Let's learn something new today!</p>
                </div>

                <!-- Top Row - Recent Course, Resources, Calendar -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Recent Enrolled Course -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent enrolled course</h3>
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-laptop-code text-2xl text-gray-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Product Design Course</h4>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: 65%"></div>
                                </div>
                                <p class="text-sm text-blue-600 mt-1">14/20 class</p>
                            </div>
                        </div>
                    </div>

                    <!-- Your Resources -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Resources</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between resource-item">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file-pdf text-red-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Auto layout.pdf</p>
                                        <p class="text-xs text-gray-500">83 MB</p>
                                    </div>
                                </div>
                                <button class="text-blue-600 text-sm font-medium resource-btn">Open</button>
                            </div>
                            <div class="flex items-center justify-between resource-item">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file-alt text-green-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Design - Figma</p>
                                        <p class="text-xs text-gray-500">829 KB</p>
                                    </div>
                                </div>
                                <button class="text-blue-600 text-sm font-medium resource-btn">Continue</button>
                            </div>
                            <div class="flex items-center justify-between resource-item">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-video text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Basics of UI.mp4</p>
                                        <p class="text-xs text-gray-500">32 MB</p>
                                    </div>
                                </div>
                                <button class="text-blue-600 text-sm font-medium resource-btn">Continue</button>
                            </div>
                        </div>
                        <button class="text-blue-600 text-sm font-medium mt-4">See more</button>
                    </div>

                    <!-- Calendar -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <button class="p-1 hover:bg-gray-100 rounded calendar-prev">
                                <i class="fas fa-chevron-left text-gray-600"></i>
                            </button>
                            <h3 class="font-semibold text-gray-900 calendar-month">Jun 2024</h3>
                            <button class="p-1 hover:bg-gray-100 rounded calendar-next">
                                <i class="fas fa-chevron-right text-gray-600"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center">
                            <div class="text-xs font-medium text-gray-500 py-2">Su</div>
                            <div class="text-xs font-medium text-gray-500 py-2">Mo</div>
                            <div class="text-xs font-medium text-gray-500 py-2">Tu</div>
                            <div class="text-xs font-medium text-gray-500 py-2">We</div>
                            <div class="text-xs font-medium text-gray-500 py-2">Th</div>
                            <div class="text-xs font-medium text-gray-500 py-2">Fr</div>
                            <div class="text-xs font-medium text-gray-500 py-2">Sa</div>
                            <!-- Calendar days -->
                            <div class="text-sm py-2 text-gray-400"></div>
                            <div class="text-sm py-2 text-gray-400"></div>
                            <div class="text-sm py-2 text-gray-400"></div>
                            <div class="text-sm py-2 text-gray-400"></div>
                            <div class="text-sm py-2 text-gray-400"></div>
                            <div class="text-sm py-2 text-gray-400">1</div>
                            <div class="text-sm py-2 text-gray-900">2</div>
                            <div class="text-sm py-2 text-gray-900">3</div>
                            <div class="text-sm py-2 text-gray-900">4</div>
                            <div class="text-sm py-2 text-gray-900">5</div>
                            <div class="text-sm py-2 text-gray-900">6</div>
                            <div class="text-sm py-2 text-gray-900">7</div>
                            <div class="text-sm py-2 text-gray-900">8</div>
                            <div class="text-sm py-2 text-gray-900">9</div>
                            <div class="text-sm py-2 bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center mx-auto">10</div>
                            <div class="text-sm py-2 text-gray-900">11</div>
                            <div class="text-sm py-2 text-gray-900">12</div>
                            <div class="text-sm py-2 text-gray-900">13</div>
                            <div class="text-sm py-2 text-gray-900">14</div>
                            <div class="text-sm py-2 text-gray-900">15</div>
                            <div class="text-sm py-2 text-gray-900">16</div>
                            <div class="text-sm py-2 text-gray-900">17</div>
                            <div class="text-sm py-2 text-gray-900">18</div>
                            <div class="text-sm py-2 text-gray-900">19</div>
                            <div class="text-sm py-2 text-gray-900">20</div>
                            <div class="text-sm py-2 text-gray-900">21</div>
                            <div class="text-sm py-2 text-gray-900">22</div>
                            <div class="text-sm py-2 text-gray-900">23</div>
                            <div class="text-sm py-2 text-gray-900">24</div>
                            <div class="text-sm py-2 text-gray-900">25</div>
                            <div class="text-sm py-2 text-gray-900">26</div>
                            <div class="text-sm py-2 text-gray-900">27</div>
                            <div class="text-sm py-2 text-gray-900">28</div>
                            <div class="text-sm py-2 text-gray-900">29</div>
                            <div class="text-sm py-2 text-gray-900">30</div>
                        </div>
                    </div>
                </div>

                <!-- Middle Row - Hours Spent, Performance, To Do List -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Hours Spent Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Hours Spent</h3>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Study</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                                <span class="text-sm text-gray-600">Online Test</span>
                            </div>
                        </div>
                        <canvas id="hoursChart" class="w-full h-48"></canvas>
                    </div>

                    <!-- Performance -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Performance</h3>
                            <select class="text-sm border border-gray-200 rounded-lg px-3 py-1">
                                <option>Monthly</option>
                                <option>Weekly</option>
                                <option>Daily</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-center mb-4">
                            <div class="relative w-32 h-32">
                                <canvas id="performanceChart" class="w-full h-full"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-900">8.966</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Your Grade: <span class="font-semibold">8.966</span></p>
                        </div>
                    </div>

                    <!-- To Do List -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">To do List</h3>
                        <div class="space-y-3" id="todoList">
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" class="mt-1 rounded border-gray-300">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-sm">Human Interaction Designs</p>
                                    <p class="text-xs text-gray-500">Tuesday, 30 June 2024</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" class="mt-1 rounded border-gray-300">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-sm">Design system Basics</p>
                                    <p class="text-xs text-gray-500">Monday, 24 June 2024</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" checked class="mt-1 rounded border-gray-300">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-sm line-through text-gray-500">Introduction to UI</p>
                                    <p class="text-xs text-gray-500">Friday, 10 June 2024</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" checked class="mt-1 rounded border-gray-300">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-sm line-through text-gray-500">Basics of Figma</p>
                                    <p class="text-xs text-gray-500">Friday, 05 June 2024</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Row - Recent Classes and Upcoming Lessons -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Enrolled Classes -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Recent enrolled classes</h3>
                            <div class="flex items-center space-x-2">
                                <button class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg">All</button>
                                <button class="p-2 hover:bg-gray-100 rounded-lg">
                                    <i class="fas fa-search text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="border border-blue-200 bg-blue-50 rounded-lg p-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-paint-brush text-blue-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">User Experience (UX) Design</h4>
                                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                            <span><i class="far fa-clock mr-1"></i>5:30hrs</span>
                                            <span><i class="fas fa-book mr-1"></i>05 Lessons</span>
                                            <span><i class="fas fa-tasks mr-1"></i>Assignments</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-palette text-gray-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">Visual Design and Branding</h4>
                                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                            <span><i class="far fa-clock mr-1"></i>4:00hrs</span>
                                            <span><i class="fas fa-book mr-1"></i>03 Lessons</span>
                                            <span><i class="fas fa-tasks mr-1"></i>Assignments</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Lessons -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Upcoming Lesson</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg lesson-item">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-graduation-cap text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">UX Design Fundamentals</h4>
                                        <p class="text-sm text-gray-600">5:30pm</p>
                                    </div>
                                </div>
                                <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 join-class-btn">Join</button>
                            </div>

                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg lesson-item">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-mouse-pointer text-green-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Interaction Design</h4>
                                        <p class="text-sm text-gray-600">9:00pm</p>
                                    </div>
                                </div>
                                <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 join-class-btn">Join</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>