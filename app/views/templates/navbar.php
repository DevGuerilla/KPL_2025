<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<div class="relative z-50" x-data="{ 
    mobileMenuOpen: false,
    scrolled: false,
    searchOpen: false,
    searchQuery: '',
    searchResults: [],
    isLoading: false
}" @scroll.window="scrolled = window.pageYOffset > 20">

    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 bg-slate-50 shadow-sm rounded-b-xl opacity-95"
        :class="{ 'shadow-md': scrolled }">
        <div class="max-w-[90%] mx-auto">
            <div class="flex items-center justify-between h-20 px-6">
                <div class="flex items-center">
                    <a href="<?= BASEURL; ?>" class="group flex items-center">
                        <img src="<?= BASEURL; ?>/img/logo_only.png" alt="Logo" class="h-10 w-auto transform transition-transform duration-300 group-hover:scale-105 object-scale-down">
                    </a>
                </div>

                <div class="flex items-center gap-6">
                    <button @click="searchOpen = true"
                        class="p-2.5 hover:bg-gray-100 rounded-full transition-all duration-300 hover:scale-110">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    <?php if (!isset($_SESSION['isLoggedIn'])): ?>
                        <div class="flex items-center gap-4">
                            <a href="<?= BASEURL; ?>/auth/login"
                                class="px-6 py-2.5 text-sm font-medium text-gray-700 hover:text-blue-600 rounded-xl hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                                Masuk
                            </a>
                            <a href="<?= BASEURL; ?>/auth/register"
                                class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-blue-200">
                                Daftar
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="relative" x-data="{ 
                        open: false,
                        showLogoutConfirm: false,
                        async toggleDropdown() {
                            if (!this.open) {
                                this.open = true;
                                await this.$nextTick();
                                this.$refs.dropdown.classList.add('dropdown-enter');
                            } else {
                                this.$refs.dropdown.classList.add('dropdown-leave');
                                setTimeout(() => {
                                    this.open = false;
                                    this.$refs.dropdown.classList.remove('dropdown-leave');
                                }, 300);
                            }
                        }
                    }">
                            <button @click="toggleDropdown()"
                                class="relative flex items-center gap-3 p-2.5 hover:bg-gray-100 rounded-xl transition-all duration-300 group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full overflow-hidden ring-2 ring-white shadow-lg transition-all duration-300 group-hover:shadow-blue-200">
                                        <img src="<?= BASEURL ?>/img/users/<?= $_SESSION['myProfile']['profile_picture_url'] ?? 'default.jpg' ?>" class="w-full h-full flex items-center justify-center text-white font-medium">

                                        </img>
                                    </div>
                                    <div class="hidden md:block text-left">
                                        <p class="text-sm font-semibold text-gray-900 line-clamp-1 group-hover:text-blue-600 transition-colors duration-300">
                                            <?= htmlspecialchars($_SESSION['myProfile']['username']); ?>
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            <?= htmlspecialchars($_SESSION['myProfile']['email']); ?>
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-300 group-hover:text-blue-600"
                                        :class="{'rotate-180': open}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </button>

                            <div x-show="open"
                                x-ref="dropdown"
                                @click.away="toggleDropdown()"
                                class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl py-2 ring-1 ring-black/5 transform origin-top"
                                style="display: none;">

                                <div class="p-2 space-y-1">
                                    <a href="<?= BASEURL; ?>/dashboard/profile"
                                        class="flex items-center gap-3 p-2.5 rounded-xl text-gray-700 hover:bg-blue-50 transition-all duration-300 group">
                                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100 transition-colors duration-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium group-hover:text-blue-600 transition-colors duration-300">Profile</p>
                                            <p class="text-xs text-gray-500">Kelola informasi personal</p>
                                        </div>
                                    </a>

                                    <div class="h-px bg-gray-100 my-2"></div>

                                    <a href="<?= BASEURL; ?>/dashboard"
                                        class="flex items-center gap-3 p-2.5 rounded-xl text-gray-700 hover:bg-blue-50 transition-all duration-300 group">
                                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100 transition-colors duration-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium group-hover:text-blue-600 transition-colors duration-300">Dashboard</p>
                                            <p class="text-xs text-gray-500">Kelola halaman utama</p>
                                        </div>
                                    </a>

                                    <div class="h-px bg-gray-100 my-2"></div>

                                    <form action="<?= BASEURL; ?>/auth/logout" method="post">
                                        <button type="submit"
                                            class="w-full flex items-center gap-3 p-2.5 rounded-xl text-gray-700 hover:bg-red-50 transition-all duration-300 group">
                                            <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg bg-red-50 text-red-600 group-hover:bg-red-100 transition-colors duration-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 text-left">
                                                <p class="text-sm font-medium group-hover:text-red-600 transition-colors duration-300">Keluar</p>
                                                <p class="text-xs text-gray-500">Akhiri sesi anda</p>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div x-show="searchOpen"
        x-cloak
        @keydown.window.ctrl.k.prevent="searchOpen = true"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape="searchOpen = false"
        class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm">

        <div class="container mx-auto px-4 pt-16 sm:pt-28" @click.away="searchOpen = false">
            <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-xl max-w-2xl mx-auto transform transition-all"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0">

                <div class="relative">
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text"
                            x-ref="searchInput"
                            x-model="searchQuery"
                            @input.debounce.300ms="
                                if(searchQuery.length >= 2) {
                                    isLoading = true;
                                    const encodedQuery = encodeURIComponent(searchQuery);
                                    fetch(`<?= BASEURL ?>/posts/search/${encodedQuery}`)
                                        .then(response => {
                                            if (!response.ok) throw new Error('Network response was not ok');
                                            return response.json();
                                        })
                                        .then(data => {
                                            searchResults = Array.isArray(data.data) ? data.data : [];
                                            isLoading = false;
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            searchResults = [];
                                            isLoading = false;
                                        });
                                } else {
                                    searchResults = [];
                                }
                            "
                            class="w-full text-sm outline-none border border-gray-200 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-lg px-4 py-3 transition-all duration-300"
                            placeholder="Cari blog atau artikel... (Ctrl + K)">
                        <kbd class="hidden md:inline-flex items-center px-3 py-1.5 text-xs font-semibold text-gray-600 bg-gray-100 border border-gray-200 rounded-lg shadow-sm">⌘K</kbd>
                        <button type="button"
                            @click="searchOpen = false"
                            class="p-2 hover:bg-gray-100 rounded-xl transition-all duration-300">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="isLoading" class="flex justify-center py-8">
                        <div class="animate-pulse flex space-x-4">
                            <div class="rounded-lg bg-gray-200 h-24 w-24"></div>
                            <div class="flex-1 space-y-4">
                                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                <div class="space-y-2">
                                    <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                                    <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="!isLoading && searchQuery.length >= 2"
                        class="mt-4 max-h-[60vh] overflow-y-auto custom-scrollbar">
                        <template x-if="searchResults && searchResults.length > 0">
                            <div class="space-y-4">
                                <template x-for="result in searchResults" :key="result.id_post">
                                    <a :href="`<?= BASEURL ?>/posts/detail/${result.id_post}`"
                                        class="block bg-white p-4 hover:bg-gray-50 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg border border-gray-100">
                                        <div class="flex flex-col sm:flex-row gap-4">
                                            <div class="w-full sm:w-24 h-48 sm:h-24 bg-gray-100 rounded-xl overflow-hidden">
                                                <img :src="`<?= BASEURL ?>/img/posts/${result.thumbnail}`"
                                                    class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500">
                                            </div>
                                            <div class="flex-1">
                                                <h3 x-text="result.title"
                                                    class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 hover:text-blue-600"></h3>
                                                <p x-text="result.content"
                                                    class="text-sm text-gray-600 line-clamp-2 mb-3"></p>
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <template x-for="tag in result.tags" :key="tag">
                                                        <span class="px-3 py-1.5 text-xs font-medium bg-blue-50 text-blue-600 rounded-lg shadow-sm hover:shadow-md hover:bg-blue-100 transition-all duration-300"
                                                            x-text="tag"></span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </template>

                        <template x-if="!searchResults || searchResults.length === 0">
                            <div class="py-12 text-center">
                                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-lg">Tidak ada hasil yang ditemukan untuk pencarian ini.</p>
                                <p class="text-gray-400 text-sm mt-2">Coba kata kunci lain</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 20px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(156, 163, 175, 0.8);
        }
    </style>
</div>

<style>
    @keyframes slideDown {
        0% {
            opacity: 0;
            transform: translateY(-8px) scale(0.95);
        }

        70% {
            transform: translateY(2px) scale(1.01);
        }

        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes slideUp {
        0% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        100% {
            opacity: 0;
            transform: translateY(-8px) scale(0.95);
        }
    }

    .dropdown-enter {
        animation: slideDown 0.4s ease-out forwards;
    }

    .dropdown-leave {
        animation: slideUp 0.3s ease-in forwards;
    }
</style>