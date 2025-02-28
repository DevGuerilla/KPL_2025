<?php include_once __DIR__ . '/../templates/navbar.php';
include_once __DIR__ . '/../../core/Helper.php';

?>

<div class="min-h-screen bg-[#f8fafc] py-8 sm:py-12 px-4 sm:px-6 lg:px-8 pt-24 sm:pt-36 font-poppins relative">
    <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-gradient-to-bl from-blue-100/40 to-transparent rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-1/3 h-1/3 bg-gradient-to-tr from-indigo-100/40 to-transparent rounded-full blur-3xl"></div>

    <div class="lg:hidden flex justify-center gap-4 mb-8">
        <button class="p-3 rounded-full bg-white shadow-sm hover:bg-blue-500 hover:text-white transition-all duration-300">
            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
            </svg>
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="relative h-[400px] w-full overflow-hidden">
            <img src="<?= BASEURL . "/img/posts/" . $data['post']['post']['image']; ?>" alt="Post cover" 
                 class="w-full h-full object-cover">
        </div>

        <div class="p-6 sm:p-8" x-data="{ showComments: true }">
            <div class="flex justify-between items-start mb-6">
                <div class="flex-1">
                    <h1 class="text-2xl sm:text-3xl font-bold text-[#1e293b] mb-4">
                        <?= htmlspecialchars($data['post']['post']['title']); ?>
                    </h1>

                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full 
                                      flex items-center justify-center text-blue-600 text-base font-medium 
                                      shadow-sm border-2 border-blue-50">
                                <?= strtoupper(substr($data['post']['post']['name'], 0, 2)); ?>
                            </div>
                            <div class="px-4 py-2 bg-white rounded-full shadow-sm">
                                <span class="text-gray-800 font-medium">
                                    <?= htmlspecialchars($data['post']['post']['name']); ?>
                                </span>
                            </div>
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        </div>
                    </div>
                </div>
                <div class="text-gray-600 text-sm py-2">
                    <?php 
                        $created_at = strtotime($data['post']['post']['created_at']);
                        $updated_at = strtotime($data['post']['post']['updated_at']);
                        $is_updated = $created_at != $updated_at;
                    ?>

                    <div class="flex items-center space-x-2">
                        <?php if ($is_updated): ?>
                            <!-- Tampilkan Updated -->
                            <div class="relative group">
                                <div class="flex items-center space-x-1">
                                    <span class="text-xs bg-gray-200 px-2 py-1 rounded-md text-gray-700">Updated</span>
                                    <p class="text-gray-700 font-medium">
                                        <?= htmlspecialchars(date('d M Y, H:i', $updated_at)); ?>
                                    </p>
                                </div>


                                <!-- Tampilkan Created saat hover -->
                                <div class="absolute left-0 mt-1 hidden group-hover:block bg-white shadow-md border text-gray-700 text-xs px-3 py-2 rounded-md">
                                    Created: <?= htmlspecialchars(date('d M Y, H:i', $created_at)); ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Jika tidak ada pembaruan, tampilkan Created -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-700 font-medium">
                                <?= htmlspecialchars(date('d M Y, H:i', $created_at)); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="prose max-w-none text-gray-600 mb-8">
                <?= $data['post']['post']['content']; ?>
            </div>

            <div class="flex flex-wrap gap-2 mb-6 pt-4 border-t border-gray-100">
                <?php foreach ($data['post']['tags'] as $tag): ?>
                    <span class="px-4 py-2 bg-blue-50 rounded-full text-blue-600 text-sm font-medium 
                                hover:bg-blue-100 transition-colors cursor-pointer">
                        #<?= htmlspecialchars($tag['tag_name']); ?>
                    </span>
                <?php endforeach; ?>
            </div>

            <div class="flex justify-between items-center py-4 border-t border-gray-100">
                <button @click="showComments = !showComments" 
                        class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>Komentar</span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded-full text-xs font-medium ml-1">
                        <?= count($data['post']['comments']); ?>
                    </span>
                </button>
                <button class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                    </svg>
                    <span>Bagikan</span>
                </button>
            </div>

            <div x-show="showComments" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="mt-8 pt-8 border-t border-gray-100">
                
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Diskusi
                    </h3>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        <?= count($data['post']['comments']); ?> Komentar
                    </span>
                </div>

                <div class="mb-8">
                    <?php Flasher::flash(); ?>
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-blue-600 text-sm font-medium shadow-sm border-2 border-blue-100">
                                <?= isset($_SESSION['myProfile']) ? strtoupper(substr($_SESSION['myProfile']['username'], 0, 2)) : 'G' ?>
                            </div>
                            <div class="flex-1">
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-sm mb-4">
                                    <span class="font-medium text-gray-900">
                                        <?= isset($_SESSION['myProfile']) ? htmlspecialchars($_SESSION['myProfile']['username']) : 'Tamu' ?>
                                    </span>
                                    <?php if (isset($_SESSION['isLoggedIn'])): ?>
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <?php endif; ?>
                                </div>
                                <form method="POST" action="<?= BASEURL; ?>/posts/detail/<?= $data['post']['post']['id_post']; ?>" class="mt-2">
                                    <?php if (isset($_SESSION['isLoggedIn'])): ?>
                                        <input type="hidden" name="username" value="<?= htmlspecialchars($_SESSION['myProfile']['username']); ?>">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? ''; ?>">
                                    <?php else: ?>
                                        <input type="hidden" name="username" value="Tamu">
                                    <?php endif; ?>
                                    
                                    <textarea name="comment" rows="3" required
                                            class="w-full p-4 bg-white border border-blue-100 rounded-xl focus:ring-2 focus:ring-blue-500 
                                                   focus:border-transparent transition-all resize-none outline-none shadow-sm mb-3"
                                            placeholder="Bagikan pendapat Anda..."></textarea>
                                    
                                    <div class="flex justify-end">
                                        <button type="submit"
                                                class="px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 
                                                       transition-all duration-300 flex items-center gap-2 shadow-md hover:shadow-lg">
                                            <span>Kirim</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <?php foreach ($data['post']['comments'] as $comment) : ?>
                        <div class="group">
                            <div class="flex items-start gap-4 bg-white p-6 rounded-2xl shadow-sm 
                                    transition-all duration-300 hover:shadow-md hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-white">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full 
                                        flex items-center justify-center text-blue-600 text-sm font-medium shadow-sm">
                                    <?= strtoupper(substr($comment['username'], 0, 2)); ?>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-3">
                                        <div class="gap-3">
                                            <span class="font-medium text-gray-900">
                                                <?= htmlspecialchars($comment['username']); ?>
                                            </span>
                                            <div class="text-gray-500 text-sm">
                                            <?= Helper::timeAgo($comment['created_at']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pl-4 border-l-2 border-blue-100 group-hover:border-blue-300 transition-all">
                                        <p class="text-slate-700 transition-all text-lg">
                                            <?= htmlspecialchars($comment['comment']); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto mt-12">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
            Artikel Terkait
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <?php foreach ($data['post']['randomPosts'] as $post) : ?>
                <a href="<?= BASEURL; ?>/posts/detail/<?= $post['id_post']; ?>" 
                   class="group block bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                    <div class="relative h-48 overflow-hidden">
                        <img src="/api/placeholder/400/200" alt="Related Post"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                            <?= htmlspecialchars($post['title']); ?>
                        </h4>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full 
                                          flex items-center justify-center text-blue-600 text-xs font-medium">
                                    <?= strtoupper(substr($post['name'], 0, 2)); ?>
                                </div>
                                <div class="px-3 py-1 bg-blue-50 rounded-full">
                                    <span class="text-sm text-gray-800">
                                        <?= htmlspecialchars($post['name']); ?>
                                    </span>
                                </div>
                            </div>
                            <span class="text-gray-500 text-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= htmlspecialchars($post['created_at']); ?>
                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['flash'])): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const commentsSection = document.querySelector('[x-show="showComments"]');
        if (commentsSection) {
            commentsSection.scrollIntoView({ behavior: 'smooth' });
        }
    });
</script>
<?php endif; ?>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>