<template>
    <div class="min-h-screen bg-gray-100 font-sans">
        <v-app>
            <v-app-bar app color="white" flat class="border-b border-gray-200">
                <v-app-bar-nav-icon
                    @click="drawer = !drawer"
                ></v-app-bar-nav-icon>

                <v-toolbar-title class="font-bold text-gray-800"
                    >PKKM</v-toolbar-title
                >

                <v-spacer></v-spacer>

                <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn text class="capitalize" v-bind="attrs" v-on="on">
                            {{ $page.props.auth.user.name }}
                            <v-icon right>mdi-menu-down</v-icon>
                        </v-btn>
                    </template>
                    <v-list dense>
                        <v-list-item>
                            <v-list-item-title>
                                <Link
                                    :href="route('profile.edit')"
                                    class="text-sm font-medium"
                                >
                                    Profile
                                </Link>
                            </v-list-item-title>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-title>
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="text-sm font-medium w-full text-left"
                                >
                                    Log Out
                                </Link>
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-app-bar>

            <v-navigation-drawer v-model="drawer" app clipped permanent>
                <v-list nav>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title class="text-h6 text-center">
                                <Link :href="route('dashboard')"> PKKM </Link>
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-divider></v-divider>

                    <Link :href="route('dashboard')">
                        <v-list-item link>
                            <v-list-item-content>
                                <v-icon>mdi-view-dashboard</v-icon>
                                <span class="ml-2">Dashboard</span>
                            </v-list-item-content>
                        </v-list-item>
                    </Link>

                    <Link :href="route('nodes.index')">
                        <v-list-item link>
                            <v-list-item-content>
                                <v-icon>mdi-file-tree-outline</v-icon>
                                <span class="ml-2">Penilaian</span>
                            </v-list-item-content>
                        </v-list-item>
                    </Link>

                    <a
                        :href="route('public.index')"
                        target="_blank"
                        class="no-underline"
                    >
                        <v-list-item link>
                            <v-list-item-content>
                                <v-icon>mdi-eye-outline</v-icon>
                                <span class="ml-2">Tampilan Publik</span>
                            </v-list-item-content>
                        </v-list-item>
                    </a>
                </v-list>
            </v-navigation-drawer>

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
import { Link } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const drawer = ref(true); // Default open sidebar
</script>
