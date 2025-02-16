<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<div class="min-h-screen flex items-center justify-center p-4"
    x-data="{ show: false }"
    x-init="setTimeout(() => show = true, 150)">
    <div x-cloak
        x-show="show"
        x-transition:enter="transition duration-1000 ease-out"
        x-transition:enter-start="opacity-0 -translate-y-60"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="bg-white w-full max-w-6xl rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <div class="flex flex-col md:flex-row">
            <div class="w-full md:w-1/2 p-8 lg:p-12">
                <div class="max-w-md mx-auto">
                    <div class="text-center md:text-left">
                        <a href="<?= BASEURL; ?>">
                            <img src="<?= BASEURL; ?>/img/logo_only1.png" alt="Uptime Logo" class="mb-3 w-auto mx-auto md:mx-0 transform hover:scale-105 transition-transform duration-500">
                        </a>
                        <h3 class="text-2xl font-bold text-gray-900">Masuk ke Blog Campus</h3>
                        <p class="my-2 text-base text-gray-600">Bagikan cerita dan pengalamanmu di kampus</p>
                    </div>

                    <?php Flasher::flash(); ?>

                    <form action="<?= BASEURL; ?>/auth/doLogin" method="POST" class="space-y-6 mt-8"
                        x-data="{ loading: false }">
                        <div class="transform transition duration-300 hover:-translate-y-1">
                            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                            <div class="mt-1 relative">
                                <input id="username"
                                    name="username"
                                    type="text"
                                    required
                                    placeholder="Masukkan username Anda"
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition duration-300 shadow-sm hover:shadow-md">
                            </div>
                        </div>

                        <div class="transform transition duration-300 hover:-translate-y-1"
                            x-data="{ showPassword: false }">
                            <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                            <div class="mt-1 relative">
                                <input :type="showPassword ? 'text' : 'password'"
                                    id="password"
                                    name="password"
                                    required
                                    placeholder="Masukkan kata sandi"
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition duration-300 shadow-sm hover:shadow-md pr-10">
                                <button type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center transition-opacity duration-300 outline-none"
                                    :class="{ 'opacity-70': !showPassword, 'opacity-100': showPassword }">
                                    <svg x-show="!showPassword" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    <svg x-show="showPassword" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="transform transition duration-300 hover:-translate-y-1">
                            <button type="submit"
                                @click="loading = true"
                                class="w-full flex justify-center py-3 px-4 border-0 rounded-lg text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl outline-none"
                                :class="{ 'opacity-75 cursor-wait': loading }">
                                <span x-show="!loading">Mulai Menulis</span>
                                <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="text-center transform transition duration-300 hover:-translate-y-1">
                            <p class="text-sm text-gray-600">
                                Belum punya akun?
                                <a href="<?= BASEURL; ?>/auth/register" class="font-medium text-blue-500 hover:text-blue-600 transition-colors duration-300">
                                    Bergabung Sekarang
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="w-full md:w-1/2 bg-gradient-to-br from-blue-500 to-blue-600 p-8 lg:p-12 flex items-center">
                <div class="max-w-md mx-auto text-center text-white">
                    <h2 class="text-2xl md:text-3xl font-bold mb-4">Selamat Datang di Blog Campus!</h2>
                    <p class="text-base md:text-lg mb-4 md:mb-8 hidden md:block">Tempat berbagi inspirasi, pengalaman, dan cerita menarik seputar kehidupan kampus.</p>
                </div>
            </div>
        </div>
    </div>
</div>