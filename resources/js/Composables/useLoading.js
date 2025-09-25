import { ref, onMounted, onUnmounted } from "vue";
import { router } from "@inertiajs/vue3";

const isLoading = ref(false);

export function useLoading() {
    onMounted(() => {
        // Use a function to manage the state
        const start = () => (isLoading.value = true);
        const finish = () => (isLoading.value = false);

        // Register the listeners and store the cleanup functions
        const cleanupStart = router.on("start", start);
        const cleanupFinish = router.on("finish", finish);

        // Clean up listeners when the component is unmounted
        onUnmounted(() => {
            cleanupStart();
            cleanupFinish();
        });
    });

    return {
        isLoading,
        startLoading: () => (isLoading.value = true),
        finishLoading: () => (isLoading.value = false),
    };
}
