<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import axios from "axios";
import { useLoading } from "@/Composables/useLoading";

const { isLoading, startLoading, finishLoading } = useLoading();

const page = usePage();
const mobileMenu = ref(false);
const isMobile = ref(window.innerWidth < 640);

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

onMounted(() => {
    router.on("start", () => startLoading());
    router.on("finish", () => finishLoading());
});
</script>

<template>
    <v-app>
        <!-- Simplified Loading Spinner -->
        <div v-if="isLoading" class="loading-overlay">
            <div class="loading-spinner"></div>
        </div>

        <!-- Lightweight App Bar -->
        <v-app-bar
            app
            elevation="0"
            height="64"
            color="#B794F6"
            class="app-header"
        >
            <div class="d-flex align-center">
                <v-icon color="white" class="mr-2">mdi-cog</v-icon>
                <v-toolbar-title class="text-white font-weight-bold">
                    PKKM
                </v-toolbar-title>
            </div>

            <v-spacer />

            <v-app-bar-nav-icon
                v-if="isMobile"
                @click="mobileMenu = !mobileMenu"
                color="white"
            />

            <!-- Desktop Navigation - Simplified -->
            <div v-if="!isMobile" class="d-flex">
                <Link
                    v-for="item in menuItems"
                    :key="item.name"
                    :href="item.route"
                >
                    <v-btn
                        variant="text"
                        color="white"
                        class="mx-1 nav-btn"
                        :class="{ 'nav-active': isActiveRoute(item.route) }"
                    >
                        <v-icon size="small" class="mr-1">{{
                            item.icon
                        }}</v-icon>
                        {{ item.name }}
                    </v-btn>
                </Link>
            </div>

            <!-- User Menu - Simplified -->
            <v-menu offset-y>
                <template #activator="{ props }">
                    <v-btn
                        v-bind="props"
                        variant="text"
                        color="white"
                        class="ml-2"
                    >
                        {{ page.props.auth.user.name }}
                        <v-icon size="small" class="ml-1"
                            >mdi-chevron-down</v-icon
                        >
                    </v-btn>
                </template>

                <v-list density="compact">
                    <v-list-item>
                        <Link
                            :href="route('profile.edit')"
                            class="d-flex align-center text-decoration-none"
                        >
                            <v-icon size="small" class="mr-2"
                                >mdi-account</v-icon
                            >
                            Profile
                        </Link>
                    </v-list-item>
                    <v-list-item @click="logout">
                        <v-icon size="small" class="mr-2 text-red"
                            >mdi-logout</v-icon
                        >
                        <span class="text-red">Logout</span>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>

        <!-- Lightweight Mobile Navigation -->
        <v-navigation-drawer
            v-if="isMobile"
            v-model="mobileMenu"
            temporary
            location="right"
            app
            width="260"
        >
            <v-list nav density="compact">
                <v-list-subheader class="text-purple-darken-2 font-weight-bold">
                    Menu Navigasi
                </v-list-subheader>

                <Link
                    v-for="item in menuItems"
                    :key="item.name"
                    :href="item.route"
                    as="div"
                >
                    <v-list-item
                        :class="{
                            'bg-purple-lighten-4': isActiveRoute(item.route),
                        }"
                    >
                        <template v-slot:prepend>
                            <v-icon>{{ item.icon }}</v-icon>
                        </template>
                        <v-list-item-title>{{ item.name }}</v-list-item-title>
                    </v-list-item>
                </Link>

                <v-divider class="my-2" />

                <Link :href="route('profile.edit')" as="div">
                    <v-list-item>
                        <template v-slot:prepend>
                            <v-icon>mdi-account</v-icon>
                        </template>
                        <v-list-item-title>Profile</v-list-item-title>
                    </v-list-item>
                </Link>

                <v-list-item @click="logout">
                    <template v-slot:prepend>
                        <v-icon color="red">mdi-logout</v-icon>
                    </template>
                    <v-list-item-title class="text-red"
                        >Logout</v-list-item-title
                    >
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <!-- Optimized Main Content -->
        <v-main class="main-content">
            <div class="pa-4">
                <div v-if="$slots.header" class="mb-4">
                    <slot name="header"></slot>
                </div>

                <v-card class="content-card" elevation="2">
                    <v-card-text class="pa-6">
                        <slot></slot>
                    </v-card-text>
                </v-card>
            </div>
        </v-main>
    </v-app>
</template>

<style scoped>
/* Minimal, performance-focused styles */
.app-header {
    background: linear-gradient(90deg, #b794f6 0%, #d8b4fe 100%) !important;
}

.nav-btn {
    text-transform: none !important;
    border-radius: 8px !important;
}

.nav-active {
    background-color: rgba(255, 255, 255, 0.2) !important;
}

.main-content {
    background: linear-gradient(180deg, #faf5ff 0%, #f3e8ff 100%);
}

.content-card {
    border-radius: 16px !important;
    background-color: rgba(255, 255, 255, 0.95) !important;
}

/* Lightweight loading spinner */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #e0e7ff;
    border-top: 3px solid #b794f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Minimal hover effects */
.nav-btn:hover {
    background-color: rgba(255, 255, 255, 0.15) !important;
}

.v-list-item:hover {
    background-color: rgba(183, 148, 246, 0.1) !important;
}

/* Remove heavy transitions and effects */
* {
    transition: none !important;
}

/* Only essential transitions */
.nav-btn,
.v-list-item {
    transition: background-color 0.2s ease !important;
}

/* Optimize scrolling */
.main-content {
    scroll-behavior: auto;
    -webkit-overflow-scrolling: touch;
}

/* Remove backdrop filters and heavy shadows */
.content-card {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
}
</style>
