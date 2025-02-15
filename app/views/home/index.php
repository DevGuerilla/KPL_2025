<?php include_once __DIR__ . '/../templates/navbar.php'; ?>

<!-- Modern Hero Section with Blue-500 Base -->
<div class="relative min-h-screen bg-blue-500 overflow-hidden -mt-16 -z-10">
    <!-- Animated Gradient Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-blue-400 to-blue-400"></div>
    
    <!-- Modern Shape Decorations -->
    <div class="absolute top-0 left-0 w-full h-full">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-blue-300/20 -skew-x-12"></div>
        <div class="absolute bottom-0 left-0 w-1/4 h-full bg-blue-500/20 skew-x-12"></div>
    </div>

    <!-- Content Section -->
    <main class="relative container mx-auto px-4">
        <div class="flex min-h-screen">
            <div class="flex flex-col md:flex-row items-center justify-between w-full gap-12 py-12">
                <!-- Left Content -->
                <div class="w-full md:w-1/2 space-y-8 text-center md:text-left z-10">
                    <div class="space-y-6">
                        <span class="inline-block px-4 py-2 bg-blue-50 rounded-full text-md font-semibold backdrop-blur-sm text-blue-500 ">
                            UNPAS TIME
                        </span>
                        <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">
                            Jelajahi Cerita Kampus
                            <span class="block text-white">Yang Menginspirasi</span>
                        </h1>
                        <p class="text-xl text-blue-100 leading-relaxed">
                            Tuliskan kisahmu, bagikan pengalamanmu, dan temukan informasi menarik 
                            seputar kehidupan kampus dalam satu platform yang memadukan berita dan kreativitas.
                        </p>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <a href="<?= BASEURL; ?>/berita" 
                           class="px-8 py-4 bg-blue-600 text-white rounded-xl font-semibold 
                                  hover:bg-blue-700 transition duration-300 transform hover:-translate-y-1
                                  shadow-lg shadow-blue-600/20">
                            Bagikan Ceritamu
                        </a>
                    </div>

                </div>

                <!-- Right Content - Image with Modern Effects -->
                <div class="w-full md:w-1/2 hidden md:block z-10">
                    <div class="relative bg-blue-400/20 rounded-2xl backdrop-blur-sm p-6">
                        <!-- Decorative Elements -->
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-blue-300/30 rounded-full blur-xl"></div>
                        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-blue-400/30 rounded-full blur-xl"></div>
                        
                        <!-- Main Image -->
                        <div class="relative rounded-xl overflow-hidden bg-gradient-to-br from-blue-400/20 to-blue-300/20 p-4">
                            <img src="<?= BASEURL; ?>/img/banner.svg" 
                                 alt="Ilustrasi Kampus"
                                 class="w-full h-auto object-contain transform hover:scale-105 transition-all duration-500
                                        drop-shadow-[0_20px_50px_rgba(255,255,255,0.15)]">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>