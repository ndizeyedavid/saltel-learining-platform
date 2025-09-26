<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Course Builder</title>
    <?php
    include '../../include/trainer-guard.php';
    include '../../include/connect.php';

    // Check if editing existing course
    $course_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $course_data = null;

    if ($course_id) {
        $stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ? AND teacher_id = ?");
        $stmt->bind_param("ii", $course_id, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $course_data = $result->fetch_assoc();
        } else {
            header("Location: courses.php");
            exit();
        }
    }

    include '../../include/trainer-imports.php';
    ?>
    <script src="../../assets/js/course-state.js"></script>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <main class="flex-1 p-6 overflow-y-auto">
                <!-- Course Builder Form -->
                <div class="max-w-4xl mx-auto">
                    <!-- Header -->
                    <div class="mb-8">
                        <h1 class="text-2xl font-bold text-gray-900">
                            <?php echo $course_data ? 'Edit Course' : 'Create New Course'; ?>
                        </h1>
                        <p class="mt-1 text-sm text-gray-600">
                            <?php echo $course_data ? 'Update your course details and settings.' : 'Fill in the details below to create your course.'; ?>
                        </p>
                    </div>
                    <?php if (isset($_SESSION['error'])): ?>

                        <div class="p-4 mb-5 text-sm text-white bg-red-600 rounded-lg">
                            <?php echo $_SESSION['error']; ?>sadas
                        </div>
                    <?php endif; ?>

                    <!-- Course Form -->
                    <form id="courseForm" action="../../dashboard/api/courses/save.php" method="POST" enctype="multipart/form-data">
                        <?php if ($course_data): ?>
                            <input type="hidden" name="course_id" value="<?php echo $course_data['course_id']; ?>">
                        <?php endif; ?>

                        <div class="space-y-6">
                            <!-- Basic Course Information -->
                            <div class="p-6 bg-white rounded-lg shadow-sm">
                                <h2 class="mb-6 text-lg font-semibold text-gray-900">Basic Information</h2>
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div class="md:col-span-2">
                                        <label for="course_title" class="block mb-2 text-sm font-medium text-gray-700">Course Title *</label>
                                        <input type="text" id="course_title" name="course_title" required
                                            value="<?php echo htmlspecialchars($course_data['course_title'] ?? ''); ?>"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Enter course title">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Course Description *</label>
                                        <textarea id="description" name="description" required rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Describe your course, what students will learn, and any prerequisites"><?php echo htmlspecialchars($course_data['description'] ?? ''); ?></textarea>
                                    </div>

                                    <div>
                                        <label for="category" class="block mb-2 text-sm font-medium text-gray-700">Category *</label>
                                        <select id="category" name="category" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Select category</option>
                                            <option value="Development" <?php echo ($course_data['category'] ?? '') === 'Development' ? 'selected' : ''; ?>>Development</option>
                                            <option value="Business" <?php echo ($course_data['category'] ?? '') === 'Business' ? 'selected' : ''; ?>>Business</option>
                                            <option value="Design" <?php echo ($course_data['category'] ?? '') === 'Design' ? 'selected' : ''; ?>>Design</option>
                                            <option value="Marketing" <?php echo ($course_data['category'] ?? '') === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                                            <option value="Data Science" <?php echo ($course_data['category'] ?? '') === 'Data Science' ? 'selected' : ''; ?>>Data Science</option>
                                            <option value="Photography" <?php echo ($course_data['category'] ?? '') === 'Photography' ? 'selected' : ''; ?>>Photography</option>
                                            <option value="Music" <?php echo ($course_data['category'] ?? '') === 'Music' ? 'selected' : ''; ?>>Music</option>
                                            <option value="Health & Fitness" <?php echo ($course_data['category'] ?? '') === 'Health & Fitness' ? 'selected' : ''; ?>>Health & Fitness</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="level" class="block mb-2 text-sm font-medium text-gray-700">Difficulty Level *</label>
                                        <select id="level" name="level" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Select level</option>
                                            <option value="Beginner" <?php echo ($course_data['level'] ?? '') === 'Beginner' ? 'selected' : ''; ?>>Beginner</option>
                                            <option value="Intermediate" <?php echo ($course_data['level'] ?? '') === 'Intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                                            <option value="Advanced" <?php echo ($course_data['level'] ?? '') === 'Advanced' ? 'selected' : ''; ?>>Advanced</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="price" class="block mb-2 text-sm font-medium text-gray-700">Price ($) *</label>
                                        <input type="number" id="price" name="price" required min="0" step="0.01"
                                            value="<?php echo $course_data['price'] ?? ''; ?>"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="0.00">
                                    </div>

                                    <div>
                                        <label for="max_students" class="block mb-2 text-sm font-medium text-gray-700">Max Students</label>
                                        <input type="number" id="max_students" name="max_students" min="1"
                                            value="<?php echo $course_data['max_students'] ?? ''; ?>"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Leave empty for unlimited">
                                    </div>
                                </div>
                            </div>

                            <!-- Course Settings -->
                            <div class="p-6 bg-white rounded-lg shadow-sm">
                                <h2 class="mb-6 text-lg font-semibold text-gray-900">Course Settings</h2>
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                                        <select id="status" name="status"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="Draft" <?php echo ($course_data['status'] ?? 'Draft') === 'Draft' ? 'selected' : ''; ?>>Draft</option>
                                            <option value="Published" <?php echo ($course_data['status'] ?? '') === 'Published' ? 'selected' : ''; ?>>Published</option>
                                            <option value="Archived" <?php echo ($course_data['status'] ?? '') === 'Archived' ? 'selected' : ''; ?>>Archived</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="visibility" class="block mb-2 text-sm font-medium text-gray-700">Visibility</label>
                                        <select id="visibility" name="visibility"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="Public" <?php echo ($course_data['visibility'] ?? 'Public') === 'Public' ? 'selected' : ''; ?>>Public</option>
                                            <option value="Private" <?php echo ($course_data['visibility'] ?? '') === 'Private' ? 'selected' : ''; ?>>Private</option>
                                            <option value="Password Protected" <?php echo ($course_data['visibility'] ?? '') === 'Password Protected' ? 'selected' : ''; ?>>Password Protected</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-700">Start Date</label>
                                        <input type="date" id="start_date" name="start_date"
                                            value="<?php echo $course_data['start_date'] ?? ''; ?>"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>

                                    <div>
                                        <label for="end_date" class="block mb-2 text-sm font-medium text-gray-700">End Date</label>
                                        <input type="date" id="end_date" name="end_date"
                                            value="<?php echo $course_data['end_date'] ?? ''; ?>"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Course Image -->
                            <div class="p-6 bg-white rounded-lg shadow-sm">
                                <h2 class="mb-6 text-lg font-semibold text-gray-900">Course Image</h2>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-center p-6 transition-colors border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400">
                                        <div class="text-center">
                                            <i class="mb-4 text-3xl text-gray-400 fas fa-cloud-upload-alt"></i>
                                            <p class="mb-2 text-sm text-gray-600">Drag and drop your image here</p>
                                            <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                            <input type="file" id="course_image" name="course_image" accept="image/*" class="hidden">
                                            <button type="button" onclick="document.getElementById('course_image').click()"
                                                class="px-4 py-2 mt-4 text-sm text-blue-600 transition-colors border border-blue-600 rounded-lg hover:bg-blue-50">
                                                Browse Files
                                            </button>
                                        </div>
                                    </div>
                                    <div id="imagePreview" class="hidden">
                                        <img id="previewImg" src="" alt="Course preview" class="object-cover w-full h-48 rounded-lg">
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex flex-col items-center justify-between gap-4 p-6 bg-white rounded-lg shadow-sm sm:flex-row">
                                <a href="courses.php" class="w-full sm:w-auto">
                                    <button type="button" class="w-full px-6 py-2 text-gray-600 transition-colors border border-gray-300 rounded-lg sm:w-auto hover:bg-gray-50">
                                        <i class="mr-2 fas fa-arrow-left"></i>Back to Courses
                                    </button>
                                </a>

                                <div class="flex flex-col w-full gap-3 sm:flex-row sm:w-auto">
                                    <button type="submit" name="action" value="draft"
                                        class="hidden w-full px-6 py-2 text-gray-700 transition-colors bg-gray-100 border border-gray-300 rounded-lg sm:w-auto hover:bg-gray-200">
                                        <i class="mr-2 fas fa-save"></i>Save as Draft
                                    </button>

                                    <button type="submit" name="action" value="publish"
                                        class="w-full px-6 py-2 text-white transition-colors bg-blue-600 rounded-lg sm:w-auto hover:bg-blue-700">
                                        <i class="mr-2 fas fa-rocket"></i><?php echo $course_data ? 'Update Course' : 'Create & Publish'; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>

</html>