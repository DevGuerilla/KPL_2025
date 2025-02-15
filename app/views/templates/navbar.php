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
                                    <div class="w-full h-full flex items-center justify-center text-white font-medium">
                                        <?= substr(htmlspecialchars($_SESSION['myProfile']['username']), 0, 1); ?>
                                    </div>
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
                                        d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        <div x-show="open" 
                            x-ref="dropdown"
                            @click.away="toggleDropdown()"
                            class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl py-2 ring-1 ring-black/5 transform origin-top"
                            style="display: none;">
                            
                            <div class="p-2 space-y-1">
                                <a href="<?= BASEURL; ?>/profile" 
                                    class="flex items-center gap-3 p-2.5 rounded-xl text-gray-700 hover:bg-blue-50 transition-all duration-300 group">
                                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100 transition-colors duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium group-hover:text-blue-600 transition-colors duration-300">Dashbaord</p>
                                        <p class="text-xs text-gray-500">Kelola informasi personal</p>
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
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape="searchOpen = false"
        class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm">

        <div class="container mx-auto px-4 pt-28" @click.away="searchOpen = false">
            <div class="bg-white rounded-2xl p-6 shadow-xl max-w-2xl mx-auto transform transition-all"
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
                            @input.debounce.300ms="isLoading = true; setTimeout(() => isLoading = false, 1000)"
                            class="w-full text-sm outline-none placeholder-gray-400"
                            placeholder="Cari blog atau artikel...">
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
                        <div class="flex items-center justify-center space-x-2 text-blue-600">
                            <div class="w-2 h-2 rounded-full bg-blue-600 animate-bounce [animation-delay:-0.3s]"></div>
                            <div class="w-2 h-2 rounded-full bg-blue-600 animate-bounce [animation-delay:-0.15s]"></div>
                            <div class="w-2 h-2 rounded-full bg-blue-600 animate-bounce"></div>
                        </div>
                    </div>

                    <div x-show="!isLoading && searchQuery.length >= 2" 
                        class="py-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm">Tidak ada hasil yang ditemukan untuk pencarian ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
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