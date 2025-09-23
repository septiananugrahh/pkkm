<template>
    <AuthenticatedLayout>
        <Head>Dashboard</Head>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-2xl font-bold mb-4">
                            Selamat Datang, {{ $page.props.auth.user.name }}! 👋
                        </h3>
                        <p class="text-gray-600 mb-8">
                            Ini adalah ringkasan singkat dari aktivitas Anda.
                            Kelola data PKKM dengan mudah.
                        </p>

                        <v-row>
                            <v-col cols="12" md="4">
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

                            <v-col cols="12" md="4">
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

                            <v-col cols="12" md="4">
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
                        </v-row>

                        <div class="mt-8 d-flex justify-center">
                            <v-btn
                                color="primary"
                                class="mr-4"
                                large
                                :href="route('nodes.index')"
                            >
                                <v-icon left>mdi-file-tree</v-icon>
                                Kelola Data
                            </v-btn>

                            <v-btn
                                color="secondary"
                                class="mr-4"
                                large
                                :href="route('public.index')"
                                target="_blank"
                            >
                                <v-icon left>mdi-eye-outline</v-icon>
                                Lihat Tampilan Publik
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
import { Head } from "@inertiajs/vue3";

defineProps({
    totalNodes: Number,
    totalFiles: Number,
    totalYears: Number,
});
</script>
