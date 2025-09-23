<template>
    <div class="min-h-screen bg-gray-100 font-sans">
        <v-app>
            <!-- Top App Bar -->
            <v-app-bar app color="white" flat class="border-b border-gray-200">
                <v-app-bar-nav-icon @click="drawer = !drawer" />

                <v-toolbar-title class="font-bold text-gray-800">
                    PKKM
                </v-toolbar-title>

                <v-spacer />

                <!-- User Dropdown Menu -->
                <v-menu offset-y attach>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            v-bind="attrs"
                            v-on="on"
                            text
                            class="capitalize text-gray-800"
                        >
                            {{ $page.props.auth.user.name }}
                            <v-icon end>mdi-menu-down</v-icon>
                        </v-btn>
                    </template>

                    <v-list dense>
                        <!-- Profile -->
                        <v-list-item>
                            <Link
                                :href="route('profile.edit')"
                                class="w-full text-left text-sm font-medium"
                            >
                                <v-icon start class="mr-2">mdi-account</v-icon>
                                Profile
                            </Link>
                        </v-list-item>

                        <!-- Logout Form -->
                        <v-list-item>
                            <v-btn
                                @click="logout"
                                text
                                class="w-full justify-start text-sm font-medium text-left text-red-600"
                            >
                                <v-icon start class="mr-2">mdi-logout</v-icon>
                                Log Out
                            </v-btn>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-app-bar>

            <!-- Sidebar -->
            <v-navigation-drawer v-model="drawer" app clipped permanent>
                <v-list nav>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title class="text-h6 text-center">
                                <Link :href="route('dashboard')">PKKM</Link>
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-divider />

                    <!-- Dashboard -->
                    <Link :href="route('dashboard')">
                        <v-list-item link>
                            <v-list-item-content class="flex items-center">
                                <v-icon>mdi-view-dashboard</v-icon>
                                <span class="ml-2">Dashboard</span>
                            </v-list-item-content>
                        </v-list-item>
                    </Link>

                    <!-- Penilaian -->
                    <Link :href="route('nodes.index')">
                        <v-list-item link>
                            <v-list-item-content class="flex items-center">
                                <v-icon>mdi-file-tree-outline</v-icon>
                                <span class="ml-2">Penilaian</span>
                            </v-list-item-content>
                        </v-list-item>
                    </Link>

                    <!-- Tampilan Publik -->
                    <a
                        :href="route('public.index')"
                        target="_blank"
                        class="no-underline"
                    >
                        <v-list-item link>
                            <v-list-item-content class="flex items-center">
                                <v-icon>mdi-eye-outline</v-icon>
                                <span class="ml-2">Tampilan Publik</span>
                            </v-list-item-content>
                        </v-list-item>
                    </a>

                    <!-- Logout -->
                    <v-divider class="my-4" />
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="text-sm font-medium w-full text-left"
                    >
                        <v-icon left>mdi-logout</v-icon>
                        Logout
                    </Link>
                </v-list>
            </v-navigation-drawer>

            <!-- Page Content -->
            <v-main class="bg-gray-50">
                <div class="py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header"></slot>
                </div>
                <div class="px-4 sm:px-6 lg:px-8">
                    <slot></slot>
                </div>
            </v-main>
        </v-app>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import axios from "axios";

const drawer = ref(true);
const page = usePage();

const logout = () => {
    axios
        .post(route("logout")) // Kirim permintaan POST untuk logout
        .then(() => {
            window.location.href = "/login"; // Setelah logout, arahkan ke login
        })
        .catch((error) => {
            console.error("Logout gagal:", error);
        });
};
</script>
