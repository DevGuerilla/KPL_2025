<?php include_once __DIR__ . '/../templates/header.php'; ?>

<div x-data="{ 
    sidebarOpen: false,
    currentPath: window.location.pathname,
    init() {
        // Set current path saat komponen dimuat
        this.currentPath = window.location.pathname;
    }
}">
    <!-- Sidebar -->
    <div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
        class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out z-20 pt-24">
        <div class="p-6">
            <nav class="space-y-2">
                <!-- Dashboard Link -->
                <a href="<?= BASEURL; ?>/dashboard" 
                   class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition-all duration-300 group"
                   :class="currentPath === '<?= BASEURL; ?>/dashboard' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50'">
                    <div class="w-10 h-10 flex items-center justify-center rounded-lg transition-all duration-300"
                         :class="currentPath === '<?= BASEURL; ?>/dashboard' ? 'bg-blue-100 text-blue-600' : 'bg-gray-50 text-gray-600 group-hover:bg-gray-100'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium">Dashboard</p>
                        <p class="text-xs text-gray-500">Overview</p>
                    </div>
                </a>

                <!-- Create Post Link -->
                <a href="<?= BASEURL; ?>/dashboard/createpost" 
                   class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition-all duration-300 group"
                   :class="currentPath === '<?= BASEURL; ?>/dashboard/createpost' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50'">
                    <div class="w-10 h-10 flex items-center justify-center rounded-lg transition-all duration-300"
                         :class="currentPath === '<?= BASEURL; ?>/dashboard/createpost' ? 'bg-blue-100 text-blue-600' : 'bg-gray-50 text-gray-600 group-hover:bg-gray-100'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium">Buat Post</p>
                        <p class="text-xs text-gray-500">Tulis artikel baru</p>
                    </div>
                </a>

                <!-- Posts Link -->
                <a href="<?= BASEURL; ?>/dashboard/posts" 
                   class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition-all duration-300 group"
                   :class="currentPath === '<?= BASEURL; ?>/dashboard/posts' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50'">
                    <div class="w-10 h-10 flex items-center justify-center rounded-lg transition-all duration-300"
                         :class="currentPath === '<?= BASEURL; ?>/dashboard/posts' ? 'bg-blue-100 text-blue-600' : 'bg-gray-50 text-gray-600 group-hover:bg-gray-100'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium">Post Saya</p>
                        <p class="text-xs text-gray-500">Kelola artikel</p>
                    </div>
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen bg-gray-50">
        <!-- Top Navigation -->
        <?php include_once __DIR__ . '/../templates/navbar.php'; ?>


        <!-- Content Area -->
        <main class="p-4 sm:p-6 lg:p-8 pt-28">
            <?php 
            if (isset($view)) {
                $viewPath = "../app/views/{$view}.php";
                if (file_exists($viewPath)) {
                    require_once $viewPath;
                } else {
                    echo "Error: View file tidak ditemukan: " . $view;
                }
            }
            ?>
        </main>
    </div>

    <!-- Mobile Sidebar Toggle Button -->
    <button @click="sidebarOpen = !sidebarOpen"
        class="lg:hidden fixed bottom-6 right-6 p-3 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition-colors z-30">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            :class="{'rotate-90': sidebarOpen}">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
