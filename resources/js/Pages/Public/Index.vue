<template>
    <PublicTemplate>
        <Head>Public</Head>
        <div class="py-4 sm:py-12 lg:pb-20">
            <v-container>
                <h2>Struktur Nodes</h2>

                <!-- Pilihan Tahun -->
                <v-row class="mb-4">
                    <v-col cols="12" sm="4">
                        <v-select
                            v-model="selectedYear"
                            :items="years"
                            :item-title="isYearObjects ? 'year' : null"
                            :item-value="isYearObjects ? 'id' : null"
                            label="Pilih Tahun"
                            @update:modelValue="reload"
                        />
                    </v-col>
                </v-row>

                <!-- Tree Node -->
                <div>
                    <PublicNodeItem
                        v-for="node in nodes"
                        :key="node.id"
                        :node="node"
                    />
                </div>
            </v-container>
        </div>
    </PublicTemplate>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { router, Head } from "@inertiajs/vue3";
import PublicNodeItem from "@/Pages/Public/NodeItem.vue"; // sesuaikan path kalau beda
import PublicTemplate from "@/Components/PublicTemplate.vue"; // sesuaikan path jika perlu

const props = defineProps({
    nodes: Array,
    years: Array,
    year: [Number, String, null],
});

const selectedYear = ref(
    props.year ??
        (Array.isArray(props.years) && props.years[0]
            ? typeof props.years[0] === "object"
                ? props.years[0].id
                : props.years[0]
            : null)
);

const isYearObjects = computed(() => {
    return Array.isArray(props.years) && typeof props.years[0] === "object";
});

// Reload saat tahun berubah
watch(selectedYear, (val) => {
    reload();
});

const reload = () => {
    router.get(
        route("show.nodes"),
        { year: selectedYear.value },
        { preserveState: true }
    );
};
</script>
