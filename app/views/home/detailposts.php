<?php include_once __DIR__ . '/../templates/navbar.php'; ?>

<div class="min-h-screen bg-[#f8fafc] py-8 sm:py-12 px-4 sm:px-6 lg:px-8 pt-24 sm:pt-36 font-poppins relative">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-gradient-to-bl from-blue-100/40 to-transparent rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-1/3 h-1/3 bg-gradient-to-tr from-indigo-100/40 to-transparent rounded-full blur-3xl"></div>

    <!-- Post Card -->
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Post Image -->
        <div class="relative h-[400px] w-full overflow-hidden">
            <img src="https://picsum.photos/1200/800" alt="Post cover" 
                 class="w-full h-full object-cover">
        </div>

        <!-- Content Container -->
        <div class="p-6 sm:p-8">
            <!-- Title -->
            <h1 class="text-2xl sm:text-3xl font-bold text-[#1e293b] mb-4">
                <?= htmlspecialchars($data['post']['post']['title']); ?>
            </h1>

            <!-- Author Info -->
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-sky-100 rounded-full flex items-center justify-center text-sky-600 text-sm font-medium">
                        <?= strtoupper(substr($data['post']['post']['name'], 0, 2)); ?>
                    </div>
                    <span class="text-gray-600 text-sm">
                        <?= htmlspecialchars($data['post']['post']['name']); ?>
                    </span>
                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                    <span class="text-gray-500 text-sm">
                        <?= htmlspecialchars($data['post']['post']['created_at']); ?>
                    </span>
                </div>
            </div>

            <!-- Tags -->
            <div class="flex flex-wrap gap-2 mb-6">
                <?php foreach ($data['post']['tags'] as $tag): ?>
                    <span class="px-3 py-1 bg-gray-100 rounded-full text-gray-600 text-sm">
                        #<?= htmlspecialchars($tag['tag_name']); ?>
                    </span>
                <?php endforeach; ?>
            </div>

            <!-- Content -->
            <div class="prose max-w-none text-gray-600 mb-8">
                <?= $data['post']['post']['content']; ?>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center py-4 border-t border-gray-100">
                <button class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>Comments</span>
                </button>
                <button class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                    </svg>
                    <span>Share</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="max-w-4xl mx-auto mt-8">
        <section class="bg-white rounded-xl shadow-lg p-6 sm:p-8" x-data="{ isCommenting: false }">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Comments</h3>

            <!-- Comment Form -->
            <div class="mb-8">
                <?php Flasher::flash(); ?>
                <button @click="isCommenting = !isCommenting"
                        x-show="!isCommenting"
                        class="w-full px-4 py-3 text-left text-gray-600 rounded-lg border border-gray-200
                               hover:bg-gray-50 transition-colors">
                    Share your thoughts...
                </button>
                <form x-show="isCommenting" @click.away="isCommenting = false"
                      class="space-y-4" method="POST" action="<?= BASEURL; ?>/posts/detail/<?= $data['post']['post']['id_post']; ?>">
                    <?php if (isset($_SESSION['isLoggedIn'])): ?>
                        <input type="text" required name="username"
                               class="w-full p-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 
                                     focus:border-transparent transition-all"
                               placeholder="Username" value="<?= htmlspecialchars($_SESSION['myProfile']['username']); ?>">
                    <?php else: ?>
                        <input type="text" required name="username"
                               class="w-full p-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 
                                     focus:border-transparent transition-all"
                               placeholder="Username" value="">
                    <?php endif; ?>
                    
                    <textarea rows="4" name="comment" required
                              class="w-full p-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 
                                     focus:border-transparent transition-all resize-none"
                              placeholder="Write your comment..."></textarea>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="isCommenting = false"
                                class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 
                                       transition-colors">
                            Post Comment
                        </button>
                    </div>
                </form>
            </div>

            <!-- Comments List -->
            <div class="space-y-6">
                <?php foreach ($data['post']['comments'] as $comment) : ?>
                    <div class="border-t border-gray-100 pt-6">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-sky-100 rounded-full flex items-center justify-center text-sky-600 text-sm font-medium">
                                <?= strtoupper(substr($comment['username'], 0, 2)); ?>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-900">
                                        <?= htmlspecialchars($comment['username']); ?>
                                    </span>
                                    <span class="text-gray-500 text-sm">
                                        <?= htmlspecialchars($comment['created_at']); ?>
                                    </span>
                                </div>
                                <p class="text-gray-600">
                                    <?= htmlspecialchars($comment['comment']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <!-- Related Posts -->
    <div class="max-w-4xl mx-auto mt-12">
        <h3 class="text-xl font-bold text-gray-900 mb-6">More Posts</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <?php foreach ($data['post']['randomPosts'] as $post) : ?>
                <a href="<?= BASEURL; ?>/posts/detail/<?= $post['id_post']; ?>" 
                   class="block bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <img src="https://picsum.photos/400/200" alt="Related Post"
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 mb-2">
                            <?= htmlspecialchars($post['title']); ?>
                        </h4>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <span><?= htmlspecialchars($post['name']); ?></span>
                            <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                            <span><?= htmlspecialchars($post['created_at']); ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>