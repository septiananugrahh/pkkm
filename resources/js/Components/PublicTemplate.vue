<template>
    <div class="relative bg-gray-50 overflow-hidden min-h-screen">
        <PublicHeader />

        <main class="isolate">
            <div class="relative pt-14">
                <div
                    class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
                    aria-hidden="true"
                >
                    <div
                        class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                        style="
                            clip-path: polygon(
                                74.1% 44.1%,
                                100% 61.6%,
                                97.5% 26.9%,
                                85.5% 0.1%,
                                80.7% 2%,
                                72.5% 32.5%,
                                60.2% 62.4%,
                                52.4% 68.1%,
                                47.5% 58.3%,
                                45.2% 34.5%,
                                27.5% 76.7%,
                                0.1% 64.2%,
                                2.7% 76.8%,
                                36.3% 97.7%,
                                70.9% 60.6%,
                                74.1% 44.1%
                            );
                        "
                    ></div>
                </div>
                <slot></slot>
            </div>
        </main>

        <footer class="py-12 bg-white">
            <div
                class="max-w-7xl mx-auto px-6 lg:px-8 text-center text-gray-500 text-sm"
            >
                <p>
                    © MI Alam IC Ponorogo 2025 &mdash; App development by
                    <span class="font-semibold text-gray-700">Unnamed</span>
                </p>
            </div>
        </footer>
    </div>
</template>

<script setup>
import PublicHeader from "@/Components/PublicHeader.vue"; // sesuaikan path jika perlu
</script>
