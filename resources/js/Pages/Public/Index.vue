<template>
    <PublicTemplate>
        <Head title="Penilaian" />

        <!-- Hero Section with Gradient Background -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-cyan-50 pt-20"> 
            <!-- Floating Elements -->
            <div class="absolute top-20 left-10 w-16 h-16 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full opacity-20 animate-pulse"></div>
            <div class="absolute top-40 right-20 w-12 h-12 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full opacity-20 animate-bounce"></div>

            <div class="relative py-16 sm:py-24">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto max-w-3xl text-center mb-16">
                        <!-- Badge -->
                        <div class="mb-6 inline-flex items-center rounded-full bg-indigo-100 px-4 py-2 text-sm font-medium text-indigo-800 ring-1 ring-indigo-200 animate-fade-in">
                            <v-icon class="mr-2" size="small" color="indigo">mdi-clipboard-check</v-icon>
                            Data Penilaian Kinerja
                        </div>

                        <!-- Main Heading -->
                        <h1 class="text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl animate-slide-up">
                            <span class="block text-gray-900 mb-2">Struktur</span>
                            <span class="block bg-gradient-to-r from-indigo-600 via-purple-600 to-cyan-600 bg-clip-text text-transparent">
                                Penilaian Kinerja
                            </span>
                            <span class="block text-gray-900">Kepala Madrasah</span>
                        </h1>

                        <!-- Subtitle -->
                        <p class="mt-6 text-lg leading-8 text-gray-600 max-w-2xl mx-auto animate-fade-in-delay">
                            Akses data penilaian kinerja kepala madrasah secara transparan dan terstruktur berdasarkan tahun penilaian.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Section -->
        <div class="py-16 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <!-- Year Selection Card -->
                <div class="mb-12 animate-fade-in-delay-2">
                    <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-8 shadow-lg border border-gray-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mr-4">
                                <v-icon color="white" size="large">mdi-calendar</v-icon>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Filter Tahun Penilaian</h3>
                                <p class="text-gray-600">Pilih tahun untuk melihat data penilaian yang sesuai</p>
                            </div>
                        </div>

                        <div class="max-w-md">
                            <v-select
                                v-model="selectedYear"
                                :items="years"
                                :item-title="isYearObjects ? 'year' : null"
                                :item-value="isYearObjects ? 'id' : null"
                                label="Pilih Tahun Penilaian"
                                variant="outlined"
                                color="indigo"
                                class="modern-select"
                                @update:modelValue="reload"
                            >
                                <template v-slot:prepend-inner>
                                    <v-icon color="indigo">mdi-calendar-range</v-icon>
                                </template>
                            </v-select>
                        </div>
                    </div>
                </div>

                <!-- Nodes Section -->
                <div class="animate-fade-in-delay-3">
                    <!-- Section Header -->
                    <div class="mb-8 text-center">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">
                            Struktur Data Penilaian
                        </h2>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                            Berikut adalah struktur hierarkis data penilaian kinerja kepala madrasah yang dapat Anda akses.
                        </p>
                    </div>

                    <!-- Loading State -->
                    <div v-if="loading" class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 mb-4">
                            <v-progress-circular
                                indeterminate
                                color="indigo"
                                size="32"
                            ></v-progress-circular>
                        </div>
                        <p class="text-gray-600">Memuat data penilaian...</p>
                    </div>

                    <!-- Nodes Content -->
                    <div v-else-if="nodes && nodes.length > 0" class="space-y-6">
                        <!-- Stats Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-2xl p-6 border border-indigo-200">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                        <v-icon color="white">mdi-file-tree</v-icon>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-indigo-900">{{ nodes.length }}</p>
                                        <p class="text-indigo-700 text-sm">Total Node</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center mr-4">
                                        <v-icon color="white">mdi-calendar-check</v-icon>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-purple-900">{{ getCurrentYear() }}</p>
                                        <p class="text-purple-700 text-sm">Tahun Aktif</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-cyan-50 to-cyan-100 rounded-2xl p-6 border border-cyan-200">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-cyan-600 rounded-xl flex items-center justify-center mr-4">
                                        <v-icon color="white">mdi-eye</v-icon>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-cyan-900">Public</p>
                                        <p class="text-cyan-700 text-sm">Akses Terbuka</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tree Nodes -->
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center">
                                    <v-icon class="mr-3" color="gray-700">mdi-file-tree</v-icon>
                                    <h3 class="text-lg font-semibold text-gray-900">Hierarki Data Penilaian</h3>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <PublicNodeItem
                                    v-for="node in nodes"
                                    :key="node.id"
                                    :node="node"
                                    class="mb-4 last:mb-0"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-6">
                            <v-icon color="gray-400" size="40">mdi-file-search</v-icon>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Data</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            Belum ada data penilaian untuk tahun yang dipilih. Silakan pilih tahun lain atau hubungi administrator.
                        </p>
                        <button
                            @click="reload"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-105"
                        >
                            <v-icon class="mr-2" color="white">mdi-refresh</v-icon>
                            Muat Ulang
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action Section -->
        <div class="py-16 bg-gradient-to-r from-indigo-600 to-purple-600">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-white mb-4">
                        Butuh Akses Lebih Lengkap?
                    </h2>
                    <p class="text-xl text-indigo-200 mb-8 max-w-2xl mx-auto">
                        Login sebagai administrator untuk mengakses fitur manajemen dan laporan lengkap
                    </p>
                    
                    <Link
                        :href="route('login')"
                        class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-indigo-600 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105"
                    >
                        <v-icon class="mr-2" color="indigo">mdi-login</v-icon>
                        Login Administrator
                    </Link>
                </div>
            </div>
        </div>
    </PublicTemplate>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { router, Head, Link } from "@inertiajs/vue3";
import PublicNodeItem from "@/Pages/Public/NodeItem.vue";
import PublicTemplate from "@/Components/PublicTemplate.vue";

const props = defineProps({
    nodes: Array,
    years: Array,
    year: [Number, String, null],
});

const loading = ref(false);

const selectedYear = ref(
    props.year ??
        (Array.isArray(props.years) && props.years[0]
            ? typeof props.years[0] === "object"
                ? props.years[0].id
                : props.years[0]
            : null)
);

const isYearObjects = computed(() => {
    return Array.isArray(props.years) && typeof props.years[0] === "object";
});

const getCurrentYear = () => {
    if (!selectedYear.value) return '-';
    
    if (isYearObjects.value) {
        const yearObj = props.years.find(y => y.id === selectedYear.value);
        return yearObj ? yearObj.year : selectedYear.value;
    }
    
    return selectedYear.value;
};

// Reload saat tahun berubah
watch(selectedYear, (val) => {
    if (val !== null) {
        reload();
    }
});

const reload = () => {
    loading.value = true;
    router.get(
        route("show.nodes"),
        { year: selectedYear.value },
        { 
            preserveState: true,
            onFinish: () => {
                loading.value = false;
            }
        }
    );
};
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

/* Modern Select Styling */
.modern-select :deep(.v-field) {
    border-radius: 12px;
}

.modern-select :deep(.v-field--focused) {
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
}

/* Custom Shadows */
.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.hover\:shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>