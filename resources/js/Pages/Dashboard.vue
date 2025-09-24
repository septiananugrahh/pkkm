<template>
    <AuthenticatedLayout>
        <Head title="Dashboard" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <v-row class="mb-6">
                            <v-col cols="12" sm="4">
                                <v-select
                                    v-model="selectedYear"
                                    :items="years"
                                    item-title="year"
                                    item-value="id"
                                    label="Pilih Tahun"
                                    @update:modelValue="reload"
                                />
                            </v-col>
                        </v-row>

                        <h3 class="text-2xl font-bold mb-4">
                            Selamat Datang, {{ $page.props.auth.user.name }}! 👋
                        </h3>
                        <p class="text-gray-600 mb-8">
                            Ini adalah ringkasan singkat dari aktivitas Anda.
                            Kelola data PKKM dengan mudah.
                        </p>

                        <v-row>
                            <v-col cols="12" md="3" class="mb-4">
                                <v-card
                                    class="d-flex flex-column align-center text-center pa-4"
                                    color="indigo"
                                    dark
                                >
                                    <v-icon size="48"
                                        >mdi-folder-multiple-outline</v-icon
                                    >
                                    <div class="text-h4 font-weight-bold mt-2">
                                        {{ totalNodes }}
                                    </div>
                                    <div class="text-subtitle-1">
                                        Total Nodes
                                    </div>
                                </v-card>
                            </v-col>

                            <v-col cols="12" md="3" class="mb-4">
                                <v-card
                                    class="d-flex flex-column align-center text-center pa-4"
                                    color="teal"
                                    dark
                                >
                                    <v-icon size="48"
                                        >mdi-file-multiple-outline</v-icon
                                    >
                                    <div class="text-h4 font-weight-bold mt-2">
                                        {{ totalFiles }}
                                    </div>
                                    <div class="text-subtitle-1">
                                        Total Files
                                    </div>
                                </v-card>
                            </v-col>

                            <v-col cols="12" md="3" class="mb-4">
                                <v-card
                                    class="d-flex flex-column align-center text-center pa-4"
                                    color="purple"
                                    dark
                                >
                                    <v-icon size="48"
                                        >mdi-calendar-check-outline</v-icon
                                    >
                                    <div class="text-h4 font-weight-bold mt-2">
                                        {{ totalYears }}
                                    </div>
                                    <div class="text-subtitle-1">
                                        Total Tahun
                                    </div>
                                </v-card>
                            </v-col>

                            <v-col cols="12" md="3" class="mb-4">
                                <v-card
                                    class="d-flex flex-column align-center text-center pa-4"
                                    color="green"
                                    dark
                                >
                                    <v-icon size="48"
                                        >mdi-check-circle-outline</v-icon
                                    >
                                    <div class="text-h4 font-weight-bold mt-2">
                                        {{ completionPercentage }}%
                                    </div>
                                    <div class="text-subtitle-1">
                                        Persentase Selesai
                                    </div>
                                </v-card>
                            </v-col>
                        </v-row>

                        <div class="mt-8 d-flex justify-center flex-wrap">
                            <v-btn
                                color="primary"
                                class="mr-4 mb-4"
                                large
                                component="span"
                            >
                                <Link
                                    :href="route('nodes.index')"
                                    class="d-flex align-center text-white"
                                >
                                    <v-icon left>mdi-file-tree</v-icon>
                                    Kelola Data
                                </Link>
                            </v-btn>

                            <v-btn
                                color="secondary"
                                class="mr-4 mb-4"
                                large
                                component="span"
                            >
                                <Link
                                    :href="route('show.index')"
                                    target="_blank"
                                    class="d-flex align-center text-white"
                                >
                                    <v-icon left>mdi-eye-outline</v-icon>
                                    Lihat Tampilan Publik
                                </Link>
                            </v-btn>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    totalNodes: Number,
    totalFiles: Number,
    totalYears: Number,
    years: Array,
    year: [Number, String],
    completionPercentage: Number,
});

const selectedYear = ref(props.year);

const reload = () => {
    router.get(
        route("dashboard"),
        { year: selectedYear.value },
        { preserveState: true }
    );
};
</script>
