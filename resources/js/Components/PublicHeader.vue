<template>
    <header
        class="absolute inset-x-0 top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-200/20"
    >
        <nav
            class="flex items-center justify-between p-6 lg:px-8 max-w-7xl mx-auto"
            aria-label="Global"
        >
            <!-- Logo/Brand -->
            <div class="flex items-center">
                <div class="flex items-center space-x-2">
                    <div
                        class="w-8 h-8 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center"
                    >
                        <v-icon color="white" size="small">mdi-school</v-icon>
                    </div>
                    <span
                        class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent"
                    >
                        PKKM
                    </span>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="flex flex-1 items-center justify-end gap-x-8">
                <!-- Link ke Public Index -->
                <Link
                    :href="route('welcome')"
                    class="relative inline-flex items-center text-sm font-semibold leading-6 text-gray-700 hover:text-indigo-600 transition-colors duration-200 z-10"
                >
                    <v-icon class="mr-2" size="small" color="gray-600"
                        >mdi-home</v-icon
                    >
                    Beranda
                </Link>

                <Link
                    :href="route('show.index')"
                    class="relative inline-flex items-center text-sm font-semibold leading-6 text-gray-700 hover:text-indigo-600 transition-colors duration-200 z-10"
                >
                    <v-icon class="mr-2" size="small" color="gray-600"
                        >mdi-clipboard-list</v-icon
                    >
                    Penilaian
                </Link>

                <!-- Jika sudah login -->
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="relative inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 z-10"
                >
                    <v-icon class="mr-2" size="small" color="white"
                        >mdi-view-dashboard</v-icon
                    >
                    Dashboard
                </Link>

                <!-- Jika belum login -->
                <Link
                    v-else
                    :href="route('login')"
                    class="relative inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 z-10"
                >
                    <v-icon class="mr-2" size="small" color="white"
                        >mdi-login</v-icon
                    >
                    Masuk
                </Link>

                <!-- User Menu (if logged in) -->
                <div v-if="$page.props.auth.user" class="relative z-20">
                    <v-menu offset-y>
                        <template v-slot:activator="{ props }">
                            <button
                                v-bind="props"
                                class="flex items-center space-x-2 rounded-full bg-gray-100 p-2 hover:bg-gray-200 transition-colors duration-200"
                            >
                                <div
                                    class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center"
                                >
                                    <v-icon color="white" size="small"
                                        >mdi-account</v-icon
                                    >
                                </div>
                            </button>
                        </template>

                        <v-list class="min-w-48">
                            <v-list-item>
                                <v-list-item-title class="font-medium">
                                    {{ $page.props.auth.user.name }}
                                </v-list-item-title>
                                <v-list-item-subtitle>
                                    {{ $page.props.auth.user.email }}
                                </v-list-item-subtitle>
                            </v-list-item>

                            <v-divider></v-divider>

                            <v-list-item
                                :href="route('profile.edit')"
                                class="hover:bg-gray-50"
                            >
                                <template v-slot:prepend>
                                    <v-icon>mdi-account-edit</v-icon>
                                </template>
                                <v-list-item-title>Profile</v-list-item-title>
                            </v-list-item>

                            <v-list-item
                                :href="route('logout')"
                                method="post"
                                class="hover:bg-red-50 text-red-600"
                            >
                                <template v-slot:prepend>
                                    <v-icon color="red">mdi-logout</v-icon>
                                </template>
                                <v-list-item-title>Logout</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </div>
        </nav>
    </header>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<style scoped>
/* Glassmorphism Effect */
.backdrop-blur-md {
    backdrop-filter: blur(12px);
}

/* Gradient Text */
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom shadows */
.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.hover\:shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Ensure proper stacking and no overlap */
.relative {
    position: relative;
}

/* Remove any potential pseudo-elements that might cause overlap */
.z-10 {
    z-index: 10;
}

.z-20 {
    z-index: 20;
}
</style>
