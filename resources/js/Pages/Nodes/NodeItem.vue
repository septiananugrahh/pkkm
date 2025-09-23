<template>
    <div class="node-item">
        <v-row align="center" class="py-1 node-header" @click.stop="toggle">
            <v-col cols="auto">
                <v-icon small v-if="hasChildren">mdi-folder</v-icon>
                <v-icon small v-else>mdi-file-outline</v-icon>
            </v-col>
            <v-col>
                <div>
                    {{ node.title }}
                    <span v-if="hasChildren" class="ml-2 text-sm text-gray-500">
                        ({{ completionPercentage }}%)
                    </span>
                    <span v-else class="ml-2 text-sm text-gray-500">
                        ({{
                            node.files && node.files.length > 0 ? "100%" : "0%"
                        }})
                    </span>
                </div>
            </v-col>
            <v-col cols="auto">
                <v-icon :color="isCompletedAll ? 'green' : 'red'">
                    {{
                        isCompletedAll ? "mdi-check-circle" : "mdi-close-circle"
                    }}
                </v-icon>
            </v-col>
            <v-col cols="auto" class="d-flex align-center">
                <v-btn small text @click.stop="openEditModal(node)">
                    <v-icon left>mdi-pencil</v-icon>
                </v-btn>
                <v-btn
                    small
                    text
                    class="ml-2"
                    @click.stop="$emit('add-child', node.id)"
                >
                    <v-icon left>mdi-plus</v-icon>
                </v-btn>
                <!-- <v-btn
                    small
                    text
                    color="error"
                    class="ml-2"
                    @click.stop="deleteNode(node.id)"
                >
                    <v-icon>mdi-delete</v-icon>
                </v-btn> -->
            </v-col>
        </v-row>

        <v-expand-transition>
            <div v-if="expanded" class="node-children-wrapper">
                <div class="node-indented-content">
                    <v-card
                        v-if="!hasChildren"
                        class="rounded-lg shadow-md transition-all duration-300 hover:shadow-lg"
                        outlined
                    >
                        <div
                            v-if="node.is_completed"
                            class="mb-4 text-green-600 font-semibold"
                        >
                            Penilaian: Selesai
                        </div>
                        <v-card-text>
                            <div
                                class="flex items-center justify-between border-b pb-3 mb-3"
                            >
                                <div class="space-y-1">
                                    <!-- <div
                                        class="text-sm font-semibold text-gray-700"
                                    >
                                        ID: {{ node.id }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Tahun:
                                        {{
                                            node.year
                                                ? node.year.year
                                                : node.year_id
                                        }}
                                    </div> -->
                                </div>
                                <v-btn
                                    :color="
                                        node.is_completed ? 'error' : 'success'
                                    "
                                    @click.stop="toggleComplete(node)"
                                    class="font-semibold"
                                >
                                    <v-icon left>
                                        {{
                                            node.is_completed
                                                ? "mdi-close-circle"
                                                : "mdi-check-circle"
                                        }}
                                    </v-icon>
                                    <span>
                                        {{
                                            node.is_completed
                                                ? "Batal Selesai"
                                                : "Tandai Selesai"
                                        }}
                                    </span>
                                </v-btn>
                            </div>

                            <div class="mb-4">
                                <h4
                                    class="text-lg font-bold text-gray-800 mb-2"
                                >
                                    Upload Files
                                </h4>
                                <v-form @submit.prevent="uploadFiles">
                                    <v-file-input
                                        v-model="fileForm.files"
                                        multiple
                                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                        label="Pilih file"
                                        show-size
                                        outlined
                                        dense
                                    />
                                    <v-btn
                                        type="submit"
                                        color="primary"
                                        :disabled="
                                            !fileForm.files ||
                                            fileForm.files.length === 0
                                        "
                                        :loading="fileForm.processing"
                                        class="w-full mt-2"
                                        large
                                    >
                                        Upload
                                    </v-btn>
                                </v-form>
                            </div>

                            <div v-if="node.files && node.files.length">
                                <h4
                                    class="text-lg font-bold text-gray-800 mb-2"
                                >
                                    Files:
                                </h4>
                                <v-list dense class="bg-transparent">
                                    <v-list-item
                                        v-for="f in node.files"
                                        :key="f.id"
                                        class="px-0"
                                    >
                                        <div
                                            class="w-full flex items-center justify-between py-2 border-b last:border-0"
                                        >
                                            <div
                                                class="flex items-center space-x-2"
                                            >
                                                <v-icon
                                                    small
                                                    :color="
                                                        getFileIconColor(
                                                            f.file_name
                                                        )
                                                    "
                                                >
                                                    {{
                                                        getFileIcon(f.file_name)
                                                    }}
                                                </v-icon>
                                                <a
                                                    :href="`/storage/${f.file_path}`"
                                                    target="_blank"
                                                    @click.prevent="
                                                        openFileViewer(f)
                                                    "
                                                    class="text-blue-600 hover:underline transition-colors duration-200"
                                                >
                                                    {{ f.file_name }}
                                                </a>
                                            </div>
                                            <v-btn
                                                icon
                                                small
                                                color="red"
                                                @click="deleteFile(f.id)"
                                            >
                                                <v-icon small
                                                    >mdi-delete</v-icon
                                                >
                                            </v-btn>
                                        </div>

                                        <div
                                            v-if="isImage(f.file_name)"
                                            class="mt-2 text-center"
                                        >
                                            <v-img
                                                :src="`/storage/${f.file_path}`"
                                                max-width="300"
                                                class="rounded-lg shadow-md transition-shadow duration-300 hover:shadow-lg mx-auto"
                                                cover
                                            />
                                        </div>
                                    </v-list-item>
                                </v-list>
                            </div>
                        </v-card-text>
                    </v-card>

                    <div v-if="hasChildren" class="mt-3">
                        <node-item
                            v-for="child in node.children"
                            :key="child.id"
                            :node="child"
                            @add-child="$emit('add-child', $event)"
                        />
                    </div>
                </div>
            </div>
        </v-expand-transition>
    </div>

    <ConfirmDialog
        :visible.sync="showDeleteFileModal"
        title="Konfirmasi Hapus File"
        message="Apakah Anda yakin ingin menghapus file ini?"
        @confirm="confirmDeleteFile"
    />

    <ConfirmDialog
        :visible.sync="showDeleteNodeModal"
        title="Konfirmasi Hapus Node"
        message="Apakah Anda yakin ingin menghapus node ini? Ini akan menghapus semua child dan file-nya!"
        @confirm="confirmDeleteNode"
    />

    <v-dialog v-model="showFileViewer" max-width="800">
        <v-card>
            <v-toolbar dense flat>
                <v-toolbar-title>{{ fileToView.file_name }}</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon @click="closeFileViewer">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <div v-if="isImage(fileToView.file_name)">
                    <v-img
                        :src="`/storage/${fileToView.file_path}`"
                        contain
                    ></v-img>
                </div>
                <div v-else-if="isPdf(fileToView.file_name)">
                    <iframe
                        :src="`/storage/${fileToView.file_path}`"
                        width="100%"
                        height="600px"
                    ></iframe>
                </div>
                <div v-else>
                    <p>Tidak ada preview tersedia untuk tipe file ini.</p>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>

    <v-dialog v-model="showEditNodeModal" max-width="500">
        <v-card>
            <v-card-title>Edit Node Title</v-card-title>
            <v-card-text>
                <v-text-field
                    v-model="editForm.title"
                    label="Node Title"
                    outlined
                    dense
                ></v-text-field>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" text @click="closeEditModal">Batal</v-btn>
                <v-btn
                    color="primary"
                    :loading="editForm.processing"
                    @click="updateNode"
                >
                    Simpan
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
defineOptions({ name: "NodeItem" });

import { ref, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import ConfirmDialog from "@/Components/ConfirmDialog.vue"; // Sesuaikan path ini
import axios from "axios";

const props = defineProps({
    node: { type: Object, required: true },
});

const completionPercentage = computed(() => {
    if (
        !props.node.children_total_count ||
        props.node.children_total_count === 0
    ) {
        // Jika tidak ada anak yang perlu dihitung (misal parent node tanpa leaf node),
        // atau jika itu adalah leaf node.
        return 0;
    }
    const percentage =
        (props.node.children_completed_count /
            props.node.children_total_count) *
        100;
    return percentage.toFixed(0); // Bulatkan ke angka terdekat
});

// Perbarui computed isCompletedAll agar sesuai dengan logika persentase
const isCompletedAll = computed(() => {
    // Jika node adalah leaf node
    if (!props.node.children || props.node.children.length === 0) {
        return props.node.files && props.node.files.length > 0;
    }
    // Jika node adalah parent, cek persentasenya
    else {
        return completionPercentage.value === 100;
    }
});

console.log("Node ID:", props.node.id);
console.log("Files:", props.node.files);

const loading = ref(false);

import { route } from "ziggy-js";

// Vue (Inertia)
const toggleComplete = (node) => {
    router.put(
        route("nodes.toggleComplete", { node: node.id }),
        {}, // Data kosong, karena Anda tidak mengirim payload
        {
            onSuccess: () => {
                // Opsional: Lakukan sesuatu setelah berhasil, misalnya flash message
                console.log("Berhasil toggle complete!");
            },
            onError: (errors) => {
                console.error("Gagal toggle complete:", errors);
            },
        }
    );
};
// ... ref, computed, dan fungsi lainnya

const showFileViewer = ref(false);
const fileToView = ref({});

// Fungsi untuk membuka modal viewer
const openFileViewer = (file) => {
    fileToView.value = file;
    showFileViewer.value = true;
};

// Fungsi untuk menutup modal viewer
const closeFileViewer = () => {
    showFileViewer.value = false;
    fileToView.value = {};
};

// Tambahkan fungsi untuk memeriksa apakah file adalah PDF
function isPdf(fileName) {
    return /\.pdf$/i.test(fileName);
}

// Perbaiki fungsi isImage agar hanya memeriksa ekstensi gambar
function isImage(fileName) {
    return /\.(jpg|jpeg|png|gif|webp)$/i.test(fileName);
}

const getFileIcon = (fileName) => {
    const ext = fileName.split(".").pop().toLowerCase();
    switch (ext) {
        case "pdf":
            return "mdi-file-pdf-box";
        case "doc":
        case "docx":
            return "mdi-file-word-box";
        case "jpg":
        case "jpeg":
        case "png":
            return "mdi-file-image-box";
        default:
            return "mdi-file";
    }
};

const getFileIconColor = (fileName) => {
    const ext = fileName.split(".").pop().toLowerCase();
    switch (ext) {
        case "pdf":
            return "red";
        case "doc":
        case "docx":
            return "blue";
        case "jpg":
        case "jpeg":
        case "png":
            return "green";
        default:
            return "grey";
    }
};

const emit = defineEmits(["add-child"]);

const expanded = ref(false);
const hasChildren = computed(
    () => Array.isArray(props.node.children) && props.node.children.length > 0
);

function toggle() {
    expanded.value = !expanded.value;
}

// form upload
const fileForm = useForm({
    files: [],
});

const uploadFiles = () => {
    if (!fileForm.files || fileForm.files.length === 0) return;

    fileForm.post(route("files.store", props.node.id), {
        forceFormData: true,
        onSuccess: () => {
            fileForm.reset();
            router.reload({ only: ["nodes"] }); // refresh node agar file terbaru muncul
        },
    });
};

// State untuk Modal Hapus File
const showDeleteFileModal = ref(false);
const fileIdToDelete = ref(null);

// State untuk Modal Hapus Node
const showDeleteNodeModal = ref(false);
const nodeIdToDelete = ref(null);

// ... fungsi toggle, isImage, isPdf, uploadFiles, dan lainnya ...

// Fungsi untuk membuka modal penghapusan file
const openDeleteFileModal = (fileId) => {
    fileIdToDelete.value = fileId;
    showDeleteFileModal.value = true;
};

// Fungsi untuk membuka modal penghapusan node
const openDeleteNodeModal = (nodeId) => {
    nodeIdToDelete.value = nodeId;
    showDeleteNodeModal.value = true;
};

// Fungsi untuk mengonfirmasi penghapusan file
const confirmDeleteFile = () => {
    if (fileIdToDelete.value) {
        router.delete(route("files.destroy", fileIdToDelete.value), {
            onSuccess: () => {
                router.reload({ only: ["nodes"] });
            },
            onFinish: () => {
                fileIdToDelete.value = null;
            },
        });
    }
};

// Fungsi untuk mengonfirmasi penghapusan node
const confirmDeleteNode = () => {
    if (nodeIdToDelete.value) {
        router.delete(route("nodes.destroy", nodeIdToDelete.value), {
            onSuccess: () => {
                router.reload({ only: ["nodes"] });
            },
            onError: (errors) => {
                console.error("Gagal menghapus node:", errors);
                alert("Gagal menghapus node. Lihat konsol untuk detailnya.");
            },
            onFinish: () => {
                nodeIdToDelete.value = null;
            },
        });
    }
};

// Ubah event handler pada tombol hapus
const deleteFile = (fileId) => openDeleteFileModal(fileId);
const deleteNode = (nodeId) => openDeleteNodeModal(nodeId);

// --- NEW CODE FOR EDITING ---
const showEditNodeModal = ref(false);
const editForm = useForm({
    title: "",
});
const nodeToEditId = ref(null);

const openEditModal = (node) => {
    nodeToEditId.value = node.id;
    editForm.title = node.title;
    showEditNodeModal.value = true;
};

const closeEditModal = () => {
    showEditNodeModal.value = false;
    editForm.reset();
};

const updateNode = () => {
    if (!nodeToEditId.value || !editForm.title) return;

    editForm.put(route("nodes.update", nodeToEditId.value), {
        onSuccess: () => {
            closeEditModal();
            router.reload({ only: ["nodes"] });
        },
        onError: (errors) => {
            console.error("Gagal memperbarui node:", errors);
        },
    });
};
</script>

<style scoped>
.node-item {
    border-bottom: 1px dashed rgba(0, 0, 0, 0.03);
    position: relative; /* Penting untuk penempatan garis */
}

.node-header {
    cursor: pointer;
}

.node-children-wrapper {
    position: relative;
    padding-left: 20px; /* Indentasi untuk garis */
}

.node-children-wrapper::before {
    content: "";
    position: absolute;
    left: 10px; /* Posisi vertikal garis */
    top: 0;
    bottom: 0;
    width: 1px;
    background-color: #bdbdbd; /* Warna garis abu-abu */
}

.node-item .node-item {
    /* Aturan untuk child node agar garis terhubung */
    border-left: 1px solid transparent;
    position: relative;
}

.node-item .node-item::before {
    content: "";
    position: absolute;
    left: -10px; /* Garis horizontal pendek */
    top: 15px; /* Sesuaikan agar tepat di tengah */
    width: 10px;
    height: 1px;
    background-color: #bdbdbd;
}
</style>
