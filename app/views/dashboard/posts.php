<div class="bg-gray-100 font-sans">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen">
        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'block' : 'hidden'" class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden" @click="sidebarOpen = false"></div>
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-blue-800 text-white transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            <div class="p-4">
                <h1 class="text-2xl font-bold">Admin Blog</h1>
            </div>
            <nav class="mt-6">
                <a href="#" class="block py-2 px-4 hover:bg-blue-700">Dashboard</a>
                <a href="#" class="block py-2 px-4 hover:bg-blue-700">Posts</a>
                <a href="#" class="block py-2 px-4 hover:bg-blue-700">Categories</a>
                <a href="#" class="block py-2 px-4 hover:bg-blue-700">Comments</a>
                <a href="#" class="block py-2 px-4 hover:bg-blue-700">Settings</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow p-4">
                <div class="flex justify-between items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-blue-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                    <h2 class="text-xl font-semibold text-blue-800">Dashboard</h2>
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-4">Admin</span>
                        <img src="https://via.placeholder.com/40" alt="Profile" class="w-10 h-10 rounded-full">
                    </div>
                </div>
            </header>

            <!-- Main Section -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Card 1: Total Posts -->
                    <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <h3 class="text-lg font-semibold text-blue-800">Total Posts</h3>
                        <p class="text-3xl font-bold mt-2"><?= count($data['posts']); ?></p>
                    </div>

                    <!-- Card 2: Total Categories -->
                    <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <h3 class="text-lg font-semibold text-blue-800">Total Categories</h3>
                        <p class="text-3xl font-bold mt-2">15</p>
                    </div>

                    <!-- Card 3: Total Comments -->
                    <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <h3 class="text-lg font-semibold text-blue-800">Total Comments</h3>
                        <p class="text-3xl font-bold mt-2">456</p>
                    </div>
                </div>

                <!-- Recent Posts Table -->
                <div class="mt-8">
                    <h3 class="text-xl font-semibold text-blue-800 mb-4">Recent Posts</h3>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase">Author</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">

                                <?php foreach ($data['posts'] as $post) : ?>
                                    <tr>
                                        <td class="px-6 py-4"><?= $post['title']; ?></td>
                                        <td class="px-6 py-4"><?= $post['username']; ?></td>
                                        <td class="px-6 py-4"><?= $post['created_at']; ?></td>
                                        <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-800 rounded">Published</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>