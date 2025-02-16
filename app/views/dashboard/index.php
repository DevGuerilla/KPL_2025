<?php ob_start();

?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/10 to-indigo-50/10">
    <!-- Dashboard Content -->
    <div class="p-6 sm:p-6 lg:p-8 -mt-5 md:mt-10">
        <!-- Welcome Banner -->
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
                            👋 Welcome back!
                        </span>
                        <span class="px-2 sm:px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs sm:text-sm flex items-center gap-1">
                            <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-green-400 rounded-full"></span> Online
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold"><?= $_SESSION['myProfile']['name'] ?? 'User' ?></h1>
                    <p class="text-sm sm:text-base text-white/80">Hari ini adalah waktu yang tepat untuk membuat konten baru!</p>
                </div>
                <div class="hidden md:block w-32 lg:w-64 flex-shrink-0">
                    <img src="<?= BASEURL ?>/img/hello.svg" alt="Welcome" class="w-full h-auto">
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
            <!-- Total Posts -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100/50 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-blue-50 rounded-lg sm:rounded-xl">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Total Posts</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-800"><?= count($data['posts'] ?? []) ?></p>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <div class="flex-1 bg-gray-100 h-1 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full" style="width: 75%"></div>
                    </div>
                    <span class="text-sm text-green-600">+12%</span>
                </div>
            </div>

            <!-- Views -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100/50 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-purple-50 rounded-lg sm:rounded-xl">
                        <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Total Views</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-800">1.2K</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <div class="flex-1 bg-gray-100 h-1 rounded-full overflow-hidden">
                        <div class="bg-purple-500 h-full rounded-full" style="width: 65%"></div>
                    </div>
                    <span class="text-sm text-green-600">+8%</span>
                </div>
            </div>

            <!-- More stat cards here -->
        </div>

        <!-- Recent Posts Section -->
        <div class="bg-white/80 backdrop-blur-xl rounded-xl sm:rounded-3xl shadow-sm border border-gray-100/50">
            <div class="p-4 sm:p-6 border-b border-gray-100/50">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Postingan Terbaru</h2>
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        <a href="<?= BASEURL ?>/dashboard/posts"
                            class="text-xs sm:text-sm text-gray-600 hover:text-gray-900 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl hover:bg-gray-50 transition-all duration-300">
                            Lihat Semua
                        </a>
                        <a href="<?= BASEURL ?>/dashboard/createpost"
                            class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg sm:rounded-xl hover:shadow-lg hover:shadow-blue-500/30 active:scale-95 transition-all duration-300">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Post
                        </a>
                    </div>
                </div>
            </div>

            <!-- Posts List -->
            <div class="divide-y divide-gray-100/50">
                <?php if (!empty($data['posts'])): ?>
                    <?php foreach ($data['posts'] as $post): ?>
                        <div class="group p-4 sm:p-6 flex flex-col sm:flex-row gap-4 sm:gap-6 sm:items-center hover:bg-gray-50/50 transition-all duration-300">
                            <img src="<?= BASEURL . '/img/posts/' . $post['image'] ?>"
                                alt="<?= $post['title'] ?>"
                                class="w-full sm:w-20 h-40 sm:h-20 rounded-xl object-cover ring-2 ring-gray-100 group-hover:ring-blue-100">

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <?= Helper::timeAgo($post['created_at']) ?>
                                    </span>
                                </div>
                                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-1 truncate group-hover:text-blue-600 transition-colors">
                                    <?= $post['title'] ?>
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-600 line-clamp-2 sm:line-clamp-1">
                                    <?= Helper::excerpt($post['content'], 100) ?>
                                </p>
                            </div>

                            <div class="flex sm:flex-col items-center gap-2">
                                <a href="<?= BASEURL ?>/dashboard/editpost/<?= $post['id_post'] ?>"
                                    class="flex-1 sm:flex-none w-full sm:w-auto px-4 py-2 sm:p-2 text-center text-xs sm:text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-300">
                                    Edit
                                </a>
                                <a href="<?= BASEURL ?>/posts/detail/<?= $post['id_post'] ?>"
                                    class="flex-1 sm:flex-none w-full sm:w-auto px-4 py-2 sm:p-2 text-center text-xs sm:text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-300">
                                    Lihat
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-8 sm:py-12 px-4">
                        <img src="<?= BASEURL ?>/img/nodata.svg"
                            alt="No Posts"
                            class="w-32 h-32 sm:w-48 sm:h-48 mx-auto mb-4">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum ada postingan</h3>
                        <p class="text-sm text-gray-500 mb-6">Mulai menulis artikel pertama Anda</p>
                        <a href="<?= BASEURL ?>/dashboard/createpost"
                            class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-300">
                            Buat Postingan Pertama
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../templates/dashboard.php';
?>