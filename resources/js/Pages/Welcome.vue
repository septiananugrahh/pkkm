<template>
    <PublicTemplate>
        <Head title="Landing Page" />

        <!-- Hero Section with Gradient Background -->
        <div
            class="relative overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-cyan-50"
        >
            <!-- Floating Elements -->
            <div
                class="absolute top-20 left-10 w-20 h-20 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full opacity-20 animate-pulse"
            ></div>
            <div
                class="absolute top-40 right-20 w-16 h-16 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full opacity-20 animate-bounce"
            ></div>
            <div
                class="absolute bottom-20 left-1/4 w-12 h-12 bg-gradient-to-r from-green-400 to-blue-400 rounded-full opacity-20 animate-ping"
            ></div>

            <div class="relative py-24 sm:py-32 lg:pb-40">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto max-w-4xl text-center">
                        <!-- Badge -->
                        <div
                            class="mb-8 inline-flex items-center rounded-full bg-indigo-100 px-4 py-2 text-sm font-medium text-indigo-800 ring-1 ring-indigo-200 animate-fade-in"
                        >
                            <v-icon class="mr-2" size="small" color="indigo"
                                >mdi-target</v-icon
                            >
                            Sistem Penilaian Terdepan
                        </div>

                        <!-- Main Heading with Gradient Text -->
                        <h1
                            class="text-5xl font-bold tracking-tight sm:text-7xl lg:text-8xl animate-slide-up"
                        >
                            <span class="block text-gray-900 mb-2"
                                >Aplikasi</span
                            >
                            <span
                                class="block bg-gradient-to-r from-indigo-600 via-purple-600 to-cyan-600 bg-clip-text text-transparent"
                            >
                                Penilaian Kinerja
                            </span>
                            <span class="block text-gray-900"
                                >Kepala Madrasah</span
                            >
                        </h1>

                        <!-- Subtitle with Animation -->
                        <p
                            class="mt-8 text-xl leading-8 text-gray-600 max-w-3xl mx-auto animate-fade-in-delay"
                        >
                            Mempermudah proses penilaian, pengumpulan data, dan
                            pelaporan kinerja kepala madrasah secara
                            <span class="font-semibold text-indigo-600"
                                >terstruktur</span
                            >,
                            <span class="font-semibold text-purple-600"
                                >transparan</span
                            >, dan
                            <span class="font-semibold text-cyan-600"
                                >efisien</span
                            >.
                        </p>

                        <!-- Feature Highlights -->
                        <div
                            class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto animate-fade-in-delay-2"
                        >
                            <div
                                class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2"
                            >
                                <div
                                    class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mb-4 mx-auto"
                                >
                                    <v-icon color="white"
                                        >mdi-check-circle</v-icon
                                    >
                                </div>
                                <h3
                                    class="text-lg font-semibold text-gray-900 mb-2"
                                >
                                    Terstruktur
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    Sistem penilaian yang sistematis dan
                                    terorganisir
                                </p>
                            </div>

                            <div
                                class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2"
                            >
                                <div
                                    class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-4 mx-auto"
                                >
                                    <v-icon color="white">mdi-eye</v-icon>
                                </div>
                                <h3
                                    class="text-lg font-semibold text-gray-900 mb-2"
                                >
                                    Transparan
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    Proses penilaian yang terbuka dan dapat
                                    diakses
                                </p>
                            </div>

                            <div
                                class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2"
                            >
                                <div
                                    class="w-12 h-12 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl flex items-center justify-center mb-4 mx-auto"
                                >
                                    <v-icon color="white"
                                        >mdi-lightning-bolt</v-icon
                                    >
                                </div>
                                <h3
                                    class="text-lg font-semibold text-gray-900 mb-2"
                                >
                                    Efisien
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    Menghemat waktu dan meningkatkan
                                    produktivitas
                                </p>
                            </div>
                        </div>

                        <!-- CTA Buttons -->
                        <div
                            class="mt-16 flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-delay-3"
                        >
                            <Link
                                :href="route('show.index')"
                                class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white transition-all duration-300 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 hover:scale-105 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                <v-icon class="mr-2" color="white"
                                    >mdi-eye-outline</v-icon
                                >
                                Lihat Penilaian Publik
                                <div
                                    class="absolute inset-0 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300 blur-xl"
                                ></div>
                            </Link>

                            <Link
                                :href="route('login')"
                                class="group inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-gray-700 transition-all duration-300 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 hover:scale-105 border border-gray-200 hover:border-indigo-300"
                            >
                                <v-icon class="mr-2" color="gray-700"
                                    >mdi-lock-outline</v-icon
                                >
                                Masuk Halaman Admin
                                <v-icon
                                    class="ml-2 transition-transform group-hover:translate-x-1"
                                    color="gray-700"
                                    >mdi-arrow-right</v-icon
                                >
                            </Link>
                        </div>

                        <!-- Stats Section -->
                        <div
                            class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-8 animate-fade-in-delay-4"
                        >
                            <div class="text-center">
                                <div
                                    class="flex items-center justify-center mb-2"
                                >
                                    <v-icon
                                        class="mr-2"
                                        color="indigo"
                                        size="large"
                                        >mdi-chart-line</v-icon
                                    >
                                    <span
                                        class="text-3xl font-bold text-indigo-600"
                                        >100%</span
                                    >
                                </div>
                                <div class="text-sm text-gray-600">
                                    Akurasi Data
                                </div>
                            </div>
                            <div class="text-center">
                                <div
                                    class="flex items-center justify-center mb-2"
                                >
                                    <v-icon
                                        class="mr-2"
                                        color="purple"
                                        size="large"
                                        >mdi-clock-outline</v-icon
                                    >
                                    <span
                                        class="text-3xl font-bold text-purple-600"
                                        >24/7</span
                                    >
                                </div>
                                <div class="text-sm text-gray-600">
                                    Akses Online
                                </div>
                            </div>
                            <div class="text-center">
                                <div
                                    class="flex items-center justify-center mb-2"
                                >
                                    <v-icon
                                        class="mr-2"
                                        color="cyan"
                                        size="large"
                                        >mdi-speedometer</v-icon
                                    >
                                    <span
                                        class="text-3xl font-bold text-cyan-600"
                                        >Real-time</span
                                    >
                                </div>
                                <div class="text-sm text-gray-600">
                                    Pelaporan
                                </div>
                            </div>
                            <div class="text-center">
                                <div
                                    class="flex items-center justify-center mb-2"
                                >
                                    <v-icon
                                        class="mr-2"
                                        color="green"
                                        size="large"
                                        >mdi-shield-check</v-icon
                                    >
                                    <span
                                        class="text-3xl font-bold text-green-600"
                                        >Secure</span
                                    >
                                </div>
                                <div class="text-sm text-gray-600">
                                    Data Aman
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-24 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center mb-16">
                    <h2
                        class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"
                    >
                        Fitur Unggulan
                    </h2>
                    <p class="mt-4 text-lg leading-8 text-gray-600">
                        Solusi komprehensif untuk manajemen penilaian kinerja
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div
                        class="group relative rounded-2xl bg-gradient-to-br from-indigo-50 to-indigo-100 p-8 transition-all duration-300 hover:shadow-lg hover:scale-105"
                    >
                        <div
                            class="flex items-center justify-center w-16 h-16 rounded-xl bg-indigo-600 text-white mb-6 mx-auto"
                        >
                            <v-icon size="x-large" color="white"
                                >mdi-clipboard-list</v-icon
                            >
                        </div>
                        <h3
                            class="text-xl font-semibold text-gray-900 mb-4 text-center"
                        >
                            Penilaian Terstruktur
                        </h3>
                        <p class="text-gray-600 text-center">
                            Sistem penilaian yang sistematis dan terorganisir
                            dengan kriteria yang jelas dan objektif.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div
                        class="group relative rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100 p-8 transition-all duration-300 hover:shadow-lg hover:scale-105"
                    >
                        <div
                            class="flex items-center justify-center w-16 h-16 rounded-xl bg-purple-600 text-white mb-6 mx-auto"
                        >
                            <v-icon size="x-large" color="white"
                                >mdi-account-eye</v-icon
                            >
                        </div>
                        <h3
                            class="text-xl font-semibold text-gray-900 mb-4 text-center"
                        >
                            Transparansi Penuh
                        </h3>
                        <p class="text-gray-600 text-center">
                            Akses publik untuk melihat hasil penilaian dengan
                            transparansi dan akuntabilitas yang tinggi.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div
                        class="group relative rounded-2xl bg-gradient-to-br from-cyan-50 to-cyan-100 p-8 transition-all duration-300 hover:shadow-lg hover:scale-105"
                    >
                        <div
                            class="flex items-center justify-center w-16 h-16 rounded-xl bg-cyan-600 text-white mb-6 mx-auto"
                        >
                            <v-icon size="x-large" color="white"
                                >mdi-rocket-launch</v-icon
                            >
                        </div>
                        <h3
                            class="text-xl font-semibold text-gray-900 mb-4 text-center"
                        >
                            Efisiensi Tinggi
                        </h3>
                        <p class="text-gray-600 text-center">
                            Proses yang cepat dan efisien dengan otomatisasi
                            untuk menghemat waktu dan tenaga.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Features Section -->
        <div class="py-24 bg-gray-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center mb-16">
                    <h2
                        class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"
                    >
                        Mengapa Memilih PKKM?
                    </h2>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8"
                >
                    <div class="text-center">
                        <div
                            class="flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4 mx-auto"
                        >
                            <v-icon size="large" color="blue"
                                >mdi-database</v-icon
                            >
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Data Terpusat
                        </h3>
                        <p class="text-gray-600 text-sm">
                            Semua data tersimpan dalam satu sistem yang
                            terintegrasi
                        </p>
                    </div>

                    <div class="text-center">
                        <div
                            class="flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4 mx-auto"
                        >
                            <v-icon size="large" color="green"
                                >mdi-file-chart</v-icon
                            >
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Laporan Otomatis
                        </h3>
                        <p class="text-gray-600 text-sm">
                            Generate laporan komprehensif secara otomatis
                        </p>
                    </div>

                    <div class="text-center">
                        <div
                            class="flex items-center justify-center w-16 h-16 rounded-full bg-orange-100 mb-4 mx-auto"
                        >
                            <v-icon size="large" color="orange"
                                >mdi-account-multiple</v-icon
                            >
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Multi User
                        </h3>
                        <p class="text-gray-600 text-sm">
                            Mendukung berbagai tingkat akses pengguna
                        </p>
                    </div>

                    <div class="text-center">
                        <div
                            class="flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4 mx-auto"
                        >
                            <v-icon size="large" color="red"
                                >mdi-cellphone</v-icon
                            >
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Mobile Friendly
                        </h3>
                        <p class="text-gray-600 text-sm">
                            Dapat diakses dari berbagai perangkat mobile
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Final CTA Section -->
        <div class="py-24 bg-gradient-to-r from-indigo-600 to-purple-600">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center">
                    <h2
                        class="text-3xl font-bold tracking-tight text-white sm:text-4xl mb-4"
                    >
                        Siap Memulai Penilaian Digital?
                    </h2>
                    <p class="text-xl text-indigo-200 mb-8 max-w-2xl mx-auto">
                        Bergabunglah dengan revolusi digital dalam sistem
                        penilaian kinerja kepala madrasah
                    </p>

                    <div
                        class="flex flex-col sm:flex-row items-center justify-center gap-4"
                    >
                        <Link
                            :href="route('show.index')"
                            class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-indigo-600 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105"
                        >
                            <v-icon class="mr-2" color="indigo"
                                >mdi-play-circle</v-icon
                            >
                            Mulai Sekarang
                        </Link>

                        <Link
                            :href="route('login')"
                            class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white border-2 border-white rounded-2xl hover:bg-white hover:text-indigo-600 transition-all duration-300 hover:scale-105"
                        >
                            <v-icon class="mr-2">mdi-login</v-icon>
                            Login Admin
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </PublicTemplate>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import PublicTemplate from "@/Components/PublicTemplate.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<style scoped>
/* Custom Animations */
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out;
}

.animate-fade-in-delay {
    animation: fade-in 0.8s ease-out 0.2s both;
}

.animate-fade-in-delay-2 {
    animation: fade-in 0.8s ease-out 0.4s both;
}

.animate-fade-in-delay-3 {
    animation: fade-in 0.8s ease-out 0.6s both;
}

.animate-fade-in-delay-4 {
    animation: fade-in 0.8s ease-out 0.8s both;
}

.animate-slide-up {
    animation: slide-up 1s ease-out;
}

/* Glassmorphism Effect */
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
}

/* Gradient Text */
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

/* Custom Shadows */
.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.hover\:shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
