<template>
    <Head title="Penilaian" />
    <AuthenticatedLayout>
        <h2>Daftar Penilaian</h2>

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

        <!-- Tombol Tambah Node Root -->
        <!-- <v-btn color="primary" class="mb-4" @click="openModal(null)">
            Tambah Penilaian
        </v-btn> -->
        <v-text-field
            v-model="searchQuery"
            label="Cari Nama Penilaian"
            append-icon="mdi-magnify"
            class="mb-4"
            clearable
        />

        <!-- Tree render (rekursif) -->
        <div>
            <node-item
                v-for="node in filteredNodes"
                :key="node.id"
                :node="node"
                @add-child="openModal"
                @update-completion="handleCompletionUpdate"
            />
        </div>

        <!-- Modal Tambah Node -->
        <v-dialog v-model="showModal" max-width="500">
            <v-card>
                <v-card-title>Tambah Node</v-card-title>
                <v-card-text>
                    <v-form @submit.prevent="submit">
                        <v-text-field
                            v-model="form.title"
                            label="Nama Penilaian"
                            required
                        />
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn text @click="showModal = false">Batal</v-btn>
                    <v-btn color="primary" @click="submit">Simpan</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import { ref, computed, watch } from "vue";
import { useForm, router, Head } from "@inertiajs/vue3";
import NodeItem from "@/Pages/Nodes/NodeItem.vue"; // sesuaikan path kalau beda

const props = defineProps({
    nodes: Array,
    years: Array,
    year: [Number, String, null],
});

// Di script setup NodeTree.vue
const handleCompletionUpdate = ({ id, isCompleted, isLeaf }) => {
    // Cari node yang diupdate berdasarkan ID
    const updatedNode = findNodeById(rootNode.value, id);

    // Perbarui jumlah completed children pada induknya
    if (updatedNode && updatedNode.parent) {
        const parent = updatedNode.parent;
        if (isCompleted) {
            parent.completed_children_count++;
        } else {
            parent.completed_children_count--;
        }
        // Dan teruskan ke atas jika perlu...
    }
};

// Pastikan Anda memiliki properti parent di setiap node
const attachParents = (nodes, parent = null) => {
    nodes.forEach((node) => {
        node.parent = parent;
        if (node.children) {
            attachParents(node.children, node);
        }
    });
};

const searchQuery = ref("");

function filterNodesRecursively(nodes, query) {
    if (!query) return nodes;

    return nodes
        .map((node) => {
            const match = node.title
                .toLowerCase()
                .includes(query.toLowerCase());

            // Jika node match → tampilkan node + semua anaknya asli (apapun itu)
            if (match) {
                return {
                    ...node,
                    children: node.children ? node.children : [],
                };
            }

            // Jika node tidak match, periksa anaknya (recursive)
            let filteredChildren = [];
            if (node.children && node.children.length > 0) {
                filteredChildren = filterNodesRecursively(node.children, query);
            }

            // Kalau ada anak yang match → tampilkan node + anak hasil filter
            if (filteredChildren.length > 0) {
                return {
                    ...node,
                    children: filteredChildren,
                };
            }

            // Tidak ada yang match → buang node
            return null;
        })
        .filter((node) => node !== null);
}

const filteredNodes = computed(() =>
    filterNodesRecursively(props.nodes, searchQuery.value)
);

const showModal = ref(false);
const selectedYear = ref(
    props.year ??
        (Array.isArray(props.years) && props.years[0]
            ? typeof props.years[0] === "object"
                ? props.years[0].id
                : props.years[0]
            : null)
);

const form = useForm({
    title: "",
    parent_id: null,
    year_id: selectedYear.value,
});

const isYearObjects = computed(
    () =>
        Array.isArray(props.years) &&
        props.years.length &&
        typeof props.years[0] === "object"
);

// sync form.year_id saat pilihan tahun berubah
watch(selectedYear, (val) => {
    form.year_id = val;
});

// buka modal tambah node (parent null untuk root)
const openModal = (parentId) => {
    form.reset();
    form.parent_id = parentId;
    form.year_id = selectedYear.value;
    showModal.value = true;
};

// kirim ke backend
const submit = () => {
    form.post(route("nodes.store"), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
            // reload agar menampilkan node baru (bisa juga di-handle oleh server flash / Inertia props)
            router.get(
                route("nodes.index"),
                { year: selectedYear.value },
                { preserveState: true, replace: true }
            );
        },
    });
};

// reload nodes berdasarkan tahun
const reload = () => {
    router.get(
        route("nodes.index"),
        { year: selectedYear.value },
        { preserveState: true }
    );
};
</script>
