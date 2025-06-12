<?php ob_start(); ?>

<!-- Add x-cloak style -->
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="min-h-[calc(100vh-10rem)] bg-gradient-to-br from-gray-50 via-blue-50/10 to-indigo-50/10"
    x-data="{
        ...imageUpload(),
        showPasswordModal: false,
        isFormChanged: false,
        showOldPassword: false,
        showNewPassword: false,
        showConfirmPassword: false,
        initialData: {
            name: '<?= $_SESSION['myProfile']['name'] ?? '' ?>',
            username: '<?= $_SESSION['myProfile']['username'] ?? '' ?>',
            email: '<?= $_SESSION['myProfile']['email'] ?? '' ?>'
        },
        checkChanges() {
            this.isFormChanged =
                this.initialData.name !== document.getElementById('name').value ||
                this.initialData.username !== document.getElementById('username').value ||
                this.initialData.email !== document.getElementById('email').value ||
                this.imageUrl !== null;
        }
    }">
    <div class="p-6 sm:p-6 lg:p-8 pl-4 sm:pl-72 -mt-5 md:mt-10">

        <?= Flasher::flash(); ?>

        <div class="space-y-6">
            <!-- FORM EDIT PROFILE -->
            <form action="<?= BASEURL ?>/dashboard/doProfile" method="POST" enctype="multipart/form-data" @submit="validateForm($event)">
                <?= Helper::renderCSRFField(); ?>

                <!-- Personal Information Section -->
                <div class="bg-white/80 backdrop-blur-sm rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-sm border border-gray-100/50 hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 sm:p-3 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-xl shadow-inner">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent">
                                    Informasi Pribadi
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500">Update data diri Anda</p>
                            </div>
                        </div>
                        <div class="hidden sm:block h-px flex-1 bg-gradient-to-r from-indigo-100 to-transparent"></div>
                    </div>

                    <!-- Form Fields with Profile Picture -->
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Left side - Profile Picture -->
                        <div class="w-full md:w-1/3">
                            <div class="flex flex-col items-center space-y-4">
                                <div class="relative group">
                                    <div class="relative w-52 h-52 rounded-full overflow-hidden bg-gray-50 ring-4 ring-white shadow-lg">
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
                                        <!-- Camera Icon Button -->
                                        <label class="absolute bottom-0 inset-x-0 h-12 flex items-center justify-center bg-black/50 cursor-pointer group-hover:h-full transition-all duration-300">
                                            <div class="flex flex-col items-center text-white">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span class="text-xs mt-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    Ubah Foto
                                                </span>
                                            </div>
                                            <input type="file"
                                                class="hidden"
                                                x-ref="fileInput"
                                                @change="handleImageUpload($event); checkChanges()"
                                                name="image"
                                                accept="image/*">
                                        </label>
                                    </div>
                                    <button x-show="imageUrl" @click.prevent="removeImage"
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
                        </div>

                        <!-- Right side - Form Fields -->
                        <div class="w-full md:w-2/3">
                            <div class="space-y-4">
                                <!-- Name -->
                                <div class="space-y-1.5">
                                    <label for="name" class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <input type="text"
                                            id="name"
                                            name="name"
                                            value="<?= $_SESSION['myProfile']['name'] ?? '' ?>"
                                            @input="checkChanges()"
                                            class="w-full pl-10 pr-4 py-2.5 text-sm sm:text-base rounded-xl border border-gray-200 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <!-- Username -->
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
                                            @input="checkChanges()"
                                            class="w-full pl-8 pr-4 py-2.5 text-sm sm:text-base rounded-xl border border-gray-200 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <!-- Email -->
                                <div class="space-y-1.5">
                                    <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input type="email"
                                            id="email"
                                            name="email"
                                            value="<?= $_SESSION['myProfile']['email'] ?? '' ?>"
                                            @input="checkChanges()"
                                            class="w-full pl-10 pr-4 py-2.5 text-sm sm:text-base rounded-xl border border-gray-200 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-100">
                        <button type="button"
                            @click="showPasswordModal = true"
                            class="px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors">
                            Ubah Password
                        </button>
                        <div class="flex items-center gap-3">
                            <button type="button"
                                @click="if(isFormChanged) {
                                    if(confirm('Batalkan perubahan?')) {
                                        window.location.reload();
                                    }
                                }"
                                :class="isFormChanged ? 'bg-gray-500 text-white hover:bg-gray-600' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                                :disabled="!isFormChanged"
                                class="px-4 py-2 rounded-xl transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                :disabled="!isFormChanged"
                                class="px-6 py-2 text-md font-medium text-white rounded-xl transition-all duration-300"
                                :class="isFormChanged ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-300 cursor-not-allowed'">
                                <span class="flex items-center gap-2">
                                    <span>Simpan Perubahan</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- PASSWORD CHANGE MODAL -->
            <div x-show="showPasswordModal"
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                <div @click.away="showPasswordModal = false"
                    x-transition:enter-start="opacity-0 transform scale-90"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-90"
                    class="bg-white rounded-2xl max-w-sm w-full mx-4 p-6 shadow-xl border border-gray-100/80 space-y-6">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-xl font-bold text-gray-800">Ubah Password</h2>
                        <button @click="showPasswordModal = false"
                            class="p-1.5 rounded-full text-gray-400 hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Flash message tampil di modal (akan reload jika gagal/sukses) -->
                    <div id="modalFlashMessage">
                        <?= Flasher::flash(); ?>
                    </div>

                    <!-- Ganti action ke doProfile dan tambahkan hidden input -->
                    <form action="<?= BASEURL ?>/dashboard/doProfile" method="POST" class="space-y-4" @submit="validatePasswordForm($event)">
                        <?= Helper::renderCSRFField(); ?>
                        <input type="hidden" name="change_password_only" value="1">
                        <!-- Old Password -->
                        <div>
                            <label for="old_password" class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input :type="showOldPassword ? 'text' : 'password'"
                                    id="old_password"
                                    name="old_password"
                                    required
                                    placeholder="Masukkan password lama"
                                    class="w-full pl-10 pr-10 py-2.5 text-sm rounded-xl border border-gray-200 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all"
                                    autocomplete="current-password">
                                <button type="button"
                                    @click="showOldPassword = !showOldPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg x-show="!showOldPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showOldPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input :type="showNewPassword ? 'text' : 'password'"
                                    id="password"
                                    name="password"
                                    required
                                    placeholder="Masukkan password baru"
                                    class="w-full pl-10 pr-10 py-2.5 text-sm rounded-xl border border-gray-200 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all"
                                    autocomplete="new-password">
                                <button type="button"
                                    @click="showNewPassword = !showNewPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg x-show="!showNewPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showNewPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Confirm New Password -->
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <input :type="showConfirmPassword ? 'text' : 'password'"
                                    id="confirm_password"
                                    name="confirm_password"
                                    required
                                    placeholder="Konfirmasi password baru"
                                    class="w-full pl-10 pr-10 py-2.5 text-sm rounded-xl border border-gray-200 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all"
                                    autocomplete="new-password">
                                <button type="button"
                                    @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Modal Actions -->
                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button"
                                @click="showPasswordModal = false"
                                class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-5 py-2 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 transition">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END MODAL -->
        </div>
    </div>
</div>

<!-- Alpine.js Logic -->
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
            reader.onload = (e) => {
                this.imageUrl = e.target.result;
                this.checkChanges();
            }
            reader.readAsDataURL(file);
        },
        removeImage() {
            this.imageUrl = null;
            this.$refs.fileInput.value = '';
            this.checkChanges();
        }
    }
}

function validateForm(e) {
    const form = e.target;
    const name = form.name.value.trim();
    const username = form.username.value.trim();
    const email = form.email.value.trim();

    if (!name || !username || !email) {
        alert('Nama, username, dan email wajib diisi!');
        e.preventDefault();
        return false;
    }

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Format email tidak valid!');
        e.preventDefault();
        return false;
    }
    return true;
}

function validatePasswordForm(e) {
    const form = e.target;
    const oldPassword = form.old_password.value;
    const newPassword = form.password.value;
    const confirmPassword = form.confirm_password.value;

    if (!oldPassword || !newPassword || !confirmPassword) {
        alert('Semua field password harus diisi!');
        e.preventDefault();
        return false;
    }

    if (newPassword !== confirmPassword) {
        alert('Password baru dan konfirmasi password tidak cocok!');
        e.preventDefault();
        return false;
    }

    if (newPassword.length < 6) {
        alert('Password baru minimal 6 karakter!');
        e.preventDefault();
        return false;
    }
    return true;
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../templates/dashboard.php';
?>