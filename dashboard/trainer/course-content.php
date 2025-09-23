<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Course Content Builder</title>
    <?php include '../../include/trainer-imports.php'; ?>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script src="../../assets/js/course-state.js"></script>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <main class="flex flex-1 overflow-hidden">
                <!-- Content Navigation -->
                <div class="overflow-y-auto bg-white border-r border-gray-200 w-80">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Course Content</h2>
                            <button class="text-blue-600 hover:text-blue-700" id="addModuleBtn">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="relative">
                            <input type="text" placeholder="Search content..." class="w-full px-3 py-2 pl-8 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <i class="absolute text-gray-400 fas fa-search left-3 top-3"></i>
                        </div>
                    </div>

                    <!-- Module List -->
                    <div id="moduleList" class="p-4 space-y-2">
                        <!-- Module items will be added here -->
                    </div>
                </div>

                <!-- Content Editor -->
                <div class="flex-1 overflow-y-auto">
                    <div class="p-6">
                        <!-- Module/Lesson Editor -->
                        <div id="contentEditor" class="max-w-3xl mx-auto">
                            <div class="mb-6">
                                <input type="text" placeholder="Enter title" class="w-full px-4 py-2 text-2xl font-bold border-none focus:ring-0">
                            </div>

                            <!-- Rich Text Editor -->
                            <div id="editor" class="bg-white border border-gray-200 rounded-lg h-[200px]"></div>

                            <!-- Media Upload Section -->
                            <div class="p-6 mt-6 bg-white border border-gray-200 rounded-lg">
                                <h3 class="mb-4 text-lg font-semibold">Content Resources</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Video Upload -->
                                    <div class="p-4 text-center border border-gray-300 border-dashed rounded-lg">
                                        <i class="mb-2 text-2xl text-gray-400 fas fa-video"></i>
                                        <p class="mb-2 text-sm text-gray-600">Upload Video</p>
                                        <input type="file" accept="video/*" class="hidden" id="videoUpload">
                                        <button onclick="document.getElementById('videoUpload').click()" class="px-4 py-2 text-sm text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50">
                                            Choose File
                                        </button>
                                    </div>

                                    <!-- Document Upload -->
                                    <div class="p-4 text-center border border-gray-300 border-dashed rounded-lg">
                                        <i class="mb-2 text-2xl text-gray-400 fas fa-file-alt"></i>
                                        <p class="mb-2 text-sm text-gray-600">Upload Documents</p>
                                        <input type="file" multiple class="hidden" id="docUpload">
                                        <button onclick="document.getElementById('docUpload').click()" class="px-4 py-2 text-sm text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50">
                                            Choose Files
                                        </button>
                                    </div>
                                </div>

                                <!-- Uploaded Resources List -->
                                <div class="mt-4">
                                    <h4 class="mb-2 text-sm font-medium text-gray-700">Uploaded Resources</h4>
                                    <div class="space-y-2">
                                        <!-- Sample Resource Items -->
                                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                                            <div class="flex items-center">
                                                <i class="mr-3 text-red-500 fas fa-file-pdf"></i>
                                                <span class="text-sm text-gray-700">lecture-notes.pdf</span>
                                            </div>
                                            <button class="text-gray-400 hover:text-red-600">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quiz Builder -->
                            <div class="p-6 mt-6 bg-white border border-gray-200 rounded-lg">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold">Knowledge Check</h3>
                                    <button class="px-4 py-2 text-sm text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50" id="addQuestionBtn">
                                        Add Question
                                    </button>
                                </div>

                                <div id="quizQuestions" class="space-y-4">
                                    <!-- Question Template -->
                                    <div class="p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-start justify-between mb-4">
                                            <input type="text" placeholder="Enter question" class="flex-1 mr-4 text-sm border-none focus:ring-0">
                                            <button class="text-gray-400 hover:text-red-600">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <div class="space-y-2">
                                            <div class="flex items-center">
                                                <input type="radio" name="correct_answer" class="mr-3">
                                                <input type="text" placeholder="Option 1" class="flex-1 text-sm border-none focus:ring-0">
                                            </div>
                                            <!-- Add more options -->
                                        </div>

                                        <button class="mt-2 text-sm text-blue-600 hover:text-blue-700">
                                            <i class="mr-1 fas fa-plus"></i>Add Option
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Settings -->
                            <div class="p-6 mt-6 bg-white border border-gray-200 rounded-lg">
                                <h3 class="mb-4 text-lg font-semibold">Content Settings</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Duration (minutes)</label>
                                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Points</label>
                                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Prerequisites</label>
                                    <select multiple class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option>Previous Module Completion</option>
                                        <option>Minimum Quiz Score</option>
                                        <option>Assignment Submission</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end mt-6 space-x-4">
                                <button class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    Preview
                                </button>
                                <a href="course-settings.php" class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                    Save Changes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Module Modal -->
    <div id="moduleModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
        <div class="absolute w-full max-w-md p-6 transform -translate-x-1/2 -translate-y-1/2 bg-white top-1/2 left-1/2 rounded-xl">
            <h3 class="mb-4 text-lg font-semibold">Add New Module</h3>
            <div class="space-y-4">
                <div>
                    <label class="block mb-2 text-sm font-medium">Module Title</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium">Description</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>
            <div class="flex justify-end mt-6 space-x-4">
                <button class="px-4 py-2 text-gray-600 hover:text-gray-800" id="cancelModule">Cancel</button>
                <button class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700" id="saveModule">Add Module</button>
            </div>
        </div>
    </div>

    <script>
        // Initialize Quill editor
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{
                        'header': 1
                    }, {
                        'header': 2
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });

        // Initialize Sortable for module list
        new Sortable(document.getElementById('moduleList'), {
            animation: 150,
            handle: '.module-handle'
        });

        // Modal handlers
        const moduleModal = document.getElementById('moduleModal');
        const addModuleBtn = document.getElementById('addModuleBtn');
        const cancelModule = document.getElementById('cancelModule');
        const saveModule = document.getElementById('saveModule');

        addModuleBtn.addEventListener('click', () => moduleModal.classList.remove('hidden'));
        cancelModule.addEventListener('click', () => moduleModal.classList.add('hidden'));

        // Add sample module to list
        saveModule.addEventListener('click', () => {
            const moduleHtml = `
                <div class="p-3 bg-white border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="mr-3 text-gray-400 cursor-move fas fa-grip-vertical module-handle"></i>
                            <div>
                                <h4 class="text-sm font-medium">New Module</h4>
                                <p class="text-xs text-gray-500">0 lessons</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-gray-400 hover:text-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('moduleList').insertAdjacentHTML('beforeend', moduleHtml);
            moduleModal.classList.add('hidden');
        });
    </script>
</body>

</html>