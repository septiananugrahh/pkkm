<template>
    <div class="node-item">
        <v-row align="center" class="py-1 node-header" @click.stop="toggle">
            <v-col cols="auto">
                <v-icon small v-if="hasChildren">mdi-folder</v-icon>
                <v-icon small v-else>mdi-file-outline</v-icon>
            </v-col>
            <v-col>
                <div>
                    <span style="font-size: 1.1rem"> {{ node.title }} </span>
                    <span
                        v-if="!isLeaf && completionPercentage !== null"
                        class="ml-2 text-sm text-gray-500"
                    >
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
                <!-- <v-btn small text @click.stop="openEditModal(node)">
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
                 <v-btn
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
                        <v-card-text>
                            <div
                                class="mb-4 text-green-600 font-semibold"
                                v-if="node.is_completed"
                            >
                                ✅ Input Data: Selesai
                            </div>

                            <h4 class="text-lg font-bold text-gray-800 mb-4">
                                Upload & Status Penilaian
                            </h4>

                            <v-row dense align="center">
                                <v-spacer></v-spacer>

                                <v-col cols="auto">
                                    <v-btn
                                        color="primary"
                                        size="large"
                                        icon
                                        @click="openUploadChoiceModal"
                                    >
                                        <v-icon>mdi-upload</v-icon>
                                    </v-btn>
                                </v-col>

                                <v-col cols="auto">
                                    <v-btn
                                        :color="
                                            node.is_completed
                                                ? 'error'
                                                : 'success'
                                        "
                                        size="large"
                                        icon
                                        @click.stop="toggleComplete(node)"
                                    >
                                        <v-icon>
                                            {{
                                                node.is_completed
                                                    ? "mdi-close-circle"
                                                    : "mdi-check-circle"
                                            }}
                                        </v-icon>
                                    </v-btn>
                                </v-col>
                            </v-row>

                            <!-- Upload Files -->

                            <v-dialog
                                v-model="showUploadProgressModal"
                                persistent
                                max-width="400"
                            >
                                <v-card>
                                    <v-card-title class="text-h6">
                                        Mengunggah File...
                                    </v-card-title>
                                    <v-card-text>
                                        <div class="text-center mb-4">
                                            {{ uploadProgress }}%
                                        </div>
                                        <v-progress-linear
                                            :model-value="uploadProgress"
                                            color="primary"
                                            height="20"
                                            rounded
                                        >
                                        </v-progress-linear>
                                    </v-card-text>
                                    <v-card-actions class="justify-center">
                                        <v-btn
                                            text
                                            color="error"
                                            @click="cancelUpload"
                                            :disabled="uploadProgress === 100"
                                        >
                                            Batalkan
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>

                            <!-- Modal Pilihan Upload -->
                            <v-dialog
                                v-model="showUploadChoiceModal"
                                max-width="600"
                            >
                                <v-card>
                                    <v-card-title
                                        >Pilih Sumber File</v-card-title
                                    >
                                    <v-card-text>
                                        <v-tabs
                                            v-model="uploadTab"
                                            background-color="primary"
                                            dark
                                        >
                                            <v-tab value="new"
                                                >Upload Baru</v-tab
                                            >
                                            <v-tab value="existing"
                                                >Pilih dari Database</v-tab
                                            >
                                        </v-tabs>

                                        <v-window v-model="uploadTab">
                                            <!-- Tab Upload Baru -->
                                            <v-window-item value="new">
                                                <v-form
                                                    @submit.prevent="
                                                        uploadFiles
                                                    "
                                                    class="mt-4"
                                                >
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
                                                            fileForm.files
                                                                .length === 0
                                                        "
                                                        :loading="
                                                            fileForm.processing
                                                        "
                                                        class="mt-3"
                                                        block
                                                    >
                                                        Upload
                                                    </v-btn>
                                                </v-form>
                                            </v-window-item>

                                            <!-- Tab Pilih dari Database -->
                                            <v-window-item value="existing">
                                                <div class="mt-4">
                                                    <v-autocomplete
                                                        v-model="
                                                            selectedExistingFile
                                                        "
                                                        :items="availableFiles"
                                                        item-title="file_name"
                                                        item-value="id"
                                                        label="Pilih file dari database"
                                                        outlined
                                                        dense
                                                        clearable
                                                        hide-details
                                                        class="custom-autocomplete"
                                                    />

                                                    <v-btn
                                                        color="success"
                                                        class="mt-3"
                                                        block
                                                        @click="
                                                            attachExistingFile
                                                        "
                                                        :disabled="
                                                            !selectedExistingFile
                                                        "
                                                    >
                                                        <v-icon left
                                                            >mdi-link</v-icon
                                                        >
                                                        Gunakan File Ini
                                                    </v-btn>
                                                </div>
                                            </v-window-item>
                                        </v-window>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer />
                                        <v-btn
                                            text
                                            color="error"
                                            @click="
                                                showUploadChoiceModal = false
                                            "
                                            >Tutup</v-btn
                                        >
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>

                            <!-- Daftar Files Terupload (Horizontal / Grid) -->
                            <div
                                v-if="node.files && node.files.length"
                                class="mt-3"
                            >
                                <h4
                                    class="text-lg font-bold text-gray-800 ml-3"
                                >
                                    Files Terupload
                                </h4>

                                <div
                                    class="grid p-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4"
                                >
                                    <div
                                        v-for="f in node.files"
                                        :key="f.id"
                                        class="flex flex-col items-center border rounded-lg p-5 shadow-sm hover:shadow-md transition"
                                    >
                                        <!-- Thumbnail atau Icon -->
                                        <div v-if="isImage(f.file_name)">
                                            <v-img
                                                :src="`/storage/${f.file_path}`"
                                                class="rounded-lg mb-2"
                                                style="
                                                    min-width: 120px;
                                                    min-height: 120px;
                                                    max-width: 120px;
                                                    max-height: 120px;
                                                "
                                                cover
                                            />
                                        </div>
                                        <div
                                            v-else
                                            class="flex items-center justify-center w-24 h-24 bg-gray-100 rounded-lg mb-2"
                                        >
                                            <v-icon
                                                large
                                                :color="
                                                    getFileIconColor(
                                                        f.file_name
                                                    )
                                                "
                                            >
                                                {{ getFileIcon(f.file_name) }}
                                            </v-icon>
                                        </div>

                                        <!-- Nama File -->
                                        <a
                                            :href="`/storage/${f.file_path}`"
                                            target="_blank"
                                            @click.prevent="openFileViewer(f)"
                                            class="text-sm text-center text-blue-600 hover:underline break-words"
                                            style="word-break: break-all"
                                        >
                                            >
                                            {{ f.file_name }}
                                        </a>

                                        <!-- Tombol Delete -->
                                        <v-btn
                                            icon
                                            small
                                            color="red"
                                            @click="deleteFile(f.id)"
                                            class="mt-1"
                                        >
                                            <v-icon small>mdi-delete</v-icon>
                                        </v-btn>
                                    </div>
                                </div>
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
        :key="`delete-file-${fileIdToDelete}`"
        :visible.sync="showDeleteFileModal"
        title="Konfirmasi Hapus File"
        message="Apakah Anda yakin ingin menghapus file ini?"
        @confirm="confirmDeleteFile"
    />

    <ConfirmDialog
        :key="`delete-node-${nodeIdToDelete}`"
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

import { ref, computed, nextTick } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import ConfirmDialog from "@/Components/ConfirmDialog.vue"; // Sesuaikan path ini
import Swal from "sweetalert2"; // Pastikan Anda sudah mengimpor SweetAlert

const props = defineProps({
    node: { type: Object, required: true },
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

import { route } from "ziggy-js";

const toggleComplete = (node) => {
    router.put(
        route("nodes.toggleComplete", { node: node.id }),
        {},
        {
            onSuccess: () => {
                // Toggle status selesai
                node.is_completed = !node.is_completed;

                // Emit event ke parent untuk memperbarui status
                emit("update-completion", {
                    id: node.id,
                    isCompleted: node.is_completed,
                    isLeaf: !hasChildren.value,
                });
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

const emit = defineEmits(["add-child", "update-completion"]);

const expanded = ref(false);
const hasChildren = computed(
    () => Array.isArray(props.node.children) && props.node.children.length > 0
);

function toggle() {
    expanded.value = !expanded.value;
}

// form upload
const showUploadProgressModal = ref(false);
const uploadProgress = ref(0);

const fileForm = useForm({
    files: null,
});

// Perbaikan: gunakan fileForm.processing sebagai pengganti fileForm.loading
const uploadFiles = () => {
    if (!fileForm.files || fileForm.files.length === 0) return;

    // Validasi ukuran file sebelum upload
    const maxSize = 20 * 1024 * 1024; // Maksimal 20MB
    for (let i = 0; i < fileForm.files.length; i++) {
        const file = fileForm.files[i];

        if (file.size > maxSize) {
            Swal.fire({
                icon: "error",
                title: "File Terlalu Besar",
                text: `File ${file.name} melebihi ukuran maksimal 20MB.`,
            });
            return;
        }
    }

    // Tampilkan modal progress sebelum memulai upload
    showUploadChoiceModal.value = false;
    showUploadProgressModal.value = true;
    uploadProgress.value = 0;

    fileForm.post(route("files.store", props.node.id), {
        forceFormData: true,
        // Mendapatkan progress upload
        onProgress: (progressEvent) => {
            if (progressEvent.lengthComputable) {
                uploadProgress.value = Math.round(
                    (progressEvent.loaded * 100) / progressEvent.total
                );
            }
        },
        onSuccess: (response) => {
            // Asumsi respons Inertia berisi node yang diupdate
            const updatedNode = response.props.node;
            // Gantikan data node saat ini dengan data yang baru dari server
            // atau tambahkan file baru secara langsung
            props.node.files = updatedNode.files;

            fileForm.reset();
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "File berhasil diunggah.",
            });
            showUploadProgressModal.value = false;
        },
        onError: (errors) => {
            if (errors && errors.files) {
                Swal.fire({
                    icon: "error",
                    title: "Gagal Upload",
                    text:
                        errors.files[0] ||
                        "Terjadi kesalahan saat mengupload file.",
                });
            }
            // Tutup modal progress meskipun gagal
            showUploadProgressModal.value = false;
        },
    });
};

const cancelUpload = () => {
    // Fungsi untuk membatalkan proses upload
    // Karena Inertia.js tidak memiliki metode bawaan untuk membatalkan,
    // kita hanya bisa menyembunyikan modal dan mereset status
    fileForm.cancel();
    showUploadProgressModal.value = false;
};

const isLeaf = computed(() => {
    return !props.node.children || props.node.children.length === 0;
});

// hitung persentase selesai berdasarkan children
function calculateCompletion(node) {
    if (!node.children || node.children.length === 0) {
        // leaf: pakai status selesai (true/false → 0/100)
        return {
            done: node.is_completed ? 1 : 0,
            total: 1,
        };
    }

    // kalau punya anak → akumulasi semua children
    return node.children.reduce(
        (acc, child) => {
            const childResult = calculateCompletion(child);
            acc.done += childResult.done;
            acc.total += childResult.total;
            return acc;
        },
        { done: 0, total: 0 }
    );
}

const completionPercentage = computed(() => {
    if (isLeaf.value) return null; // leaf → tidak ada persentase

    const { done, total } = calculateCompletion(props.node);
    return total > 0 ? Math.round((done / total) * 100) : 0;
});

// State untuk Modal Hapus File
const showDeleteFileModal = ref(false);
const fileIdToDelete = ref(null);

// State untuk Modal Hapus Node
const showDeleteNodeModal = ref(false);
const nodeIdToDelete = ref(null);

// ... fungsi toggle, isImage, isPdf, uploadFiles, dan lainnya ...

// Buka modal hapus file
const openDeleteFileModal = (fileId) => {
    fileIdToDelete.value = fileId;
    showDeleteFileModal.value = false; // reset dulu
    nextTick(() => {
        showDeleteFileModal.value = true; // buka modal
    });
};
// Konfirmasi hapus file
const confirmDeleteFile = () => {
    if (!fileIdToDelete.value) return;

    showDeleteFileModal.value = false; // Tutup modal dulu

    router.delete(route("files.destroy", fileIdToDelete.value), {
        onSuccess: () => {
            // Hapus file dari node.files secara lokal
            const fileIndex = props.node.files.findIndex(
                (f) => f.id === fileIdToDelete.value
            );
            if (fileIndex !== -1) props.node.files.splice(fileIndex, 1);
        },
        onFinish: () => {
            fileIdToDelete.value = null;
        },
    });
};
// Buka modal hapus node
const openDeleteNodeModal = (nodeId) => {
    nodeIdToDelete.value = nodeId;
    showDeleteNodeModal.value = false; // reset dulu
    nextTick(() => {
        showDeleteNodeModal.value = true; // buka modal
    });
};

// Konfirmasi hapus node
const confirmDeleteNode = () => {
    if (!nodeIdToDelete.value) return;

    showDeleteNodeModal.value = false; // Tutup modal dulu

    router.delete(route("nodes.destroy", nodeIdToDelete.value), {
        onSuccess: () => {
            // Hapus node dari props.node.children secara lokal
            if (props.node.children && props.node.children.length) {
                const index = props.node.children.findIndex(
                    (n) => n.id === nodeIdToDelete.value
                );
                if (index !== -1) props.node.children.splice(index, 1);
            }
        },
        onFinish: () => {
            nodeIdToDelete.value = null;
        },
    });
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

// FIles Upload

const showUploadChoiceModal = ref(false);
const uploadTab = ref("new");
const selectedExistingFile = ref(null);

// Asumsikan file dari database sudah dilempar lewat props atau fetch API
// Contoh: props.availableFiles = [{id: 1, file_name: 'abc.pdf', file_path: '...'}]
const availableFiles = ref([]);

const openUploadChoiceModal = async () => {
    showUploadChoiceModal.value = true;
    uploadTab.value = "new"; // default tab

    // Ambil data file dari backend
    try {
        const res = await axios.get(route("files.available"));
        availableFiles.value = res.data;
    } catch (err) {
        console.error("Gagal load available files:", err);
    }
};

// attach file yg sudah ada ke node ini
const attachExistingFile = () => {
    if (!selectedExistingFile.value) return;

    router.post(
        route("files.attach", props.node.id),
        {
            file_id: selectedExistingFile.value,
        },
        {
            onSuccess: () => {
                showUploadChoiceModal.value = false;
                selectedExistingFile.value = null;
                router.reload({ only: ["nodes"] });
            },
        }
    );
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

/* custom v-autocomplete */
.custom-autocomplete {
    border-radius: 12px; /* sudut melengkung */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); /* shadow lembut */
    background: #fff; /* putih bersih */
    transition: all 0.3s ease;
}

.custom-autocomplete:hover {
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
}

.custom-autocomplete :deep(.v-field) {
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    background-color: #fafafa;
    transition: border-color 0.3s ease, background-color 0.3s ease;
}

.custom-autocomplete :deep(.v-field:hover) {
    border-color: #1976d2; /* biru saat hover */
    background-color: #fff;
}

.custom-autocomplete :deep(.v-field__input) {
    font-size: 0.95rem;
    padding: 6px 12px;
}

.custom-autocomplete :deep(.v-label) {
    font-weight: 500;
    color: #666;
}
</style>
