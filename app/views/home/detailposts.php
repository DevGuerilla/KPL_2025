<?php include_once __DIR__ . '/../templates/navbar.php'; ?>

<div class="min-h-screen bg-[#f8fafc] py-8 sm:py-12 px-4 sm:px-6 lg:px-8 pt-24 sm:pt-36 font-poppins relative">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-gradient-to-bl from-blue-100/40 to-transparent rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-1/3 h-1/3 bg-gradient-to-tr from-indigo-100/40 to-transparent rounded-full blur-3xl"></div>

    <!-- Mobile Social Share -->
    <div class="lg:hidden flex justify-center gap-4 mb-8">
        <button class="p-3 rounded-full bg-white shadow-sm hover:bg-blue-500 hover:text-white transition-all duration-300">
            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
            </svg>
        </button>
        <button class="p-3 rounded-full bg-white shadow-sm hover:bg-blue-500 hover:text-white transition-all duration-300">
            <svg class="w-5 h-5 text-sky-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
            </svg>
        </button>
        <button class="p-3 rounded-full bg-white shadow-sm hover:bg-blue-500 hover:text-white transition-all duration-300">
            <svg class="w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
            </svg>
        </button>
        <button class="p-3 rounded-full bg-white shadow-sm hover:bg-blue-500 hover:text-white transition-all duration-300">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
            </svg>
        </button>
    </div>

    <!-- Side Navigation -->
    <div class="fixed left-8 top-1/2 transform -translate-y-1/2 space-y-4 hidden xl:block">
        <div class="flex flex-col items-center space-y-6">
            <button class="group p-3 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-300">
                <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
            <div class="h-24 w-0.5 bg-gray-200 rounded-full"></div>
            <button class="group p-3 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-300">
                <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <article class="max-w-4xl mx-auto relative">
        <!-- Enhanced Header Section -->
        <header class="relative mb-8 sm:mb-16 text-center">
            <div class="absolute inset-0 -skew-y-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-3xl -z-10"></div>
            <div class="pt-8 sm:pt-12 pb-6 sm:pb-8 px-4 sm:px-6">
                <h1 class="text-3xl sm:text-4xl md:text-6xl font-bold text-gray-800 mb-4 sm:mb-6 leading-tight
                           hover:scale-[1.01] transition-all duration-500 cursor-default">
                    <?= $data['post']['post']['title']; ?>
                </h1>

                <!-- Author Info & Meta - Mobile Optimized -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8 mt-6 sm:mt-8">
                    <div class="flex items-center group bg-white/50 backdrop-blur-sm px-4 py-2 rounded-2xl w-full sm:w-auto
                               hover:bg-white transition-all duration-300 shadow-sm hover:shadow-md">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full 
                                      animate-pulse opacity-20"></div>
                            <img src="https://ui-avatars.com/api/?name=John+Doe&background=random" alt="Author"
                                class="w-12 h-12 rounded-full border-2 border-white shadow-md relative">
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 
                                      border-white animate-bounce"></div>
                        </div>
                        <div class="ml-3 transform group-hover:translate-x-2 transition-transform duration-300">
                            <p class="text-sm font-semibold text-gray-900"><?= $data['post']['post']['name']; ?></p>
                            <p class="text-xs text-gray-500"><?= $data['post']['post']['created_at']; ?></p>
                        </div>
                    </div>


                </div>
            </div>
        </header>

        <!-- Enhanced Tags - Mobile Optimized -->
        <div class="flex flex-wrap gap-2 sm:gap-3 justify-center -mt-4 sm:-mt-8 mb-8 sm:mb-12 relative z-10 px-4">
            <?php foreach ($data['post']['tags'] as $tag): ?>
                <span class="group relative px-5 py-2 bg-white rounded-xl shadow-sm border border-gray-100
                        hover:shadow-md transition-all duration-300 cursor-pointer">
                    <div class="absolute inset-0 bg-blue-500 opacity-0 group-hover:opacity-100 
                           rounded-xl transition-opacity duration-300 -z-10"></div>
                    <span class="relative text-gray-700 group-hover:text-white font-medium transition-colors duration-300">
                        #<?= $tag['tag_name']; ?>
                    </span>
                </span>
            <?php endforeach; ?>
        </div>

        <!-- Content Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-7 gap-6 sm:gap-8 mt-8 sm:mt-12">
            <!-- Social Share Sidebar -->
            <div class="lg:col-span-1 hidden lg:block">
                <div class="sticky top-36 flex flex-col items-center space-y-4">
                    <span class="text-sm text-gray-500 font-medium">Share</span>
                    <button class="p-3 rounded-full bg-white shadow-sm hover:bg-blue-500 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </button>
                    <button class="p-3 rounded-full bg-white shadow-sm hover:bg-blue-500 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 text-sky-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                    </button>
                    <button class="p-3 rounded-full bg-white shadow-sm hover:bg-blue-500 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
                    </button>
                    <div class="h-px w-12 bg-gray-200 my-2"></div>
                    <button class="p-3 rounded-full bg-white shadow-sm hover:bg-blue-500 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Main Article Content -->
            <div class="lg:col-span-6">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-blue-100 rounded-[2.5rem] 
                        transform rotate-[-1deg] -z-10"></div>
                    <div class="bg-white/80 backdrop-blur-xl rounded-[1.5rem] sm:rounded-[2rem] shadow-lg hover:shadow-xl 
                        transition-all duration-500 overflow-hidden border border-gray-100">

                        <!-- Enhanced Image Section -->
                        <div class="relative h-[300px] sm:h-[400px] md:h-[500px] overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/20"></div>
                            <img src="https://picsum.photos/1200/800" alt="Cover"
                                class="w-full h-full object-cover transform hover:scale-105 transition-all duration-700">
                        </div>

                        <!-- Content Sections with Enhanced Typography -->
                        <div class="p-6 sm:p-8 md:p-12 space-y-6">
                            <article class="text-gray-700 leading-relaxed mb-6 text-lg">
                                <?= $data['post']['post']['content']; ?>
                            </article>

                        </div>

                        <!-- Interactive Elements - Mobile Optimized -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between 
                                  px-6 sm:px-8 md:px-12 py-4 sm:py-6 border-t border-gray-100/50
                                  bg-gradient-to-r from-gray-50/50 to-white/50 gap-4 sm:gap-0">
                            <div class="flex items-center justify-center sm:justify-start space-x-4">

                                <button class="flex items-center space-x-2 text-gray-600 hover:bg-blue-500 hover:text-white p-2 rounded-lg transition-all duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <span>Comments</span>
                                </button>
                            </div>
                            <div class="flex items-center justify-center sm:justify-end space-x-4">
                                <button class="flex items-center space-x-2 text-gray-600 hover:bg-blue-500 hover:text-white p-2 rounded-lg transition-all duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                    </svg>
                                    <span>Share</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Author Bio - Mobile Optimized -->
                <div class="my-8 sm:my-12 p-6 sm:p-8 bg-blue-50/50 rounded-xl sm:rounded-2xl border border-blue-100">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-4 sm:gap-6">
                        <img src="https://ui-avatars.com/api/?name=John+Doe&background=random"
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl border-2 border-white shadow-md">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">About the Author</h4>
                            <p class="text-gray-600 mt-2">
                                <?= $data['post']['post']['name']; ?> is a senior web developer with over 10 years of experience in building modern web applications.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Comments Section -->
                <section class="mb-8 sm:mb-12" x-data="{ isCommenting: false }">
                    <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-8">
                        Join the Discussion
                    </h3>

                    <!-- Comment Form -->
                    <div class="bg-white backdrop-blur-lg bg-opacity-90 rounded-xl sm:rounded-3xl shadow-md p-6 sm:p-8 mb-6 sm:mb-8 
                                transition-all duration-300 hover:shadow-xl border border-gray-100/50">
                        <?php Flasher::flash(); ?>
                        <button @click="isCommenting = !isCommenting"
                            x-show="!isCommenting"
                            class="w-full px-6 py-3 text-left text-gray-600 rounded-2xl border border-gray-200
                                transition-all duration-300 hover:bg-blue-500 hover:text-white">
                            Share your thoughts...
                        </button>
                        <form x-show="isCommenting" @click.away="isCommenting = false"
                            class="space-y-4 transform transition-all duration-300" method="POST" action="<?= BASEURL; ?>/posts/detail/<?= $data['post']['post']['id_post']; ?>">
                            <?php if (isset($_SESSION['isLoggedIn'])): ?>
                                <input type="text" required name="username"
                                    class="w-full p-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 
                                            focus:border-transparent transition-all duration-300 resize-none none"
                                    placeholder="Username" value="<?= $_SESSION['myProfile']['username']; ?>"
                                    </input>
                            <?php else: ?>
                                <input type="text" required name="username"
                                    class="w-full p-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 
                                            focus:border-transparent transition-all duration-300 resize-none none"
                                    placeholder="Username" value="">
                                </input>
                            <?php endif; ?>
                            <textarea rows="4" name="comment"
                                class="w-full p-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 
                                            focus:border-transparent transition-all duration-300 resize-none"
                                placeholder="Write your comment..."></textarea>
                            <button type="submit" class="px-8 py-3 bg-blue-500 text-white rounded-xl
                                        transition-all duration-300 hover:bg-blue-600 hover:shadow-lg hover:scale-105">
                                Post Comment
                            </button>
                        </form>
                    </div>

                    <!-- Comments List -->
                    <div class="space-y-4 sm:space-y-6">
                        <?php foreach ($data['post']['comments'] as $comment) : ?>

                            <div class="group bg-white backdrop-blur-lg bg-opacity-90 rounded-3xl p-6 shadow-md 
                                    transition-all duration-500 hover:shadow-xl hover:-translate-y-1 border border-gray-100/50">
                                <div class="flex items-center mb-4">
                                    <div class="relative transform group-hover:scale-105 transition-all duration-300">
                                        <img src="https://ui-avatars.com/api/?name=<?= $comment['username']; ?>&background=random"
                                            class="w-10 h-10 rounded-full border-2 border-white shadow-sm">
                                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
                                    </div>
                                    <div class="ml-4 transform group-hover:translate-x-2 transition-transform duration-300">
                                        <h4 class="text-sm font-semibold text-gray-900"><?= $comment['username']; ?></h4>
                                        <time class="text-xs text-gray-500"><?= $comment['created_at']; ?></time>
                                    </div>
                                </div>
                                <p class="text-gray-700 group-hover:text-gray-900 transition-colors duration-300">
                                    <?= $comment['comment']; ?>
                                </p>
                            </div>
                        <?php endforeach;; ?>
                    </div>
                </section>
            </div>
        </div>
    </article>

    <!-- Related Articles - Mobile Optimized -->
    <div class="max-w-4xl mx-auto mt-12 sm:mt-16 px-4">
        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6 sm:mb-8">More Articles</h3>
        <div class="grid grid-cols-2 sm:grid-cols-2 gap-6 sm:gap-8">
            <?php foreach ($data['post']['randomPosts'] as $post) : ?>
                <a href="<?= BASEURL; ?>/posts/detail/<?= $post['id_post']; ?>"" class=" group block">
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                        <img src="https://picsum.photos/400/200" alt="Related Article"
                            class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="p-6">
                            <h4 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-300">
                                <?= $post['title']; ?>
                            </h4>
                            <p class="text-gray-600 text-sm mt-2">Learn the basics of React and start building modern UIs...</p>
                        </div>
                    </div>
                </a>
                <!-- More related articles can be added here -->
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>