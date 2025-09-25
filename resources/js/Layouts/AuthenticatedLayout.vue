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
        <div v-if="isLoading" class="spinner-overlay">
            <div class="container">
                <svg
                    class="machine"
                    xmlns="http://www.w3.org/2000/svg"
                    x="0px"
                    y="0px"
                    viewBox="0 0 645 526"
                >
                    <defs />
                    <g>
                        <path
                            x="-173,694"
                            y="-173,694"
                            class="large-shadow"
                            d="M645 194v-21l-29-4c-1-10-3-19-6-28l25-14 -8-19 -28 7c-5-8-10-16-16-24L602 68l-15-15 -23 17c-7-6-15-11-24-16l7-28 -19-8 -14 25c-9-3-18-5-28-6L482 10h-21l-4 29c-10 1-19 3-28 6l-14-25 -19 8 7 28c-8 5-16 10-24 16l-23-17L341 68l17 23c-6 7-11 15-16 24l-28-7 -8 19 25 14c-3 9-5 18-6 28l-29 4v21l29 4c1 10 3 19 6 28l-25 14 8 19 28-7c5 8 10 16 16 24l-17 23 15 15 23-17c7 6 15 11 24 16l-7 28 19 8 14-25c9 3 18 5 28 6l4 29h21l4-29c10-1 19-3 28-6l14 25 19-8 -7-28c8-5 16-10 24-16l23 17 15-15 -17-23c6-7 11-15 16-24l28 7 8-19 -25-14c3-9 5-18 6-28L645 194zM471 294c-61 0-110-49-110-110S411 74 471 74s110 49 110 110S532 294 471 294z"
                        />
                    </g>
                    <g>
                        <path
                            x="-136,996"
                            y="-136,996"
                            class="medium-shadow"
                            d="M402 400v-21l-28-4c-1-10-4-19-7-28l23-17 -11-18L352 323c-6-8-13-14-20-20l11-26 -18-11 -17 23c-9-4-18-6-28-7l-4-28h-21l-4 28c-10 1-19 4-28 7l-17-23 -18 11 11 26c-8 6-14 13-20 20l-26-11 -11 18 23 17c-4 9-6 18-7 28l-28 4v21l28 4c1 10 4 19 7 28l-23 17 11 18 26-11c6 8 13 14 20 20l-11 26 18 11 17-23c9 4 18 6 28 7l4 28h21l4-28c10-1 19-4 28-7l17 23 18-11 -11-26c8-6 14-13 20-20l26 11 11-18 -23-17c4-9 6-18 7-28L402 400zM265 463c-41 0-74-33-74-74 0-41 33-74 74-74 41 0 74 33 74 74C338 430 305 463 265 463z"
                        />
                    </g>
                    <g>
                        <path
                            x="-100,136"
                            y="-100,136"
                            class="small-shadow"
                            d="M210 246v-21l-29-4c-2-10-6-18-11-26l18-23 -15-15 -23 18c-8-5-17-9-26-11l-4-29H100l-4 29c-10 2-18 6-26 11l-23-18 -15 15 18 23c-5 8-9 17-11 26L10 225v21l29 4c2 10 6 18 11 26l-18 23 15 15 23-18c8 5 17 9 26 11l4 29h21l4-29c10-2 18-6 26-11l23 18 15-15 -18-23c5-8 9-17 11-26L210 246zM110 272c-20 0-37-17-37-37s17-37 37-37c20 0 37 17 37 37S131 272 110 272z"
                        />
                    </g>
                    <g>
                        <path
                            x="-100,136"
                            y="-100,136"
                            class="small"
                            d="M200 236v-21l-29-4c-2-10-6-18-11-26l18-23 -15-15 -23 18c-8-5-17-9-26-11l-4-29H90l-4 29c-10 2-18 6-26 11l-23-18 -15 15 18 23c-5 8-9 17-11 26L0 215v21l29 4c2 10 6 18 11 26l-18 23 15 15 23-18c8 5 17 9 26 11l4 29h21l4-29c10-2 18-6 26-11l23 18 15-15 -18-23c5-8 9-17 11-26L200 236zM100 262c-20 0-37-17-37-37s17-37 37-37c20 0 37 17 37 37S121 262 100 262z"
                        />
                    </g>
                    <g>
                        <path
                            x="-173,694"
                            y="-173,694"
                            class="large"
                            d="M635 184v-21l-29-4c-1-10-3-19-6-28l25-14 -8-19 -28 7c-5-8-10-16-16-24L592 58l-15-15 -23 17c-7-6-15-11-24-16l7-28 -19-8 -14 25c-9-3-18-5-28-6L472 0h-21l-4 29c-10 1-19 3-28 6L405 9l-19 8 7 28c-8 5-16 10-24 16l-23-17L331 58l17 23c-6 7-11 15-16 24l-28-7 -8 19 25 14c-3 9-5 18-6 28l-29 4v21l29 4c1 10 3 19 6 28l-25 14 8 19 28-7c5 8 10 16 16 24l-17 23 15 15 23-17c7 6 15 11 24 16l-7 28 19 8 14-25c9 3 18 5 28 6l4 29h21l4-29c10-1 19-3 28-6l14 25 19-8 -7-28c8-5 16-10 24-16l23 17 15-15 -17-23c6-7 11-15 16-24l28 7 8-19 -25-14c3-9 5-18 6-28L635 184zM461 284c-61 0-110-49-110-110S401 64 461 64s110 49 110 110S522 284 461 284z"
                        />
                    </g>
                    <g>
                        <path
                            x="-136,996"
                            y="-136,996"
                            class="medium"
                            d="M392 390v-21l-28-4c-1-10-4-19-7-28l23-17 -11-18L342 313c-6-8-13-14-20-20l11-26 -18-11 -17 23c-9-4-18-6-28-7l-4-28h-21l-4 28c-10 1-19 4-28 7l-17-23 -18 11 11 26c-8 6-14 13-20 20l-26-11 -11 18 23 17c-4 9-6 18-7 28l-28 4v21l28 4c1 10 4 19 7 28l-23 17 11 18 26-11c6 8 13 14 20 20l-11 26 18 11 17-23c9 4 18 6 28 7l4 28h21l4-28c10-1 19-4 28-7l17 23 18-11 -11-26c8-6 14-13 20-20l26 11 11-18 -23-17c4-9 6-18 7-28L392 390zM255 453c-41 0-74-33-74-74 0-41 33-74 74-74 41 0 74 33 74 74C328 420 295 453 255 453z"
                        />
                    </g>
                </svg>
            </div>
        </div>

        <v-app-bar app dark color="purple accent-4" elevate-on-scroll>
            <v-toolbar-title class="font-bold text-white">PKKM</v-toolbar-title>
            <v-spacer />

            <v-app-bar-nav-icon
                v-if="isMobile"
                @click="mobileMenu = !mobileMenu"
            />

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

        <v-navigation-drawer
            v-if="isMobile"
            v-model="mobileMenu"
            temporary
            right
            app
        >
            <v-list nav dense>
                <Link
                    v-for="item in menuItems"
                    :key="item.name"
                    :href="item.route"
                    :class="{
                        'bg-purple-100 rounded-lg': isActiveRoute(item.route),
                    }"
                    as="div"
                >
                    <v-list-item
                        link
                        class="flex items-center gap-2 text-sm font-medium"
                    >
                        <v-list-item-icon>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>{{ item.name }}</v-list-item-title>
                    </v-list-item>
                </Link>

                <v-divider class="my-2" />

                <Link
                    :href="route('profile.edit')"
                    as="div"
                    class="flex items-center gap-2 text-sm font-medium"
                >
                    <v-list-item link>
                        <v-list-item-icon>
                            <v-icon>mdi-account</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>Profile</v-list-item-title>
                    </v-list-item>
                </Link>

                <v-list-item
                    @click="logout"
                    link
                    class="flex items-center gap-2 text-sm font-medium text-red-600"
                >
                    <v-list-item-icon>
                        <v-icon>mdi-logout</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Logout</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

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

body,
html {
    width: 100%;
    height: 100%;
    background-color: #ffffff;
}

.container {
    height: 100%;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
}

.machine {
    width: 60vmin;
    fill: #4a148c;
}

.small-shadow,
.medium-shadow,
.large-shadow {
    fill: rgba(0, 0, 0, 0.05);
}

.small {
    -webkit-animation: counter-rotation 2.5s infinite linear;
    -moz-animation: counter-rotation 2.5s infinite linear;
    -o-animation: counter-rotation 2.5s infinite linear;
    animation: counter-rotation 2.5s infinite linear;
    -webkit-transform-origin: 100.136px 225.345px;
    -ms-transform-origin: 100.136px 225.345px;
    transform-origin: 100.136px 225.345px;
}

.small-shadow {
    -webkit-animation: counter-rotation 2.5s infinite linear;
    -moz-animation: counter-rotation 2.5s infinite linear;
    -o-animation: counter-rotation 2.5s infinite linear;
    animation: counter-rotation 2.5s infinite linear;
    -webkit-transform-origin: 110.136px 235.345px;
    -ms-transform-origin: 110.136px 235.345px;
    transform-origin: 110.136px 235.345px;
}

.medium {
    -webkit-animation: rotation 3.75s infinite linear;
    -moz-animation: rotation 3.75s infinite linear;
    -o-animation: rotation 3.75s infinite linear;
    animation: rotation 3.75s infinite linear;
    -webkit-transform-origin: 254.675px 379.447px;
    -ms-transform-origin: 254.675px 379.447px;
    transform-origin: 254.675px 379.447px;
}

.medium-shadow {
    -webkit-animation: rotation 3.75s infinite linear;
    -moz-animation: rotation 3.75s infinite linear;
    -o-animation: rotation 3.75s infinite linear;
    animation: rotation 3.75s infinite linear;
    -webkit-transform-origin: 264.675px 389.447px;
    -ms-transform-origin: 264.675px 389.447px;
    transform-origin: 264.675px 389.447px;
}

.large {
    -webkit-animation: counter-rotation 5s infinite linear;
    -moz-animation: counter-rotation 5s infinite linear;
    -o-animation: counter-rotation 5s infinite linear;
    animation: counter-rotation 5s infinite linear;
    -webkit-transform-origin: 461.37px 173.694px;
    -ms-transform-origin: 461.37px 173.694px;
    transform-origin: 461.37px 173.694px;
}

.large-shadow {
    -webkit-animation: counter-rotation 5s infinite linear;
    -moz-animation: counter-rotation 5s infinite linear;
    -o-animation: counter-rotation 5s infinite linear;
    animation: counter-rotation 5s infinite linear;
    -webkit-transform-origin: 471.37px 183.694px;
    -ms-transform-origin: 471.37px 183.694px;
    transform-origin: 471.37px 183.694px;
}

@-webkit-keyframes rotation {
    from {
        -webkit-transform: rotate(0deg);
    }
    to {
        -webkit-transform: rotate(359deg);
    }
}
@-moz-keyframes rotation {
    from {
        -moz-transform: rotate(0deg);
    }
    to {
        -moz-transform: rotate(359deg);
    }
}
@-o-keyframes rotation {
    from {
        -o-transform: rotate(0deg);
    }
    to {
        -o-transform: rotate(359deg);
    }
}
@keyframes rotation {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(359deg);
    }
}

@-webkit-keyframes counter-rotation {
    from {
        -webkit-transform: rotate(359deg);
    }
    to {
        -webkit-transform: rotate(0deg);
    }
}
@-moz-keyframes counter-rotation {
    from {
        -moz-transform: rotate(359deg);
    }
    to {
        -moz-transform: rotate(0deg);
    }
}
@-o-keyframes counter-rotation {
    from {
        -o-transform: rotate(359deg);
    }
    to {
        -o-transform: rotate(0deg);
    }
}
@keyframes counter-rotation {
    from {
        transform: rotate(359deg);
    }
    to {
        transform: rotate(0deg);
    }
}

/* Gaya untuk Overlay Spinner */
.spinner-overlay {
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

.spinner-container {
    width: 60px;
    height: 60px;
    position: relative;
}

.loading-spinner {
    width: 60px;
    height: 60px;
    border: 6px solid #e0b0ff; /* Border luar */
    border-top: 6px solid #4a148c; /* Bagian atas yang berputar */
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
</style>
