<?php ob_start(); ?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/10 to-indigo-50/10" x-data="imageUpload()">
    <div class="p-6 sm:p-6 lg:p-8 pl-4 sm:pl-72 -mt-5 md:mt-10">
        <!-- Header Banner (Matched with index.php style) -->
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl sm:rounded-3xl shadow-lg mb-6 sm:mb-8">
            <div class="absolute right-0 top-0 w-48 sm:w-96 h-full opacity-50 sm:opacity-100">
                <svg class="absolute right-0 top-0 h-full w-full text-white/10" viewBox="0 0 100 100" preserveAspectRatio="none" fill="currentColor">
                    <polygon points="0,0 100,0 100,100 50,100" />
                </svg>
            </div>
            <div class="relative p-4 sm:p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="text-white space-y-2 sm:space-y-4 max-w-lg">
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        <span class="px-2 sm:px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs sm:text-sm">
                            ⚙️ Profile Settings
                        </span>
                        <span class="px-2 sm:px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs sm:text-sm flex items-center gap-1">
                            <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-green-400 rounded-full"></span> Edit Mode
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold">Edit Profile</h1>
                    <p class="text-sm sm:text-base text-white/80">Perbarui informasi profil Anda untuk mencerminkan identitas yang unik</p>
                </div>
                <div class="hidden md:block w-32 lg:w-48 flex-shrink-0">
                    <img src="<?= BASEURL ?>/img/profile.svg" alt="Edit Profile" class="w-full h-auto">
                </div>
            </div>
        </div>

        <?= Flasher::flash(); ?>

        <!-- Form Container (Using index.php card styling) -->
        <div class="space-y-6">
            <form action="<?= BASEURL ?>/dashboard/doProfile" method="POST" enctype="multipart/form-data">
                <!-- Profile Picture Card -->
                <div class="bg-white/80 backdrop-blur-sm rounded-xl sm:rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100/50 hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Left side - Preview -->
                        <div class="w-full md:w-1/3 flex flex-col items-center space-y-4">
                            <div class="relative group">
                                <div class="relative w-40 h-40 rounded-full overflow-hidden bg-gray-50 ring-4 ring-white shadow-lg">
                                    <template x-if="!imageUrl">
                                        <img src="<?= BASEURL ?>/img/users/<?= $_SESSION['myProfile']['profile_picture_url'] ?? 'default.jpg' ?>"
                                            alt="Current Profile"
                                            class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="imageUrl">
                                        <img :src="imageUrl"
                                            alt="New Profile"
                                            class="w-full h-full object-cover">
                                    </template>
                                </div>
                                <button x-show="imageUrl"
                                    @click.prevent="removeImage"
                                    class="absolute -top-2 -right-2 p-1.5 bg-red-500 text-white rounded-full shadow-lg hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="text-sm text-gray-500 text-center">
                                <p class="font-medium text-gray-900 mb-1"><?= $_SESSION['myProfile']['name'] ?? 'User' ?></p>
                                <p>JPG, GIF atau PNG. Maks 2MB</p>
                            </div>
                        </div>

                        <!-- Right side - Upload -->
                        <div class="w-full md:w-2/3">
                            <div class="flex flex-col gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 sm:p-3 bg-blue-50 rounded-lg">
                                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Foto Profil</h3>
                                        <p class="text-sm text-gray-500">Pilih foto terbaik Anda</p>
                                    </div>
                                </div>

                                <!-- Upload Area -->
                                <div class="relative"
                                    @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false"
                                    @drop.prevent="handleDrop($event); isDragging = false">

                                    <label class="flex flex-col items-center gap-4 p-6 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors"
                                        :class="{'border-blue-500 bg-blue-50/50': isDragging}">
                                        <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm font-medium text-blue-600">Klik untuk upload</p>
                                            <p class="text-xs text-gray-500 mt-1">atau drag and drop</p>
                                        </div>
                                        <input type="file"
                                            class="hidden"
                                            x-ref="fileInput"
                                            @change="handleImageUpload"
                                            name="image"
                                            accept="image/*">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Personal Information Card -->
                <div class="bg-white/80 backdrop-blur-sm rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-sm border border-gray-100/50 hover:shadow-lg transition-all duration-300 mt-6 sm:mt-8">
                    <!-- Section Header with Animation -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 sm:p-3 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-xl shadow-inner">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent">Informasi Pribadi</h3>
                                <p class="text-xs sm:text-sm text-gray-500">Update data diri Anda</p>
                            </div>
                        </div>
                        <div class="hidden sm:block h-px flex-1 bg-gradient-to-r from-indigo-100 to-transparent"></div>
                    </div>

                    <!-- Enhanced Form Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Name Field with Icon -->
                        <div class="space-y-1.5">
                            <label for="name" class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <input type="text"
                                    id="name"
                                    name="name"
                                    value="<?= $_SESSION['myProfile']['name'] ?? '' ?>"
                                    class="w-full pl-10 pr-4 py-2.5 text-sm sm:text-base rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                <div class="absolute inset-0 rounded-xl transition-opacity opacity-0 group-focus-within:opacity-100 pointer-events-none">
                                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 opacity-[0.08]"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Username Field with Enhanced @ Symbol -->
                        <div class="space-y-1.5">
                            <label for="username" class="text-sm font-medium text-gray-700">Username</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-400 group-focus-within:text-blue-500 transition-colors text-sm sm:text-base">@</span>
                                </div>
                                <input type="text"
                                    id="username"
                                    name="username"
                                    value="<?= $_SESSION['myProfile']['username'] ?? '' ?>"
                                    class="w-full pl-8 pr-4 py-2.5 text-sm sm:text-base rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                <div class="absolute inset-0 rounded-xl transition-opacity opacity-0 group-focus-within:opacity-100 pointer-events-none">
                                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 opacity-[0.08]"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Email Field with Icon -->
                        <div class="space-y-1.5">
                            <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="email"
                                    id="email"
                                    name="email"
                                    value="<?= $_SESSION['myProfile']['email'] ?? '' ?>"
                                    class="w-full pl-10 pr-4 py-2.5 text-sm sm:text-base rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                <div class="absolute inset-0 rounded-xl transition-opacity opacity-0 group-focus-within:opacity-100 pointer-events-none">
                                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 opacity-[0.08]"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Section with Visual Separator -->
                        <div class="col-span-1 sm:col-span-2">
                            <div class="h-px bg-gradient-to-r from-gray-100 via-gray-200 to-gray-100 my-6"></div>
                            <p class="text-xs sm:text-sm text-gray-500 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                        </div>

                        <!-- Password Fields with Icons -->
                        <div class="space-y-1.5">
                            <label for="new_password" class="text-sm font-medium text-gray-700">Password Baru</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input type="password"
                                    id="new_password"
                                    name="new_password"
                                    class="w-full pl-10 pr-4 py-2.5 text-sm sm:text-base rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="confirm_password" class="text-sm font-medium text-gray-700">Konfirmasi Password</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <input type="password"
                                    id="confirm_password"
                                    name="confirm_password"
                                    class="w-full pl-10 pr-4 py-2.5 text-sm sm:text-base rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Mobile-Friendly Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4 mt-6 sm:mt-8">
                    <a href="<?= BASEURL ?>/dashboard"
                        class="group relative px-4 py-2 text-sm font-medium text-gray-700 bg-white overflow-hidden rounded-xl transition-all duration-300 hover:shadow-md">
                        <div class="absolute inset-0 w-3 bg-gray-100 transition-all duration-300 ease-out group-hover:w-full"></div>
                        <div class="relative flex items-center gap-2">
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Kembali</span>
                        </div>
                    </a>
                    <button type="submit"
                        class="group relative px-6 py-2 text-sm font-medium text-white overflow-hidden rounded-xl transition-all duration-300">
                        <!-- Gradient Background -->
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 transition-all duration-300 group-hover:scale-102"></div>
                        <!-- Shine Effect -->
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-25 transition-opacity duration-300">
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent -skew-x-12 translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                        </div>
                        <!-- Button Content -->
                        <div class="relative flex items-center gap-2 group-hover:scale-[0.97] transition-transform duration-300">
                            <span>Simpan Perubahan</span>
                            <svg class="w-4 h-4 transition-all duration-300 group-hover:translate-x-1 group-hover:rotate-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <!-- Particle Effects (Optional) -->
                            <span class="absolute right-0 top-0 -mt-1 -mr-1 w-2 h-2 rounded-full bg-white opacity-50 blur-sm group-hover:animate-ping"></span>
                            <span class="absolute right-0 bottom-0 -mb-1 -mr-1 w-2 h-2 rounded-full bg-white opacity-50 blur-sm group-hover:animate-ping" style="animation-delay: 0.2s"></span>
                        </div>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function imageUpload() {
        return {
            imageUrl: null,
            isDragging: false,
            handleImageUpload(e) {
                const file = e.target.files[0];
                this.processFile(file);
            },
            handleDrop(e) {
                const file = e.dataTransfer.files[0];
                this.processFile(file);
            },
            processFile(file) {
                if (!file) return;

                if (file.size > 2 * 1024 * 1024) {
                    alert('File terlalu besar! Maksimal 2MB');
                    return;
                }

                if (!['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
                    alert('Format file tidak didukung! Gunakan JPG, PNG, atau GIF');
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => this.imageUrl = e.target.result;
                reader.readAsDataURL(file);
            },
            removeImage() {
                this.imageUrl = null;
                this.$refs.fileInput.value = '';
            }
        }
    }
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../templates/dashboard.php';
?>