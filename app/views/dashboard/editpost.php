<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Post | UNPAS Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Trix Editor -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    <!-- Tagify -->
    <script src="https://unpkg.com/@yaireo/tagify"></script>
    <link href="https://unpkg.com/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Trix Custom Styling */
        trix-editor {
            @apply border border-gray-200 rounded-lg min-h-[400px] px-4 py-2;
        }

        trix-toolbar {
            @apply border border-gray-200 rounded-t-lg border-b-0 px-2 py-2 bg-gray-50;
        }

        trix-toolbar .trix-button-group {
            @apply border border-gray-200 rounded mr-2;
        }

        trix-toolbar .trix-button {
            @apply border-none bg-transparent hover:bg-gray-100;
        }

        /* Tagify Custom Styling */
        .tagify {
            @apply border border-gray-200 rounded-lg px-3 py-2;
        }

        .tagify__tag {
            @apply bg-blue-50 text-blue-700;
        }

        .tagify__tag__removeBtn {
            @apply text-blue-700;
        }
    </style>
</head>

<body class="bg-gray-50" x-data="{ title: '', isPreview: false }">
    <div class="min-h-screen">
        <!-- Top Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-xl font-semibold">UNPAS Blog</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button @click="isPreview = !isPreview"
                            class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                            {{ isPreview ? 'Edit' : 'Preview' }}
                        </button>
                        <button type="submit" form="postForm"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            Publish
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 py-8">
            <form id="postForm" class="space-y-6" x-show="!isPreview">
                <!-- Title Input -->
                <div>
                    <input type="text"
                        x-model="title"
                        placeholder="Post Title"
                        class="w-full text-3xl font-semibold border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 bg-transparent pb-2"
                        required>
                </div>

                <!-- Featured Image with Preview -->
                <div class="bg-white rounded-lg p-4 border border-gray-200" x-data="imagePreview()">
                    <label class="block space-y-4">
                        <span class="text-sm font-medium text-gray-700">Featured Image</span>

                        <!-- Image Preview -->
                        <template x-if="imageUrl">
                            <div class="relative group">
                                <img :src="imageUrl"
                                    class="w-full h-[300px] object-cover rounded-lg shadow-sm"
                                    alt="Preview">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                    <button type="button"
                                        @click="removeImage"
                                        class="text-white bg-red-500/80 hover:bg-red-500 px-4 py-2 rounded-md text-sm transition-colors">
                                        Remove Image
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Upload Area -->
                        <div x-show="!imageUrl"
                            class="border-2 border-dashed border-gray-200 rounded-lg p-8 text-center hover:border-blue-500 transition-colors">
                            <input type="file"
                                @change="fileChosen"
                                accept="image/*"
                                class="hidden"
                                id="image-upload">
                            <label for="image-upload" class="cursor-pointer">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Click to upload or drag and drop</p>
                                <p class="mt-1 text-xs text-gray-400">PNG, JPG, GIF up to 10MB</p>
                            </label>
                        </div>
                    </label>
                </div>

                <!-- Trix Editor -->
                <div class="bg-white rounded-lg">
                    <input id="content" type="hidden" name="content">
                    <trix-editor input="content" class="prose max-w-none"></trix-editor>
                </div>

                <!-- Tags -->
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                    <input id="tags" type="text" name="tags" class="w-full" placeholder="Add tags...">
                </div>
            </form>

            <!-- Preview Mode -->
            <div x-show="isPreview" class="bg-white rounded-lg p-8 border border-gray-200">
                <h1 x-text="title" class="text-3xl font-bold mb-6"></h1>
                <div x-html="document.querySelector('trix-editor').innerHTML" class="prose max-w-none"></div>
            </div>
        </main>
    </div>

    <script>
        // Add this before existing script
        function imagePreview() {
            return {
                imageUrl: '',
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
            }
        }

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

        // Additional Trix configuration if needed
        addEventListener("trix-file-accept", function(event) {
            // Prevent file attachments
            event.preventDefault();
        });
    </script>
</body>

</html>