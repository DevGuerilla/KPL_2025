<?php include_once __DIR__ . '/../templates/navbar.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8 pt-36">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent font-poppins mb-4">
                Temukan Cerita
            </h1>
            <p class="text-lg text-gray-600 font-poppins max-w-2xl mx-auto">
                Jelajahi wawasan dan pengetahuan terbaru dari komunitas kami
            </p>
        </div>

        <!-- Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <?php foreach ($data['posts'] as $post) : ?>
                <!-- Post Card Template -->
                <article class="group bg-white backdrop-blur-lg bg-opacity-90 rounded-3xl shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 overflow-hidden border border-gray-100/50">
                    <div class="relative overflow-hidden rounded-t-3xl">
                        <img src="<?= BASEURL . '/img/posts/' . $post['post']['image']; ?>" alt="Gambar Postingan"
                            class="w-full h-56 object-cover transform group-hover:scale-110 transition-transform duration-700 ease-in-out">
                        <div class="absolute top-4 right-4 bg-black/40 backdrop-blur-md rounded-full px-4 py-1.5 
                                transform group-hover:translate-x-1 transition-all duration-500">
                            <span class="text-white text-xs font-medium">5 menit baca</span>
                        </div>
                    </div>

                    <div class="p-7">
                        <div class="flex items-center mb-5 space-x-4">
                            <div class="relative transform group-hover:scale-105 transition-all duration-300">
                                <img src="https://ui-avatars.com/api/?name=John+Doe&background=random" alt="Penulis"
                                    class="w-12 h-12 rounded-full border-2 border-white shadow-md">
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white 
                                       group-hover:scale-110 transition-transform duration-300"></div>
                            </div>
                            <div class="transform group-hover:translate-x-2 transition-transform duration-300">
                                <p class="text-sm font-semibold text-gray-900"><?= $post['post']['name']; ?></p>
                                <p class="text-xs text-gray-500"><?= date('d M Y', strtotime($post['post']['created_at'])); ?></p>
                            </div>
                        </div>

                        <h2 class="font-poppins font-bold text-xl mb-3 text-gray-900 group-hover:text-blue-600 transition-all duration-300 ease-out">
                            <a href="#" class="line-clamp-2 hover:text-blue-700"><?= $post['post']['title']; ?></a>
                        </h2>

                        <p class="text-gray-600 text-sm mb-6 line-clamp-3 group-hover:text-gray-700 transition-colors duration-300">
                            <?= Helper::excerpt($post['post']['content'], 150); ?>
                        </p>

                        <div class="flex items-center justify-between">
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($post['tags'] as $tag) : ?>
                                    <span class="px-4 py-1.5 text-xs font-medium bg-blue-50 text-blue-600 rounded-full 
                                       transform hover:scale-105 hover:bg-blue-100 transition-all duration-300"><?= $tag['tag_name']; ?></span>
                                <?php endforeach; ?>
                            </div>
                            <a href="<?= BASEURL; ?>/posts/detail/<?= $post['post']['id_post']; ?>" class="group inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-semibold 
                                     px-4 py-2 rounded-full hover:bg-blue-50 transition-all duration-300">
                                Baca selengkapnya
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-2 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>

                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Modern Pagination -->
        <!-- Pagination dihapus -->

    </div>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>