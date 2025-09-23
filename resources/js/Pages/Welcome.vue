<template>
    <PublicTemplate>
        <Head>Landing Page</Head>
        <div class="py-24 sm:py-32 lg:pb-40">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h1
                        class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl"
                    >
                        Aplikasi Penilaian Kinerja Kepala Madrasah (PKKM)
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        Mempermudah proses penilaian, pengumpulan data, dan
                        pelaporan kinerja kepala madrasah secara terstruktur,
                        transparan, dan efisien.
                    </p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <Link
                            :href="route('public.index')"
                            class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        >
                            Lihat Penilaian Publik
                        </Link>

                        <Link
                            :href="route('login')"
                            class="text-sm font-semibold leading-6 text-gray-900"
                        >
                            Masuk Halaman Admin
                            <span aria-hidden="true">→</span>
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
