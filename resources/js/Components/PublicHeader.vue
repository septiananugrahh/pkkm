<template>
    <header class="absolute inset-x-0 top-0 z-50">
        <nav
            class="flex items-center justify-between p-6 lg:px-8"
            aria-label="Global"
        >
            <div class="flex flex-1 items-center justify-end gap-x-6">
                <!-- Link ke Public Index -->
                <Link
                    :href="route('public.index')"
                    class="text-sm font-semibold leading-6 text-gray-900"
                >
                    Lihat Penilaian Publik
                    <span aria-hidden="true">&rarr;</span>
                </Link>

                <!-- Jika sudah login -->
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Dashboard
                </Link>

                <!-- Jika belum login -->
                <Link
                    v-else
                    :href="route('login')"
                    class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Masuk
                </Link>
            </div>
        </nav>
    </header>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
