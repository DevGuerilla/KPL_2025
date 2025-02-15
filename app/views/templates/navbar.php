<!-- Navigation -->
<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left side -->
            <div class="flex">
                <a href="<?= BASEURL; ?>" class="flex items-center text-lg font-semibold text-gray-800">
                    PHP MVC
                </a>
                
                <!-- Main nav links -->
                <div class="hidden md:ml-6 md:flex md:space-x-4">
                    <a href="<?= BASEURL; ?>" class="inline-flex items-center px-3 py-2 text-gray-600 hover:text-gray-900">
                        Home
                    </a>
                    <a href="<?= BASEURL; ?>/posts" class="inline-flex items-center px-3 py-2 text-gray-600 hover:text-gray-900">
                        Posts
                    </a>
                </div>
            </div>

            <!-- Right side -->
            <div class="flex items-center">
                <span class="text-gray-600 mr-4">
                    Hello, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>!
                </span>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASEURL; ?>/auth/logout" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="<?= BASEURL; ?>/auth/login" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Login
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button type="button" 
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100"
                        aria-controls="mobile-menu" 
                        aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!-- Icon when menu is closed -->
                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="<?= BASEURL; ?>" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                Home
            </a>
            <a href="<?= BASEURL; ?>/posts" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                Posts
            </a>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<?php if (isset($_SESSION['flash'])): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <?php Flasher::flash(); ?>
    </div>
<?php endif; ?>

<!-- Remove duplicate greeting since it's already in navbar -->