<template>
    <Head title="Penilaian" />
    <AuthenticatedLayout>
        <template #header>
            <div class="page-header">
                <div class="d-flex align-center">
                    <v-icon color="purple-darken-2" size="32" class="mr-3">
                        mdi-file-tree-outline
                    </v-icon>
                    <div>
                        <h1
                            class="text-2xl font-weight-bold text-purple-800 mb-1"
                        >
                            Daftar Penilaian
                        </h1>
                        <p class="text-purple-600 text-sm">
                            Kelola dan pantau progress penilaian PKKM
                        </p>
                    </div>
                </div>
            </div>
        </template>

        <!-- Statistics Cards -->
        <v-row class="mb-6">
            <v-col cols="12" sm="6" md="3">
                <v-card class="stat-mini-card stat-card-purple" elevation="2">
                    <v-card-text class="text-center pa-4">
                        <v-icon size="28" color="white" class="mb-2"
                            >mdi-folder-multiple</v-icon
                        >
                        <div class="text-h6 font-weight-bold text-white">
                            {{ totalNodes }}
                        </div>
                        <div class="text-caption text-purple-100">
                            Total Nodes
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="3">
                <v-card class="stat-mini-card stat-card-green" elevation="2">
                    <v-card-text class="text-center pa-4">
                        <v-icon size="28" color="white" class="mb-2"
                            >mdi-check-circle</v-icon
                        >
                        <div class="text-h6 font-weight-bold text-white">
                            {{ completedNodes }}
                        </div>
                        <div class="text-caption text-green-100">Selesai</div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="3">
                <v-card class="stat-mini-card stat-card-orange" elevation="2">
                    <v-card-text class="text-center pa-4">
                        <v-icon size="28" color="white" class="mb-2"
                            >mdi-clock-outline</v-icon
                        >
                        <div class="text-h6 font-weight-bold text-white">
                            {{ pendingNodes }}
                        </div>
                        <div class="text-caption text-orange-100">Pending</div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="3">
                <v-card class="stat-mini-card stat-card-blue" elevation="2">
                    <v-card-text class="text-center pa-4">
                        <v-icon size="28" color="white" class="mb-2"
                            >mdi-percent</v-icon
                        >
                        <div class="text-h6 font-weight-bold text-white">
                            {{ completionRate }}%
                        </div>
                        <div class="text-caption text-blue-100">Progress</div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- Filter & Search Section -->
        <v-card class="mb-6" elevation="2">
            <v-card-text>
                <v-row align="center">
                    <v-col cols="12" md="6">
                        <v-select
                            v-model="selectedYear"
                            :items="years"
                            :item-title="isYearObjects ? 'year' : null"
                            :item-value="isYearObjects ? 'id' : null"
                            label="Pilih Tahun"
                            variant="outlined"
                            density="comfortable"
                            color="purple"
                            @update:modelValue="reload"
                            class="filter-select"
                        >
                            <template v-slot:prepend-inner>
                                <v-icon color="purple" size="small"
                                    >mdi-calendar</v-icon
                                >
                            </template>
                        </v-select>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="searchQuery"
                            label="Cari Nama Penilaian"
                            variant="outlined"
                            density="comfortable"
                            color="purple"
                            clearable
                            class="search-field"
                        >
                            <template v-slot:prepend-inner>
                                <v-icon color="purple" size="small"
                                    >mdi-magnify</v-icon
                                >
                            </template>
                        </v-text-field>
                    </v-col>

                    <!-- <v-col cols="12" md="2">
                        <v-btn
                            color="purple"
                            variant="elevated"
                            size="large"
                            block
                            class="add-btn"
                            @click="openModal(null)"
                        >
                            <v-icon class="mr-2">mdi-plus</v-icon>
                            Tambah
                        </v-btn>
                    </v-col> -->
                </v-row>
            </v-card-text>
        </v-card>

        <!-- Tree Content -->
        <v-card elevation="2" class="tree-container">
            <v-card-title class="d-flex align-center pa-4">
                <v-icon color="purple" class="mr-2">mdi-file-tree</v-icon>
                <span>Struktur Penilaian</span>
                <v-spacer></v-spacer>
                <v-chip
                    :color="filteredNodes.length > 0 ? 'green' : 'grey'"
                    size="small"
                    variant="elevated"
                >
                    {{ filteredNodes.length }} item{{
                        filteredNodes.length > 1 ? "s" : ""
                    }}
                </v-chip>
            </v-card-title>

            <v-divider></v-divider>

            <v-card-text class="pa-0">
                <div v-if="filteredNodes.length === 0" class="empty-state">
                    <div class="text-center pa-8">
                        <v-icon size="64" color="grey-lighten-2" class="mb-4">
                            {{
                                searchQuery
                                    ? "mdi-magnify-close"
                                    : "mdi-folder-open-outline"
                            }}
                        </v-icon>
                        <h3 class="text-h6 text-grey mb-2">
                            {{
                                searchQuery
                                    ? "Tidak ada hasil pencarian"
                                    : "Belum ada data penilaian"
                            }}
                        </h3>
                        <p class="text-grey">
                            {{
                                searchQuery
                                    ? "Coba ubah kata kunci pencarian Anda"
                                    : "Mulai dengan menambahkan penilaian baru"
                            }}
                        </p>
                        <v-btn
                            v-if="!searchQuery"
                            color="purple"
                            variant="elevated"
                            class="mt-4"
                            @click="openModal(null)"
                        >
                            <v-icon class="mr-2">mdi-plus</v-icon>
                            Tambah Penilaian Pertama
                        </v-btn>
                    </div>
                </div>

                <div v-else class="tree-content">
                    <node-item
                        v-for="node in filteredNodes"
                        :key="node.id"
                        :node="node"
                        @add-child="openModal"
                        @update-completion="handleCompletionUpdate"
                        class="node-wrapper"
                    />
                </div>
            </v-card-text>
        </v-card>

        <!-- Modal Tambah Node -->
        <v-dialog v-model="showModal" max-width="600" persistent>
            <v-card class="modal-card">
                <v-card-title
                    class="d-flex align-center pa-6 bg-purple-lighten-5"
                >
                    <v-icon color="purple" class="mr-3">mdi-plus-circle</v-icon>
                    <span class="text-h6 font-weight-bold text-purple-800">
                        Tambah Node Baru
                    </span>
                </v-card-title>

                <v-divider></v-divider>

                <v-card-text class="pa-6">
                    <v-form @submit.prevent="submit">
                        <v-text-field
                            v-model="form.title"
                            label="Nama Penilaian"
                            variant="outlined"
                            color="purple"
                            density="comfortable"
                            required
                            :rules="[
                                (v) => !!v || 'Nama penilaian wajib diisi',
                            ]"
                            class="form-field"
                        >
                            <template v-slot:prepend-inner>
                                <v-icon color="purple" size="small"
                                    >mdi-text</v-icon
                                >
                            </template>
                        </v-text-field>

                        <div class="form-info mt-4">
                            <v-alert
                                type="info"
                                variant="tonal"
                                color="purple"
                                density="compact"
                                class="text-sm"
                            >
                                <template v-slot:prepend>
                                    <v-icon>mdi-information</v-icon>
                                </template>
                                Node baru akan ditambahkan
                                {{
                                    form.parent_id
                                        ? "sebagai sub-item"
                                        : "sebagai item utama"
                                }}
                            </v-alert>
                        </div>
                    </v-form>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions class="pa-6">
                    <v-spacer />
                    <v-btn
                        variant="text"
                        color="grey"
                        @click="showModal = false"
                        class="mr-2"
                    >
                        <v-icon class="mr-1">mdi-close</v-icon>
                        Batal
                    </v-btn>
                    <v-btn
                        color="purple"
                        variant="elevated"
                        @click="submit"
                        :loading="form.processing"
                        :disabled="!form.title"
                    >
                        <v-icon class="mr-1">mdi-content-save</v-icon>
                        Simpan
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, computed, watch } from "vue";
import { useForm, router, Head } from "@inertiajs/vue3";
import NodeItem from "@/Pages/Nodes/NodeItem.vue";

const props = defineProps({
    nodes: Array,
    years: Array,
    year: [Number, String, null],
});

// Statistics computed
const totalNodes = computed(() => {
    const countNodes = (nodes) => {
        let count = 0;
        nodes.forEach((node) => {
            count += 1;
            if (node.children && node.children.length > 0) {
                count += countNodes(node.children);
            }
        });
        return count;
    };
    return countNodes(props.nodes || []);
});

const completedNodes = computed(() => {
    const countCompleted = (nodes) => {
        let count = 0;
        nodes.forEach((node) => {
            if (node.is_completed) count += 1;
            if (node.children && node.children.length > 0) {
                count += countCompleted(node.children);
            }
        });
        return count;
    };
    return countCompleted(props.nodes || []);
});

const pendingNodes = computed(() => {
    return totalNodes.value - completedNodes.value;
});

const completionRate = computed(() => {
    if (totalNodes.value === 0) return 0;
    return Math.round((completedNodes.value / totalNodes.value) * 100);
});

// Completion handling
const handleCompletionUpdate = ({ id, isCompleted, isLeaf }) => {
    const updatedNode = findNodeById(props.nodes, id);
    if (updatedNode && updatedNode.parent) {
        const parent = updatedNode.parent;
        if (isCompleted) {
            parent.completed_children_count++;
        } else {
            parent.completed_children_count--;
        }
    }
};

const attachParents = (nodes, parent = null) => {
    nodes.forEach((node) => {
        node.parent = parent;
        if (node.children) {
            attachParents(node.children, node);
        }
    });
};

const findNodeById = (nodes, id) => {
    for (const node of nodes) {
        if (node.id === id) return node;
        if (node.children) {
            const found = findNodeById(node.children, id);
            if (found) return found;
        }
    }
    return null;
};

// Search functionality
const searchQuery = ref("");

function filterNodesRecursively(nodes, query) {
    if (!query) return nodes;

    return nodes
        .map((node) => {
            const match = node.title
                .toLowerCase()
                .includes(query.toLowerCase());

            if (match) {
                return {
                    ...node,
                    children: node.children ? node.children : [],
                };
            }

            let filteredChildren = [];
            if (node.children && node.children.length > 0) {
                filteredChildren = filterNodesRecursively(node.children, query);
            }

            if (filteredChildren.length > 0) {
                return {
                    ...node,
                    children: filteredChildren,
                };
            }

            return null;
        })
        .filter((node) => node !== null);
}

const filteredNodes = computed(() =>
    filterNodesRecursively(props.nodes || [], searchQuery.value)
);

// Form handling
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

watch(selectedYear, (val) => {
    form.year_id = val;
});

const openModal = (parentId) => {
    form.reset();
    form.parent_id = parentId;
    form.year_id = selectedYear.value;
    showModal.value = true;
};

const submit = () => {
    form.post(route("nodes.store"), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
            router.get(
                route("nodes.index"),
                { year: selectedYear.value },
                { preserveState: true, replace: true }
            );
        },
    });
};

const reload = () => {
    router.get(
        route("nodes.index"),
        { year: selectedYear.value },
        { preserveState: true }
    );
};
</script>

<style scoped>
.page-header {
    padding: 20px 0;
    text-align: left;
}

/* Statistics Cards - Mini Version */
.stat-mini-card {
    border-radius: 12px !important;
    transition: transform 0.2s ease !important;
}

.stat-mini-card:hover {
    transform: translateY(-2px);
}

.stat-card-purple {
    background: linear-gradient(135deg, #b794f6 0%, #9f7aea 100%) !important;
}

.stat-card-green {
    background: linear-gradient(135deg, #68d391 0%, #48bb78 100%) !important;
}

.stat-card-orange {
    background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%) !important;
}

.stat-card-blue {
    background: linear-gradient(135deg, #63b3ed 0%, #4299e1 100%) !important;
}

/* Form Styling */
.filter-select,
.search-field {
    border-radius: 12px;
}

.add-btn {
    text-transform: none !important;
    font-weight: 600 !important;
    border-radius: 12px !important;
}

/* Tree Container */
.tree-container {
    border-radius: 16px !important;
    overflow: hidden;
}

.tree-content {
    /* max-height: 600px; */
    overflow-y: auto;
}

.node-wrapper {
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.node-wrapper:last-child {
    border-bottom: none;
}

/* Empty State */
.empty-state {
    background: linear-gradient(
        135deg,
        rgba(183, 148, 246, 0.05),
        rgba(243, 232, 255, 0.1)
    );
    border-radius: 12px;
    margin: 16px;
}

/* Modal Styling */
.modal-card {
    border-radius: 16px !important;
    overflow: hidden;
}

.form-field {
    margin-bottom: 16px;
}

.form-info {
    background: rgba(183, 148, 246, 0.05);
    border-radius: 8px;
    padding: 12px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .add-btn {
        margin-top: 16px;
    }

    .page-header h1 {
        font-size: 1.5rem;
    }

    .tree-content {
        max-height: 500px;
    }
}

/* Scrollbar Styling */
.tree-content::-webkit-scrollbar {
    width: 6px;
}

.tree-content::-webkit-scrollbar-track {
    background: rgba(183, 148, 246, 0.1);
    border-radius: 3px;
}

.tree-content::-webkit-scrollbar-thumb {
    background: rgba(183, 148, 246, 0.5);
    border-radius: 3px;
}

.tree-content::-webkit-scrollbar-thumb:hover {
    background: rgba(183, 148, 246, 0.7);
}
</style>
