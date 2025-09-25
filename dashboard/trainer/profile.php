<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • My Profile</title>
    <?php include '../../include/trainer-imports.php'; ?>
    <?php include '../../include/trainer-guard.php'; ?>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="px-6 py-8 mx-auto">
                    <!-- Profile Header -->
                    <div class="flex items-center justify-between mb-8">
                        <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
                        <button id="saveProfileBtn" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Save Changes
                        </button>
                    </div>

                    <!-- Profile Content -->
                    <div class="space-y-6">
                        <!-- Profile Picture -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Profile Picture</h2>
                            <div class="flex items-center space-x-6">
                                <div class="flex items-center justify-center w-24 h-24 overflow-hidden bg-gradient-to-r from-saltel to-secondary rounded-xl">
                                    <img id="profileImage" src="" alt="Profile" class="object-cover w-full h-full hidden" onerror="this.classList.add('hidden');" />
                                    <span id="profileInitials" class="text-3xl font-medium text-white">MJ</span>
                                </div>
                                <div>
                                    <input id="profileFile" type="file" accept="image/*" class="hidden" />
                                    <button id="uploadBtn" class="px-4 py-2 text-sm text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50">
                                        Upload New Photo
                                    </button>
                                    <p class="mt-2 text-xs text-gray-500">Maximum file size: 2MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Personal Information</h2>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">First Name</label>
                                    <input id="firstName" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Last Name</label>
                                    <input id="lastName" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                                    <input id="email" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Phone</label>
                                    <input id="phone" type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Middle Name</label>
                                    <input id="middleName" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Gender</label>
                                    <select id="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Expertise -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Expertise</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Areas of Expertise</label>
                                    <input id="expertise" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="e.g., Web Development, Data Science">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Bio</label>
                                    <textarea id="bio" class="w-full h-32 px-3 py-2 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Tell us about yourself"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Social Links -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Social Links</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">LinkedIn</label>
                                    <input id="linkedin" type="url" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="https://linkedin.com/in/username">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Website</label>
                                    <input id="website" type="url" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="https://yourwebsite.com">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadProfile();
            document.getElementById('saveProfileBtn').addEventListener('click', saveProfile);
            document.getElementById('uploadBtn').addEventListener('click', () => document.getElementById('profileFile').click());
            document.getElementById('profileFile').addEventListener('change', uploadProfileImage);
        });

        async function loadProfile() {
            try {
                const res = await fetch('../api/trainer/profile.php');
                if (!res.ok) throw new Error('Failed to load profile');
                const data = await res.json();
                const u = data.success?.user || {};
                document.getElementById('firstName').value = u.first_name || '';
                document.getElementById('middleName').value = u.middle_name || '';
                document.getElementById('lastName').value = u.last_name || '';
                document.getElementById('email').value = u.email || '';
                document.getElementById('phone').value = u.phone || '';
                document.getElementById('gender').value = u.gender || 'Male';
                document.getElementById('expertise').value = u.expertise || '';
                document.getElementById('bio').value = u.bio || '';
                document.getElementById('linkedin').value = u.linkedin_url || '';
                document.getElementById('website').value = u.website_url || '';

                const img = document.getElementById('profileImage');
                const initials = document.getElementById('profileInitials');
                if (u.profile_image_url) {
                    img.src = '../../' + u.profile_image_url;
                    img.classList.remove('hidden');
                    initials.classList.add('hidden');
                } else {
                    img.classList.add('hidden');
                    const f = (u.first_name || '').charAt(0).toUpperCase();
                    const l = (u.last_name || '').charAt(0).toUpperCase();
                    initials.textContent = (f || 'U') + (l || 'N');
                    initials.classList.remove('hidden');
                }
            } catch (e) {
                console.error(e);
                alert('Could not load profile.');
            }
        }

        async function uploadProfileImage(ev) {
            const file = ev.target.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) {
                alert('File too large (max 2MB)');
                return;
            }
            const form = new FormData();
            form.append('image', file);
            try {
                const res = await fetch('../api/trainer/upload_profile_image.php', {
                    method: 'POST',
                    body: form
                });
                const data = await res.json();
                if (!res.ok || !data.success) throw new Error(data.error || 'Upload failed');
                const url = data.success.profile_image_url;
                const img = document.getElementById('profileImage');
                const initials = document.getElementById('profileInitials');
                img.src = '../../' + url;
                img.classList.remove('hidden');
                initials.classList.add('hidden');
                alert('Profile image updated');
            } catch (e) {
                console.error(e);
                alert(e.message || 'Could not upload image');
            } finally {
                ev.target.value = '';
            }
        }

        async function saveProfile() {
            const payload = {
                first_name: document.getElementById('firstName').value.trim(),
                middle_name: document.getElementById('middleName').value.trim(),
                last_name: document.getElementById('lastName').value.trim(),
                email: document.getElementById('email').value.trim(),
                phone: document.getElementById('phone').value.trim(),
                gender: document.getElementById('gender').value,
                expertise: document.getElementById('expertise').value.trim(),
                bio: document.getElementById('bio').value.trim(),
                linkedin_url: document.getElementById('linkedin').value.trim(),
                website_url: document.getElementById('website').value.trim()
            };

            try {
                const res = await fetch('../api/trainer/profile.php', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (!res.ok || !data.success) {
                    throw new Error(data.error || 'Failed to save');
                }
                alert('Profile saved');
            } catch (e) {
                console.error(e);
                alert(e.message || 'Could not save profile.');
            }
        }
    </script>
</body>

</html>