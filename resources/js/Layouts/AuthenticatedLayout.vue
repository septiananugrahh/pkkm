<script setup>
import { ref, onMounted } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3"; // pakai router
import axios from "axios";
import { useLoading } from "@/Composables/useLoading";

const { isLoading, startLoading, finishLoading } = useLoading();

const page = usePage();
const mobileMenu = ref(false);
const isMobile = ref(window.innerWidth < 640); // breakpoint sm

// Update isMobile saat resize
window.addEventListener("resize", () => {
    isMobile.value = window.innerWidth < 640;
});

const menuItems = [
    {
        name: "Dashboard",
        route: route("dashboard"),
        icon: "mdi-view-dashboard",
    },
    {
        name: "Penilaian",
        route: route("nodes.index"),
        icon: "mdi-file-tree-outline",
    },
    {
        name: "Tampilan Publik",
        route: route("show.index"),
        icon: "mdi-eye-outline",
    },
];

const logout = () => {
    axios
        .post(route("logout"))
        .then(() => (window.location.href = route("login")))
        .catch((err) => console.error("Logout gagal:", err));
};

const isActiveRoute = (routeUrl) => {
    return (
        window.location.pathname ===
        new URL(routeUrl, window.location.origin).pathname
    );
};

// ✅ Event inertia dengan router
onMounted(() => {
    // Gunakan composable untuk mengelola status loading
    router.on("start", () => startLoading());
    router.on("finish", () => finishLoading());
});
</script>

<template>
    <v-app>
        <div v-if="isLoading" class="gear-overlay">
            <div class="gears-container">
                <svg
                    class="gear small-gear"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 100 100"
                >
                    <defs>
                        <linearGradient
                            id="smallGearGradient"
                            x1="0%"
                            y1="0%"
                            x2="100%"
                            y2="100%"
                        >
                            <stop offset="0%" style="stop-color: #6a1b9a" />
                            <stop offset="100%" style="stop-color: #9c27b0" />
                        </linearGradient>
                    </defs>
                    <path
                        d="M50 0 L58.55 12.35 L70.21 12.04 L75.92 23.32 L87.61 24.52 L91.68 35.53 L100 50 L91.68 64.47 L87.61 75.48 L75.92 76.68 L70.21 87.96 L58.55 87.65 L50 100 L41.45 87.65 L29.79 87.96 L24.08 76.68 L12.39 75.48 L8.32 64.47 L0 50 L8.32 35.53 L12.39 24.52 L24.08 23.32 L29.79 12.04 L41.45 12.35 Z"
                        fill="url(#smallGearGradient)"
                    />
                    <circle cx="50" cy="50" r="15" fill="#4a148c" />
                </svg>

                <svg
                    class="gear large-gear"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 100 100"
                >
                    <defs>
                        <linearGradient
                            id="largeGearGradient"
                            x1="0%"
                            y1="0%"
                            x2="100%"
                            y2="100%"
                        >
                            <stop offset="0%" style="stop-color: #8e24aa" />
                            <stop offset="100%" style="stop-color: #ba68c8" />
                        </linearGradient>
                    </defs>
                    <path
                        d="M50 0 L58.55 12.35 L70.21 12.04 L75.92 23.32 L87.61 24.52 L91.68 35.53 L100 50 L91.68 64.47 L87.61 75.48 L75.92 76.68 L70.21 87.96 L58.55 87.65 L50 100 L41.45 87.65 L29.79 87.96 L24.08 76.68 L12.39 75.48 L8.32 64.47 L0 50 L8.32 35.53 L12.39 24.52 L24.08 23.32 L29.79 12.04 L41.45 12.35 Z"
                        fill="url(#largeGearGradient)"
                    />
                    <circle cx="50" cy="50" r="15" fill="#4a148c" />
                </svg>
            </div>
        </div>

        <!-- Top App Bar -->
        <v-app-bar app dark color="purple accent-4" elevate-on-scroll>
            <v-toolbar-title class="font-bold text-white">PKKM</v-toolbar-title>
            <v-spacer />

            <!-- Hamburger Menu hanya mobile -->
            <v-app-bar-nav-icon
                v-if="isMobile"
                @click="mobileMenu = !mobileMenu"
            />

            <!-- Horizontal Menu (desktop) -->
            <div v-if="!isMobile" class="flex">
                <Link
                    v-for="item in menuItems"
                    :key="item.name"
                    :href="item.route"
                    class="mx-1"
                >
                    <v-btn
                        text
                        class="text-white font-medium"
                        :class="{
                            'bg-purple-700 rounded-lg px-3': isActiveRoute(
                                item.route
                            ),
                        }"
                    >
                        <v-icon left>{{ item.icon }}</v-icon>
                        {{ item.name }}
                    </v-btn>
                </Link>
            </div>

            <!-- User Dropdown -->
            <v-menu offset-y>
                <template #activator="{ props: menuProps, on: menuOn }">
                    <v-btn
                        v-bind="menuProps"
                        v-on="menuOn"
                        text
                        class="ml-2 text-white"
                    >
                        {{ page.props.auth.user.name }}
                        <v-icon end>mdi-menu-down</v-icon>
                    </v-btn>
                </template>

                <v-list dense>
                    <v-list-item>
                        <Link
                            :href="route('profile.edit')"
                            class="flex items-center gap-2 w-full text-left text-sm font-medium"
                        >
                            <v-icon>mdi-account</v-icon>
                            Profile
                        </Link>
                    </v-list-item>

                    <v-list-item>
                        <v-btn
                            @click="logout"
                            text
                            class="w-full justify-start text-left text-sm font-medium flex items-center gap-2 text-red-600"
                        >
                            <v-icon>mdi-logout</v-icon>
                            Logout
                        </v-btn>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>

        <!-- Mobile Menu Drawer hanya mobile -->
        <v-navigation-drawer
            v-if="isMobile"
            v-model="mobileMenu"
            temporary
            right
            app
        >
            <v-list nav dense>
                <v-list-item
                    v-for="item in menuItems"
                    :key="item.name"
                    :href="item.route"
                    :class="{
                        'bg-purple-100 rounded-lg': isActiveRoute(item.route),
                    }"
                    link
                >
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>{{ item.name }}</v-list-item-title>
                </v-list-item>

                <v-divider class="my-2" />

                <v-list-item
                    :href="route('profile.edit')"
                    link
                    class="flex items-center gap-2 text-sm font-medium"
                >
                    <v-icon>mdi-account</v-icon>
                    <v-list-item-title>Profile</v-list-item-title>
                </v-list-item>

                <v-list-item
                    @click="logout"
                    link
                    class="flex items-center gap-2 text-sm font-medium text-red-600"
                >
                    <v-icon>mdi-logout</v-icon>
                    <v-list-item-title>Logout</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <!-- Page Content with Gradient Background -->
        <v-main>
            <div
                class="min-h-screen py-6 px-4 sm:px-6 lg:px-8"
                style="
                    background: linear-gradient(
                        135deg,
                        #f3e8ff 0%,
                        #c4b5fd 100%
                    );
                "
            >
                <div class="mb-6">
                    <slot name="header"></slot>
                </div>

                <div class="bg-white/80 p-6 rounded-xl shadow-lg">
                    <slot></slot>
                </div>
            </div>
        </v-main>
    </v-app>
</template>

<style scoped>
.v-btn:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Gaya untuk Overlay */
.gear-overlay {
    position: fixed;
    inset: 0;
    background-color: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    pointer-events: none;
    transition: opacity 0.5s ease;
}

.gears-container {
    position: relative;
    width: 200px; /* Lebar container untuk menampung kedua gear */
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Base style untuk semua gear SVG */
.gear {
    position: absolute;
    fill: #66bb6a;
    stroke: #4caf50;
    stroke-width: 1;
    transform-origin: center center;
}

/* Ukuran dan posisi untuk gear kecil */
.small-gear {
    width: 80px;
    height: 80px;
    left: 20%; /* Pindahkan ke kiri sedikit */
    animation: rotate-forward 4s infinite linear;
}

/* Ukuran dan posisi untuk gear besar */
.large-gear {
    width: 120px;
    height: 120px;
    right: 20%; /* Pindahkan ke kanan sedikit */
    animation: rotate-reverse 4s infinite linear;
}

/* Keyframes untuk rotasi ke depan */
@keyframes rotate-forward {
    to {
        transform: rotate(360deg);
    }
}

/* Keyframes untuk rotasi ke belakang */
@keyframes rotate-reverse {
    to {
        transform: rotate(-360deg);
    }
}
</style>
