<template>
    <div class="node-item">
        <!-- <div class="node-item" :style="{ zoom: 0.9 }"> -->

        <!-- Node Header -->
        <v-card
            class="node-header-card mb-2"
            :class="{ 'node-expanded': expanded }"
            elevation="1"
            @click.stop="toggle"
        >
            <v-card-text class="pa-3">
                <v-row align="center" no-gutters>
                    <!-- Node Icon & Title -->
                    <v-col>
                        <div class="d-flex align-center">
                            <div class="node-icon-container mr-3">
                                <v-icon
                                    :color="
                                        hasChildren ? 'purple' : 'blue-grey'
                                    "
                                    size="20"
                                >
                                    {{
                                        hasChildren
                                            ? "mdi-folder"
                                            : "mdi-file-outline"
                                    }}
                                </v-icon>
                            </div>

                            <div class="node-content">
                                <div class="node-title">
                                    {{ node.title }}
                                </div>

                                <div class="node-progress">
                                    <v-chip
                                        size="x-small"
                                        :color="getProgressColor()"
                                        variant="tonal"
                                        class="mr-2"
                                    >
                                        {{ getProgressText() }}
                                    </v-chip>

                                    <v-chip
                                        size="x-small"
                                        :color="
                                            isCompletedAll ? 'green' : 'orange'
                                        "
                                        variant="flat"
                                    >
                                        <v-icon size="12" class="mr-1">
                                            {{
                                                isCompletedAll
                                                    ? "mdi-check-circle"
                                                    : "mdi-clock-outline"
                                            }}
                                        </v-icon>
                                        {{
                                            isCompletedAll
                                                ? "Selesai"
                                                : "Pending"
                                        }}
                                    </v-chip>
                                </div>
                            </div>
                        </div>
                    </v-col>

                    <!-- Expand/Collapse Indicator -->
                    <v-col cols="auto">
                        <v-btn icon size="small" variant="text" color="purple">
                            <v-icon size="20">
                                {{
                                    expanded
                                        ? "mdi-chevron-up"
                                        : "mdi-chevron-down"
                                }}
                            </v-icon>
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>

        <!-- Expanded Content -->
        <v-expand-transition>
            <div v-if="expanded" class="node-expanded-content">
                <!-- Leaf Node Content (File Upload) -->
                <v-card
                    v-if="!hasChildren"
                    class="leaf-node-card"
                    elevation="2"
                >
                    <v-card-title
                        class="d-flex align-center pa-4 bg-purple-lighten-5"
                    >
                        <v-icon color="purple" class="mr-2">mdi-upload</v-icon>
                        <span class="text-h6">Manajemen File</span>
                        <v-spacer></v-spacer>
                        <v-chip
                            :color="node.is_completed ? 'green' : 'grey'"
                            size="small"
                            variant="elevated"
                        >
                            <v-icon size="small" class="mr-1">
                                {{
                                    node.is_completed
                                        ? "mdi-check"
                                        : "mdi-minus"
                                }}
                            </v-icon>
                            {{
                                node.is_completed ? "Selesai" : "Belum Selesai"
                            }}
                        </v-chip>
                    </v-card-title>

                    <v-divider></v-divider>

                    <v-card-text class="pa-4">
                        <!-- Action Buttons -->
                        <div class="action-buttons mb-4">
                            <v-btn
                                color="purple"
                                variant="elevated"
                                size="large"
                                @click="openUploadChoiceModal"
                                class="mr-3"
                            >
                                <v-icon class="mr-2">mdi-upload</v-icon>
                                Upload File
                            </v-btn>

                            <v-btn
                                :color="node.is_completed ? 'orange' : 'green'"
                                variant="elevated"
                                size="large"
                                @click.stop="toggleComplete(node)"
                            >
                                <v-icon class="mr-2">
                                    {{
                                        node.is_completed
                                            ? "mdi-undo"
                                            : "mdi-check"
                                    }}
                                </v-icon>
                                {{
                                    node.is_completed
                                        ? "Tandai Belum Selesai"
                                        : "Tandai Selesai"
                                }}
                            </v-btn>
                        </div>

                        <!-- Files Table -->
                        <div
                            v-if="node.files && node.files.length"
                            class="files-section"
                        >
                            <v-card variant="outlined" class="files-table-card">
                                <v-card-title class="d-flex align-center pa-3">
                                    <v-icon color="blue" class="mr-2"
                                        >mdi-file-multiple</v-icon
                                    >
                                    <span class="text-subtitle-1"
                                        >File yang Diunggah</span
                                    >
                                    <v-spacer></v-spacer>
                                    <v-chip
                                        size="small"
                                        color="blue"
                                        variant="tonal"
                                    >
                                        {{ node.files.length }} file{{
                                            node.files.length > 1 ? "s" : ""
                                        }}
                                    </v-chip>
                                </v-card-title>

                                <v-divider></v-divider>

                                <v-data-table
                                    :headers="fileHeaders"
                                    :items="node.files"
                                    item-value="id"
                                    density="compact"
                                    hide-default-footer
                                    :items-per-page="-1"
                                    class="files-table"
                                    @click:row="
                                        (_, { item }) => openFileViewer(item)
                                    "
                                >
                                    <template #item.thumbnail="{ item: f }">
                                        <div class="thumbnail-container">
                                            <v-img
                                                v-if="isImage(f.file_name)"
                                                :src="`/storage/${f.file_path}`"
                                                width="40"
                                                height="40"
                                                cover
                                                class="rounded-lg"
                                            />
                                            <div
                                                v-else
                                                class="file-icon-container"
                                            >
                                                <v-icon
                                                    :color="
                                                        getFileIconColor(
                                                            f.file_name
                                                        )
                                                    "
                                                    size="24"
                                                >
                                                    {{
                                                        getFileIcon(f.file_name)
                                                    }}
                                                </v-icon>
                                            </div>
                                        </div>
                                    </template>

                                    <template #item.file_name="{ item: f }">
                                        <div class="file-name-cell">
                                            <span
                                                class="file-name-link"
                                                @click.stop="openFileViewer(f)"
                                            >
                                                {{ f.file_name }}
                                            </span>
                                        </div>
                                    </template>

                                    <template #item.actions="{ item: f }">
                                        <v-btn
                                            icon
                                            size="small"
                                            color="red"
                                            variant="text"
                                            @click.stop="deleteFile(f.id)"
                                        >
                                            <v-icon size="small"
                                                >mdi-delete</v-icon
                                            >
                                        </v-btn>
                                    </template>
                                </v-data-table>
                            </v-card>
                        </div>

                        <!-- Empty State for Files -->
                        <div v-else class="empty-files-state">
                            <v-alert
                                type="info"
                                variant="tonal"
                                color="purple"
                                density="compact"
                            >
                                <template v-slot:prepend>
                                    <v-icon>mdi-information</v-icon>
                                </template>
                                Belum ada file yang diunggah untuk item ini
                            </v-alert>
                        </div>
                    </v-card-text>
                </v-card>

                <!-- Child Nodes -->
                <div v-if="hasChildren" class="child-nodes-container">
                    <node-item
                        v-for="child in node.children"
                        :key="child.id"
                        :node="child"
                        @add-child="$emit('add-child', $event)"
                        @update-completion="$emit('update-completion', $event)"
                        class="child-node"
                    />
                </div>
            </div>
        </v-expand-transition>

        <!-- Upload Progress Modal -->
        <v-dialog v-model="showUploadProgressModal" persistent max-width="500">
            <v-card class="progress-modal">
                <v-card-title
                    class="d-flex align-center pa-4 bg-purple-lighten-5"
                >
                    <v-icon color="purple" class="mr-2">mdi-upload</v-icon>
                    <span>Mengunggah File...</span>
                </v-card-title>

                <v-divider></v-divider>

                <v-card-text class="pa-6 text-center">
                    <div class="text-h4 font-weight-bold text-purple-800 mb-4">
                        {{ uploadProgress }}%
                    </div>
                    <v-progress-linear
                        :model-value="uploadProgress"
                        color="purple"
                        height="12"
                        rounded
                        class="mb-4"
                    ></v-progress-linear>
                    <p class="text-subtitle-2 text-purple-600">
                        Mohon tunggu, file sedang diunggah...
                    </p>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions class="pa-4 justify-center">
                    <v-btn
                        color="red"
                        variant="text"
                        @click="cancelUpload"
                        :disabled="uploadProgress === 100"
                    >
                        <v-icon class="mr-1">mdi-close</v-icon>
                        Batalkan
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Upload Choice Modal -->
        <v-dialog v-model="showUploadChoiceModal" max-width="700" persistent>
            <v-card class="upload-choice-modal">
                <v-card-title
                    class="d-flex align-center pa-4 bg-purple-lighten-5"
                >
                    <v-icon color="purple" class="mr-2">mdi-file-plus</v-icon>
                    <span class="text-h6">Pilih Sumber File</span>
                </v-card-title>

                <v-divider></v-divider>

                <v-card-text class="pa-0">
                    <v-tabs
                        v-model="uploadTab"
                        color="purple"
                        class="upload-tabs"
                    >
                        <v-tab value="new">
                            <v-icon class="mr-2">mdi-upload</v-icon>
                            Upload Baru
                        </v-tab>
                        <v-tab value="existing">
                            <v-icon class="mr-2">mdi-database</v-icon>
                            Pilih dari Database
                        </v-tab>
                    </v-tabs>

                    <v-window v-model="uploadTab" class="upload-window">
                        <!-- Tab Upload Baru -->
                        <!-- Tab Upload Baru -->
                        <v-window-item value="new" class="pa-6">
                            <v-form @submit.prevent="uploadFiles">
                                <!-- Drop Zone -->
                                <div
                                    class="drop-zone"
                                    :class="{ 'drop-zone-active': isDragging }"
                                    @dragenter="handleDragEnter"
                                    @dragleave="handleDragLeave"
                                    @dragover="handleDragOver"
                                    @drop="handleDrop"
                                    @click="
                                        $refs.fileInput.$el
                                            .querySelector('input')
                                            .click()
                                    "
                                >
                                    <div class="drop-zone-content">
                                        <v-icon
                                            :color="
                                                isDragging
                                                    ? 'purple'
                                                    : 'grey-lighten-1'
                                            "
                                            size="64"
                                            class="mb-4"
                                        >
                                            {{
                                                isDragging
                                                    ? "mdi-cloud-upload"
                                                    : "mdi-cloud-upload-outline"
                                            }}
                                        </v-icon>

                                        <h3
                                            class="text-h6 mb-2"
                                            :class="
                                                isDragging
                                                    ? 'text-purple'
                                                    : 'text-grey-darken-1'
                                            "
                                        >
                                            {{
                                                isDragging
                                                    ? "Lepas file di sini"
                                                    : "Drag & Drop file di sini"
                                            }}
                                        </h3>

                                        <p class="text-body-2 text-grey mb-4">
                                            atau klik untuk memilih file
                                        </p>

                                        <v-chip
                                            v-if="
                                                fileForm.files &&
                                                fileForm.files.length > 0
                                            "
                                            color="purple"
                                            variant="tonal"
                                            size="small"
                                        >
                                            <v-icon size="small" class="mr-1"
                                                >mdi-file-multiple</v-icon
                                            >
                                            {{ fileForm.files.length }} file
                                            dipilih
                                        </v-chip>
                                    </div>
                                </div>

                                <!-- File Input (Hidden) -->
                                <v-file-input
                                    ref="fileInput"
                                    v-model="fileForm.files"
                                    multiple
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.ppt,.pptx"
                                    variant="outlined"
                                    color="purple"
                                    density="comfortable"
                                    show-size
                                    prepend-icon="mdi-paperclip"
                                    class="file-input mt-4"
                                    style="display: none"
                                ></v-file-input>

                                <!-- File List Preview -->
                                <div
                                    v-if="
                                        fileForm.files &&
                                        fileForm.files.length > 0
                                    "
                                    class="file-list-preview mt-4"
                                >
                                    <v-card variant="outlined" class="pa-3">
                                        <div class="d-flex align-center mb-3">
                                            <v-icon color="purple" class="mr-2"
                                                >mdi-file-multiple</v-icon
                                            >
                                            <span
                                                class="text-subtitle-2 font-weight-bold"
                                            >
                                                File yang akan diupload ({{
                                                    fileForm.files.length
                                                }})
                                            </span>
                                            <v-spacer></v-spacer>
                                            <v-btn
                                                icon
                                                size="x-small"
                                                variant="text"
                                                color="grey"
                                                @click.stop="
                                                    fileForm.files = null
                                                "
                                            >
                                                <v-icon size="small"
                                                    >mdi-close</v-icon
                                                >
                                            </v-btn>
                                        </div>

                                        <v-divider class="mb-3"></v-divider>

                                        <div class="file-preview-list">
                                            <div
                                                v-for="(
                                                    file, index
                                                ) in fileForm.files"
                                                :key="index"
                                                class="file-preview-item"
                                            >
                                                <v-icon
                                                    :color="
                                                        getFileIconColor(
                                                            file.name
                                                        )
                                                    "
                                                    size="20"
                                                    class="mr-2"
                                                >
                                                    {{ getFileIcon(file.name) }}
                                                </v-icon>
                                                <span
                                                    class="file-preview-name"
                                                    >{{ file.name }}</span
                                                >
                                                <span class="file-preview-size">
                                                    {{
                                                        formatFileSize(
                                                            file.size
                                                        )
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    </v-card>
                                </div>

                                <!-- Info Alert -->
                                <v-alert
                                    type="info"
                                    variant="tonal"
                                    color="blue"
                                    density="compact"
                                    class="mt-4 mb-4"
                                >
                                    <template v-slot:prepend>
                                        <v-icon>mdi-information</v-icon>
                                    </template>
                                    <strong>Format yang didukung:</strong> JPG,
                                    PNG, PDF, DOC, DOCX, PPT, PPTX<br />
                                    <strong>Maksimal ukuran per file:</strong>
                                    50MB<br />
                                    <strong>Upload multiple:</strong> Bisa
                                    upload banyak file sekaligus
                                </v-alert>

                                <!-- Upload Button -->
                                <v-btn
                                    type="submit"
                                    color="purple"
                                    variant="elevated"
                                    size="large"
                                    block
                                    :disabled="
                                        !fileForm.files ||
                                        fileForm.files.length === 0
                                    "
                                    :loading="fileForm.processing"
                                    class="upload-btn"
                                >
                                    <v-icon class="mr-2">mdi-upload</v-icon>
                                    Upload
                                    {{
                                        fileForm.files &&
                                        fileForm.files.length > 0
                                            ? `${fileForm.files.length} File`
                                            : "File"
                                    }}
                                </v-btn>
                            </v-form>
                        </v-window-item>

                        <!-- Tab Pilih dari Database -->
                        <v-window-item value="existing" class="pa-6">
                            <v-autocomplete
                                v-model="selectedExistingFile"
                                :items="availableFiles"
                                item-title="file_name"
                                item-value="id"
                                label="Pilih file dari database"
                                variant="outlined"
                                color="purple"
                                density="comfortable"
                                clearable
                                class="existing-file-select mb-4"
                            >
                                <template v-slot:prepend-inner>
                                    <v-icon color="purple">mdi-magnify</v-icon>
                                </template>
                            </v-autocomplete>

                            <v-btn
                                color="green"
                                variant="elevated"
                                size="large"
                                block
                                @click="attachExistingFile"
                                :disabled="!selectedExistingFile"
                                class="attach-btn"
                            >
                                <v-icon class="mr-2">mdi-link</v-icon>
                                Gunakan File Ini
                            </v-btn>
                        </v-window-item>
                    </v-window>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions class="pa-4">
                    <v-spacer />
                    <v-btn
                        color="grey"
                        variant="text"
                        @click="showUploadChoiceModal = false"
                    >
                        <v-icon class="mr-1">mdi-close</v-icon>
                        Tutup
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- File Viewer Modal - BAGIAN YANG DIUBAH -->
        <v-dialog v-model="showFileViewer" max-width="900">
            <v-card class="file-viewer-modal">
                <v-card-title
                    class="d-flex align-center pa-4 bg-blue-lighten-5"
                >
                    <v-icon color="blue" class="mr-2">mdi-file-eye</v-icon>
                    <span class="text-h6">{{ fileToView.file_name }}</span>
                    <v-spacer></v-spacer>
                    <v-btn icon variant="text" @click="closeFileViewer">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>

                <v-divider></v-divider>

                <v-card-text class="pa-0">
                    <!-- Image Viewer -->
                    <div
                        v-if="isImage(fileToView.file_name)"
                        class="image-viewer"
                    >
                        <v-img
                            :src="`/storage/${fileToView.file_path}`"
                            contain
                            max-height="600"
                        ></v-img>
                    </div>

                    <!-- PDF Viewer -->
                    <div
                        v-else-if="isPdf(fileToView.file_name)"
                        class="pdf-viewer"
                    >
                        <iframe
                            :src="`/storage/${fileToView.file_path}`"
                            width="100%"
                            height="600"
                            style="border: none"
                        ></iframe>
                    </div>

                    <!-- DOC/DOCX Viewer - BAGIAN BARU -->
                    <!-- DOCX Viewer dengan Mammoth.js - BAGIAN BARU -->
                    <div
                        v-else-if="isDocx(fileToView.file_name)"
                        class="docx-viewer"
                    >
                        <div id="docx-container" class="pa-6"></div>
                        <div v-if="docxLoading" class="text-center pa-8">
                            <v-progress-circular
                                indeterminate
                                color="purple"
                                size="64"
                            ></v-progress-circular>
                            <p class="mt-4 text-grey">Memuat dokumen...</p>
                        </div>
                        <div
                            v-else-if="docxContent"
                            class="docx-content pa-6"
                            v-html="docxContent"
                        ></div>
                        <div v-else-if="docxError" class="text-center pa-8">
                            <v-icon
                                size="64"
                                color="red-lighten-2"
                                class="mb-4"
                            >
                                mdi-alert-circle
                            </v-icon>
                            <h3 class="text-h6 text-grey mb-2">
                                Gagal memuat dokumen
                            </h3>
                            <p class="text-grey mb-4">{{ docxError }}</p>
                            <v-btn
                                :href="`/storage/${fileToView.file_path}`"
                                target="_blank"
                                color="blue"
                                variant="elevated"
                                download
                            >
                                <v-icon class="mr-2">mdi-download</v-icon>
                                Download File
                            </v-btn>
                        </div>
                    </div>

                    <!-- PPT/PPTX Viewer - BAGIAN BARU -->
                    <div
                        v-else-if="isPpt(fileToView.file_name)"
                        class="ppt-viewer"
                    >
                        <iframe
                            :src="getPptViewerUrl(fileToView.file_path)"
                            width="100%"
                            height="600"
                            style="border: none"
                            @error="handleViewerError"
                        ></iframe>
                        <div class="viewer-fallback" v-if="viewerError">
                            <div class="text-center pa-8">
                                <v-icon
                                    size="64"
                                    color="grey-lighten-2"
                                    class="mb-4"
                                >
                                    mdi-file-powerpoint-box
                                </v-icon>
                                <h3 class="text-h6 text-grey mb-2">
                                    Tidak dapat menampilkan preview
                                </h3>
                                <p class="text-grey mb-4">
                                    Silakan download file untuk melihat isinya
                                </p>
                                <v-btn
                                    :href="`/storage/${fileToView.file_path}`"
                                    target="_blank"
                                    color="orange"
                                    variant="elevated"
                                    download
                                >
                                    <v-icon class="mr-2">mdi-download</v-icon>
                                    Download File
                                </v-btn>
                            </div>
                        </div>
                    </div>

                    <!-- Unsupported Preview -->
                    <div v-else class="unsupported-preview">
                        <div class="text-center pa-8">
                            <v-icon
                                size="64"
                                color="grey-lighten-2"
                                class="mb-4"
                            >
                                mdi-file-document-outline
                            </v-icon>
                            <h3 class="text-h6 text-grey mb-2">
                                Preview tidak tersedia
                            </h3>
                            <p class="text-grey mb-4">
                                File ini tidak dapat ditampilkan di browser
                            </p>
                            <v-btn
                                :href="`/storage/${fileToView.file_path}`"
                                target="_blank"
                                color="blue"
                                variant="elevated"
                                download
                            >
                                <v-icon class="mr-2">mdi-download</v-icon>
                                Download & Buka File
                            </v-btn>
                        </div>
                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>

        <!-- Confirm Dialogs -->
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
    </div>
</template>

<script setup>
defineOptions({ name: "NodeItem" });
import { renderAsync } from "docx-preview";
import { ref, computed, nextTick } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import ConfirmDialog from "@/Components/ConfirmDialog.vue";
import Swal from "sweetalert2";
import { route } from "ziggy-js";

const props = defineProps({
    node: { type: Object, required: true },
});

const emit = defineEmits(["add-child", "update-completion"]);

// File table headers
const fileHeaders = [
    { title: "Preview", key: "thumbnail", sortable: false },
    { title: "Nama File", key: "file_name" },
    { title: "Aksi", key: "actions", sortable: false },
];

// Computed properties
const expanded = ref(false);
const hasChildren = computed(
    () => Array.isArray(props.node.children) && props.node.children.length > 0
);

const isLeaf = computed(() => {
    return !props.node.children || props.node.children.length === 0;
});

const isCompletedAll = computed(() => {
    if (!props.node.children || props.node.children.length === 0) {
        return props.node.files && props.node.files.length > 0;
    } else {
        return completionPercentage.value === 100;
    }
});

// Progress calculation
function calculateCompletion(node) {
    if (!node.children || node.children.length === 0) {
        return {
            done: node.is_completed ? 1 : 0,
            total: 1,
        };
    }

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
    if (isLeaf.value) return null;
    const { done, total } = calculateCompletion(props.node);
    return total > 0 ? Math.round((done / total) * 100) : 0;
});

// Progress display methods
const getProgressText = () => {
    if (isLeaf.value) {
        return props.node.files && props.node.files.length > 0 ? "100%" : "0%";
    } else {
        return `${completionPercentage.value}%`;
    }
};

const getProgressColor = () => {
    const progress = isLeaf.value
        ? props.node.files && props.node.files.length > 0
            ? 100
            : 0
        : completionPercentage.value;

    if (progress === 100) return "green";
    if (progress >= 50) return "orange";
    return "red";
};

// Node actions
const toggle = () => {
    expanded.value = !expanded.value;
};

const toggleComplete = (node) => {
    router.put(
        route("nodes.toggleComplete", { node: node.id }),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            only: ["nodes"],
            onSuccess: () => {
                node.is_completed = !node.is_completed;
                emit("update-completion", {
                    id: node.id,
                    isCompleted: node.is_completed,
                    isLeaf: !hasChildren.value,
                });
            },
        }
    );
};

// File handling
const fileForm = useForm({
    files: null,
});

const showUploadProgressModal = ref(false);
const uploadProgress = ref(0);
const showUploadChoiceModal = ref(false);
const uploadTab = ref("new");
const selectedExistingFile = ref(null);
const availableFiles = ref([]);

const openUploadChoiceModal = async () => {
    showUploadChoiceModal.value = true;
    uploadTab.value = "new";

    try {
        const res = await axios.get(route("files.available"));
        availableFiles.value = res.data;
    } catch (err) {
        console.error("Gagal load available files:", err);
    }
};

// const uploadFiles = () => {
//     if (!fileForm.files || fileForm.files.length === 0) return;

//     const maxSize = 50 * 1024 * 1024; // 50MB
//     for (let i = 0; i < fileForm.files.length; i++) {
//         const file = fileForm.files[i];
//         if (file.size > maxSize) {
//             Swal.fire({
//                 icon: "error",
//                 title: "File Terlalu Besar",
//                 text: `File ${file.name} melebihi ukuran maksimal 50MB.`,
//             });
//             return;
//         }
//     }

//     showUploadChoiceModal.value = false;
//     showUploadProgressModal.value = true;
//     uploadProgress.value = 0;

//     fileForm.post(route("files.store", props.node.id), {
//         forceFormData: true,
//         onProgress: (progressEvent) => {
//             if (progressEvent.lengthComputable) {
//                 uploadProgress.value = Math.round(
//                     (progressEvent.loaded * 100) / progressEvent.total
//                 );
//             }
//         },
//         onSuccess: (response) => {
//             if (response?.props?.node) {
//                 props.node.files = response.props.node.files;
//             }
//             fileForm.reset();
//             Swal.fire({
//                 icon: "success",
//                 title: "Berhasil",
//                 text: "File berhasil diunggah.",
//             });
//         },
//         onError: (errors) => {
//             Swal.fire({
//                 icon: "error",
//                 title: "Gagal Upload",
//                 text:
//                     errors?.files?.[0] ||
//                     "Terjadi kesalahan saat mengupload file.",
//             });
//         },
//         onFinish: () => {
//             showUploadProgressModal.value = false;
//         },
//     });
// };

// Format file size
const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";

    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + " " + sizes[i];
};

const attachExistingFile = () => {
    if (!selectedExistingFile.value) return;

    router.post(
        route("files.attach", props.node.id),
        { file_id: selectedExistingFile.value },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                showUploadChoiceModal.value = false;
                selectedExistingFile.value = null;
                router.reload({ only: ["nodes"], preserveScroll: true });
            },
        }
    );
};

const cancelUpload = () => {
    try {
        fileForm.cancel();
    } catch (e) {
        console.warn("Cancel tidak tersedia di useForm, hanya reset UI.");
    }
    showUploadProgressModal.value = false;
};

// File viewer
const showFileViewer = ref(false);
const fileToView = ref({});

const closeFileViewer = () => {
    showFileViewer.value = false;
    fileToView.value = {};
};

// ========================================
// 1. TAMBAHKAN STATE BARU (di bagian script setup)
// ========================================
const isDragging = ref(false);
const dragCounter = ref(0);

// ========================================
// 2. TAMBAHKAN METHODS UNTUK DRAG AND DROP
// ========================================

// Handle drag enter
const handleDragEnter = (e) => {
    e.preventDefault();
    e.stopPropagation();
    dragCounter.value++;
    if (dragCounter.value === 1) {
        isDragging.value = true;
    }
};

// Handle drag leave
const handleDragLeave = (e) => {
    e.preventDefault();
    e.stopPropagation();
    dragCounter.value--;
    if (dragCounter.value === 0) {
        isDragging.value = false;
    }
};

// Handle drag over
const handleDragOver = (e) => {
    e.preventDefault();
    e.stopPropagation();
};

// Handle drop
const handleDrop = (e) => {
    e.preventDefault();
    e.stopPropagation();

    isDragging.value = false;
    dragCounter.value = 0;

    const files = Array.from(e.dataTransfer.files);

    if (files.length === 0) return;

    // Validasi file
    const validExtensions = [
        "jpg",
        "jpeg",
        "png",
        "pdf",
        "doc",
        "docx",
        "ppt",
        "pptx",
    ];
    const maxSize = 50 * 1024 * 1024; // 50MB

    const invalidFiles = [];
    const validFiles = [];

    files.forEach((file) => {
        const ext = file.name.split(".").pop().toLowerCase();

        if (!validExtensions.includes(ext)) {
            invalidFiles.push({
                name: file.name,
                reason: "Format tidak didukung",
            });
        } else if (file.size > maxSize) {
            invalidFiles.push({
                name: file.name,
                reason: "Ukuran melebihi 50MB",
            });
        } else {
            validFiles.push(file);
        }
    });

    // Tampilkan peringatan jika ada file invalid
    if (invalidFiles.length > 0) {
        const errorMessage = invalidFiles
            .map((f) => `${f.name}: ${f.reason}`)
            .join("\n");
        Swal.fire({
            icon: "warning",
            title: "Beberapa File Tidak Valid",
            html: `<div style="text-align: left; max-height: 200px; overflow-y: auto;">
                ${invalidFiles
                    .map(
                        (f) =>
                            `<p>• ${f.name}: <strong>${f.reason}</strong></p>`
                    )
                    .join("")}
            </div>`,
            confirmButtonText:
                validFiles.length > 0 ? "Upload File Valid" : "OK",
        });
    }

    // Upload file yang valid
    if (validFiles.length > 0) {
        fileForm.files = validFiles;
        uploadFiles();
    }
};

// Reset drag state when leaving window
const resetDragState = () => {
    isDragging.value = false;
    dragCounter.value = 0;
};

// ========================================
// 3. MODIFIKASI METHOD uploadFiles() YANG SUDAH ADA
// ========================================
const uploadFiles = () => {
    if (!fileForm.files || fileForm.files.length === 0) return;

    const maxSize = 50 * 1024 * 1024; // 50MB
    for (let i = 0; i < fileForm.files.length; i++) {
        const file = fileForm.files[i];
        if (file.size > maxSize) {
            Swal.fire({
                icon: "error",
                title: "File Terlalu Besar",
                text: `File ${file.name} melebihi ukuran maksimal 50MB.`,
            });
            return;
        }
    }

    showUploadChoiceModal.value = false;
    showUploadProgressModal.value = true;
    uploadProgress.value = 0;

    fileForm.post(route("files.store", props.node.id), {
        forceFormData: true,
        onProgress: (progressEvent) => {
            if (progressEvent.lengthComputable) {
                uploadProgress.value = Math.round(
                    (progressEvent.loaded * 100) / progressEvent.total
                );
            }
        },
        preserveScroll: true,
        preserveState: true,
        onSuccess: (response) => {
            if (response?.props?.node) {
                props.node.files = response.props.node.files;
            }
            fileForm.reset();

            // Pesan sukses yang lebih informatif
            const fileCount = Array.isArray(fileForm.files)
                ? fileForm.files.length
                : 1;
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: `${fileCount} file berhasil diunggah.`,
            });
        },
        onError: (errors) => {
            Swal.fire({
                icon: "error",
                title: "Gagal Upload",
                text:
                    errors?.files?.[0] ||
                    "Terjadi kesalahan saat mengupload file.",
            });
        },
        onFinish: () => {
            showUploadProgressModal.value = false;
            fileForm.files = null; // Reset files
        },
    });
};

// DOCX Viewer States - TAMBAHKAN INI
const docxContent = ref("");
const docxLoading = ref(false);
const docxError = ref("");

// File utilities
const isImage = (fileName) => /\.(jpg|jpeg|png|gif|webp)$/i.test(fileName);
const isPdf = (fileName) => /\.pdf$/i.test(fileName);
const isPpt = (fileName) => /\.(ppt|pptx)$/i.test(fileName);
const isDocx = (fileName) => /\.docx$/i.test(fileName); // TAMBAHKAN
const isDoc = (fileName) => /\.doc$/i.test(fileName);

// Load DOCX Content - TAMBAHKAN METHOD INI

const loadDocxContent = async (filePath) => {
    docxLoading.value = true;
    try {
        const response = await fetch(`/storage/${filePath}`);
        const blob = await response.blob();

        const container = document.getElementById("docx-container");
        await renderAsync(blob, container);
    } catch (error) {
        //docxError.value = error.message;
    } finally {
        docxLoading.value = false;
    }
};

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
        case "ppt":
        case "pptx":
            return "mdi-file-powerpoint-box";
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
        case "ppt":
        case "pptx":
            return "orange";
        default:
            return "grey";
    }
};

// Delete functionality
const showDeleteFileModal = ref(false);
const fileIdToDelete = ref(null);
const showDeleteNodeModal = ref(false);
const nodeIdToDelete = ref(null);

const deleteFile = (fileId) => {
    fileIdToDelete.value = fileId;
    showDeleteFileModal.value = false;
    nextTick(() => {
        showDeleteFileModal.value = true;
    });
};

const confirmDeleteFile = () => {
    if (!fileIdToDelete.value) return;

    showDeleteFileModal.value = false;
    router.delete(route("files.destroy", fileIdToDelete.value), {
        preserveScroll: true,
        preserveState: true,
        only: ["nodes"],
        onSuccess: () => {
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

// Tambahkan state untuk error handling
const viewerError = ref(false);

// Method untuk generate URL viewer - TAMBAHKAN INI
const getDocViewerUrl = (filePath) => {
    // Gunakan preview token untuk generate public URL
    const publicUrl = route(
        "files.public-preview",
        fileToView.value.preview_token
    );

    // Google Docs Viewer
    return `https://docs.google.com/viewer?url=${encodeURIComponent(
        publicUrl
    )}&embedded=true`;

    // ATAU Microsoft Office Viewer (kadang lebih baik untuk DOC/PPT)
    // return `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(publicUrl)}`;
};

const getPptViewerUrl = (filePath) => {
    const fullUrl = `${window.location.origin}/storage/${filePath}`;
    // Microsoft Office Online lebih baik untuk PPT
    return `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(
        fullUrl
    )}`;
};

const handleViewerError = () => {
    viewerError.value = true;
};

// Update method openFileViewer
const openFileViewer = async (file) => {
    fileToView.value = file;
    viewerError.value = false;
    showFileViewer.value = true;

    // Jika DOCX, load konten
    if (isDocx(file.file_name)) {
        await loadDocxContent(file.file_path);
    }
};
</script>

<style scoped>
/* DOCX Viewer Styles - TAMBAHKAN INI */
.docx-viewer {
    background: #f5f5f5;
    min-height: 600px;
    max-height: 600px;
    overflow-y: auto;
}

.docx-content {
    background: white;
    max-width: 800px;
    margin: 0 auto;
    padding: 48px;
    min-height: 600px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Styling untuk konten DOCX */
.docx-content >>> h1 {
    font-size: 2em;
    font-weight: bold;
    margin-top: 24px;
    margin-bottom: 16px;
    color: #1a202c;
}

.docx-content >>> h2 {
    font-size: 1.5em;
    font-weight: bold;
    margin-top: 20px;
    margin-bottom: 12px;
    color: #2d3748;
}

.docx-content >>> h3 {
    font-size: 1.25em;
    font-weight: bold;
    margin-top: 16px;
    margin-bottom: 10px;
    color: #4a5568;
}

.docx-content >>> p {
    margin-bottom: 12px;
    line-height: 1.6;
    color: #4a5568;
}

.docx-content >>> ul,
.docx-content >>> ol {
    margin-left: 24px;
    margin-bottom: 12px;
}

.docx-content >>> li {
    margin-bottom: 6px;
    line-height: 1.6;
}

.docx-content >>> table {
    width: 100%;
    border-collapse: collapse;
    margin: 16px 0;
}

.docx-content >>> table td,
.docx-content >>> table th {
    border: 1px solid #e2e8f0;
    padding: 8px 12px;
}

.docx-content >>> table th {
    background-color: #f7fafc;
    font-weight: bold;
}

.docx-content >>> strong,
.docx-content >>> b {
    font-weight: bold;
}

.docx-content >>> em,
.docx-content >>> i {
    font-style: italic;
}

.docx-content >>> img {
    max-width: 100%;
    height: auto;
    margin: 16px 0;
}

/* Scrollbar untuk docx viewer */
.docx-viewer::-webkit-scrollbar {
    width: 8px;
}

.docx-viewer::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.docx-viewer::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.docx-viewer::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Node Item Container */
.node-item {
    margin-bottom: 16px;
}

/* Node Header Card */
.node-header-card {
    border-radius: 12px !important;
    cursor: pointer;
    transition: all 0.2s ease !important;
    border: 1px solid transparent;
}

.node-header-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(183, 148, 246, 0.15) !important;
    border-color: rgba(183, 148, 246, 0.3);
}

.node-expanded {
    border-color: rgba(183, 148, 246, 0.5) !important;
    background: linear-gradient(
        135deg,
        rgba(183, 148, 246, 0.05),
        rgba(248, 244, 255, 0.8)
    ) !important;
}

/* Node Icon */
.node-icon-container {
    background: rgba(183, 148, 246, 0.1);
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Node Content */
.node-content {
    flex: 1;
}

.node-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 4px;
}

.node-progress {
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Expanded Content */
.node-expanded-content {
    margin-left: 20px;
    padding-left: 20px;
    border-left: 2px solid rgba(183, 148, 246, 0.3);
}

/* Leaf Node Card */
.leaf-node-card {
    border-radius: 16px !important;
    overflow: hidden;
    margin-bottom: 16px;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.action-buttons .v-btn {
    text-transform: none !important;
    font-weight: 500 !important;
    border-radius: 8px !important;
}

/* Files Section */
.files-section {
    margin-top: 16px;
}

.files-table-card {
    border-radius: 12px !important;
    overflow: hidden;
}

.files-table {
    border-radius: 0 !important;
}

.files-table tbody tr:hover {
    background-color: rgba(183, 148, 246, 0.05) !important;
}

/* File Thumbnail */
.thumbnail-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
}

.file-icon-container {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 8px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* File Name */
.file-name-cell {
    max-width: 200px;
}

.file-name-link {
    color: #1976d2;
    cursor: pointer;
    text-decoration: none;
    font-weight: 500;
    word-break: break-word;
    font-size: 0.875rem;
}

.file-name-link:hover {
    text-decoration: underline;
    color: #1565c0;
}

/* Empty Files State */
.empty-files-state {
    margin-top: 16px;
}

/* Child Nodes */
.child-nodes-container {
    margin-top: 16px;
}

.child-node {
    margin-bottom: 12px;
}

/* Modals */
.progress-modal,
.upload-choice-modal,
.file-viewer-modal {
    border-radius: 16px !important;
    overflow: hidden;
}

/* Upload Tabs */
.upload-tabs {
    background: rgba(183, 148, 246, 0.05);
}

.upload-window {
    min-height: 300px;
}

/* Form Elements */
.file-input,
.existing-file-select {
    border-radius: 12px;
}

.upload-btn,
.attach-btn {
    text-transform: none !important;
    font-weight: 600 !important;
    border-radius: 12px !important;
}

/* File Viewer */
.image-viewer,
.pdf-viewer {
    background: #f5f5f5;
}

.unsupported-preview {
    background: linear-gradient(
        135deg,
        rgba(183, 148, 246, 0.05),
        rgba(248, 244, 255, 0.1)
    );
}

/* Responsive Design */
@media (max-width: 768px) {
    .node-expanded-content {
        margin-left: 10px;
        padding-left: 10px;
    }

    .action-buttons {
        flex-direction: column;
    }

    .action-buttons .v-btn {
        width: 100%;
        margin-bottom: 8px;
    }

    .file-name-cell {
        max-width: 150px;
    }

    .files-table .v-data-table__wrapper {
        overflow-x: auto;
    }
}

/* Smooth transitions */
.node-header-card,
.leaf-node-card,
.files-table-card {
    transition: all 0.2s ease;
}

/* Custom scrollbar for modals */
.upload-window::-webkit-scrollbar {
    width: 6px;
}

.upload-window::-webkit-scrollbar-track {
    background: rgba(183, 148, 246, 0.1);
    border-radius: 3px;
}

.upload-window::-webkit-scrollbar-thumb {
    background: rgba(183, 148, 246, 0.5);
    border-radius: 3px;
}

.upload-window::-webkit-scrollbar-thumb:hover {
    background: rgba(183, 148, 246, 0.7);
}

/* Drop Zone Styles */
.drop-zone {
    border: 3px dashed #d1d5db;
    border-radius: 16px;
    padding: 48px 24px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: linear-gradient(
        135deg,
        rgba(183, 148, 246, 0.02),
        rgba(248, 244, 255, 0.5)
    );
    position: relative;
    overflow: hidden;
}

.drop-zone::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(
        circle at center,
        rgba(183, 148, 246, 0.05),
        transparent
    );
    opacity: 0;
    transition: opacity 0.3s ease;
}

.drop-zone:hover {
    border-color: #b794f6;
    background: linear-gradient(
        135deg,
        rgba(183, 148, 246, 0.08),
        rgba(248, 244, 255, 0.8)
    );
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(183, 148, 246, 0.15);
}

.drop-zone:hover::before {
    opacity: 1;
}

.drop-zone-active {
    border-color: #9333ea !important;
    background: linear-gradient(
        135deg,
        rgba(183, 148, 246, 0.15),
        rgba(248, 244, 255, 1)
    ) !important;
    transform: scale(1.02);
    box-shadow: 0 12px 32px rgba(183, 148, 246, 0.25) !important;
}

.drop-zone-active::before {
    opacity: 1;
}

.drop-zone-content {
    position: relative;
    z-index: 1;
}

/* File List Preview */
.file-list-preview {
    max-height: 300px;
    overflow-y: auto;
}

.file-preview-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.file-preview-item {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    background: rgba(183, 148, 246, 0.05);
    border-radius: 8px;
    transition: all 0.2s ease;
}

.file-preview-item:hover {
    background: rgba(183, 148, 246, 0.1);
    transform: translateX(4px);
}

.file-preview-name {
    flex: 1;
    font-size: 0.875rem;
    color: #374151;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    margin-right: 12px;
}

.file-preview-size {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 500;
    background: rgba(183, 148, 246, 0.1);
    padding: 2px 8px;
    border-radius: 4px;
}

/* Custom scrollbar for file list */
.file-list-preview::-webkit-scrollbar {
    width: 6px;
}

.file-list-preview::-webkit-scrollbar-track {
    background: rgba(183, 148, 246, 0.1);
    border-radius: 3px;
}

.file-list-preview::-webkit-scrollbar-thumb {
    background: rgba(183, 148, 246, 0.5);
    border-radius: 3px;
}

.file-list-preview::-webkit-scrollbar-thumb:hover {
    background: rgba(183, 148, 246, 0.7);
}

/* Animation for drag effect */
@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

.drop-zone-active .drop-zone-content {
    animation: pulse 1.5s ease-in-out infinite;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .drop-zone {
        padding: 32px 16px;
    }

    .file-preview-name {
        font-size: 0.8125rem;
    }
}
</style>
