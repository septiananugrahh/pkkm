<script setup>
import { ref, onMounted } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3"; // pakai router
import axios from "axios";
import { useLoading } from "@/Composables/useLoading";

const { isLoading } = useLoading();

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
    router.on("start", () => (loading.value = true));
    router.on("finish", () => (loading.value = false));
});
</script>

<template>
    <v-app>
        <div v-if="isLoading" class="overlay">
            <div class="spinner"></div>
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

.overlay {
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

.spinner {
    width: 60px;
    height: 60px;
    border: 6px solid #ccc;
    border-top: 6px solid #6b21a8;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
