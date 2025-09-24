import { ref, onMounted, onUnmounted } from "vue";
import { router } from "@inertiajs/vue3";

const isLoading = ref(false);

export function useLoading() {
    onMounted(() => {
        router.on("start", () => (isLoading.value = true));
        router.on("finish", () => (isLoading.value = false));
    });

    onUnmounted(() => {
        // optional, supaya event listener bersih
        router.on("start", null);
        router.on("finish", null);
    });

    return { isLoading };
}
