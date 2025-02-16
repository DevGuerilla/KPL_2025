<?php
ob_start();
?>

<div class="space-y-4 mt-20">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Edit Artikel</h1>
            <p class="mt-1 text-sm text-gray-600">Edit dan perbarui artikel Anda</p>
        </div>
    </div>

    <div x-data="{ 
        title: '<?= htmlspecialchars($post['title'] ?? '', ENT_QUOTES) ?>', 
        isPreview: false,
        imageUrl: '<?= htmlspecialchars($post['image'] ?? '', ENT_QUOTES) ?>',
        fileChosen(event) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.type.match(/image.*/)) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        removeImage() {
            this.imageUrl = '';
            document.getElementById('image-upload').value = '';
        }
    }">
        <!-- Main Form -->
        <form id="postForm" action="<?= BASEURL; ?>/dashboard/updatepost/<?= $post['id'] ?? '' ?>" method="POST" enctype="multipart/form-data" class="space-y-4" x-show="!isPreview">
            <!-- First Row: Title and Tags (50-50 split) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Title Input (Left 50%) -->
                <div class="bg-white rounded-lg border border-gray-200 p-4 transition-all duration-300 hover:shadow-sm">
                    <label class="block text-sm font-medium text-gray-700 mb-1 pl-4">Judul Artikel</label>
                    <input type="text"
                        name="title"
                        x-model="title"
                        placeholder="Masukkan judul artikel..."
                        class="w-full text-lg outline-none border-b focus:border-blue-500 focus:ring-0 bg-transparent pb-1 px-4 py-1"
                        required>
                </div>

                <!-- Tags Input (Right 50%) -->
                <div class="bg-white rounded-lg border border-gray-200 p-4 transition-all duration-300 hover:shadow-sm">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                    <input id="tags" type="text" name="tags" 
                        value="<?= htmlspecialchars($post['tags'] ?? '', ENT_QUOTES) ?>"
                        class="w-full text-lg border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 bg-transparent pb-1 rounded-lg" 
                        placeholder="Tambahkan tags...">
                </div>
            </div>

            <!-- Second Row: Image and Content (50-50 split) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Featured Image (Left 50%) -->
                <div class="bg-white rounded-lg border border-gray-200 p-4 transition-all duration-300 hover:shadow-sm">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama</label>

                    <!-- Image Preview -->
                    <template x-if="imageUrl">
                        <div class="relative group mb-2">
                            <img :src="imageUrl.startsWith('data:') ? imageUrl : '<?= BASEURL ?>/img/' + imageUrl"
                                class="w-full h-[250px] object-cover rounded-lg"
                                alt="Preview">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-all duration-300 rounded-lg flex items-center justify-center">
                                <button type="button"
                                    @click="removeImage"
                                    class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transform hover:scale-105 transition-all duration-300">
                                    Hapus Gambar
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Upload Area -->
                    <div x-show="!imageUrl"
                        class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center hover:border-blue-500 transition-all duration-300 h-[250px] flex flex-col items-center justify-center">
                        <input type="file"
                            name="image"
                            @change="fileChosen"
                            accept="image/*"
                            class="hidden"
                            id="image-upload">
                        <label for="image-upload" class="cursor-pointer">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Klik untuk upload atau drag and drop</p>
                            <p class="mt-1 text-xs text-gray-400">PNG, JPG, GIF (Maks. 10MB)</p>
                        </label>
                    </div>
                    <input type="hidden" name="old_image" value="<?= $post['image'] ?? '' ?>">
                </div>

                <!-- Content Editor (Right 50%) -->
                <div class="bg-white rounded-lg border border-gray-200 p-4 transition-all duration-300 hover:shadow-sm">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konten Artikel</label>
                    <input id="content" type="hidden" name="content" value="<?= htmlspecialchars($post['content'] ?? '', ENT_QUOTES) ?>">
                    <trix-editor input="content" class="prose max-w-none h-[250px] bg-white rounded-lg"></trix-editor>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-4">
                <a href="<?= BASEURL ?>/dashboard/posts" 
                    class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 text-sm font-medium rounded-lg transition-all duration-300">
                    Batal
                </a>
                <button type="submit"
                    class="group relative px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg transition-all duration-300 hover:shadow-sm overflow-hidden">
                    <span class="relative z-10 transition-transform duration-300 group-hover:translate-x-1">
                        Perbarui
                    </span>
                    <div class="absolute inset-0 bg-blue-700 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                </button>
            </div>
        </form>

        <!-- Preview Mode -->
        <div x-show="isPreview" class="bg-white rounded-lg border border-gray-200 p-6 transition-all duration-300 hover:shadow-sm">
            <h1 x-text="title" class="text-2xl font-bold mb-4"></h1>
            <div x-html="document.querySelector('trix-editor').innerHTML" class="prose max-w-none"></div>
        </div>
    </div>
</div>

<style>
    /* Custom Trix Editor Styling */
    trix-toolbar {
        @apply border border-gray-200 rounded-t-lg border-b-0 px-2 py-1 bg-gray-50;
    }

    trix-editor {
        @apply border border-gray-200 rounded-b-lg px-2 py-1 focus:ring-1 focus:ring-blue-500 focus:border-transparent;
        height: calc(250px - 3rem) !important;
    }

    /* Custom Tagify Styling */
    .tagify {
        @apply border-0 border-b border-gray-200 rounded-none px-0 py-1;
    }

    .tagify__tag {
        @apply bg-blue-50;
    }

    .tagify__tag__removeBtn {
        @apply text-blue-700;
    }

    .tagify__tag>div {
        @apply text-blue-700;
    }

    .tagify__input::before {
        @apply text-gray-400;
    }

    /* Input Placeholder Styling */
    input::placeholder {
        @apply text-gray-400;
    }
</style>

<script>
    // Initialize Tagify
    const tagsInput = document.querySelector('#tags');
    const tagify = new Tagify(tagsInput, {
        maxTags: 5,
        dropdown: {
            maxItems: 5,
            classname: "tags-dropdown",
            enabled: 0,
            closeOnSelect: false
        }
    });

    // Prevent file attachments in Trix
    addEventListener("trix-file-accept", function(event) {
        event.preventDefault();
    });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../templates/dashboard.php';
?>