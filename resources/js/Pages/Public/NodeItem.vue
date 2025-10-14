<template>
    <div class="modern-node-item">
        <!-- Node Header Card -->
        <div
            class="node-card bg-white/70 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50 hover:shadow-xl transition-all duration-300 hover:scale-[1.02] cursor-pointer"
            @click.stop="toggle"
        >
            <div class="flex items-center p-6">
                <!-- Icon Section -->
                <div class="flex-shrink-0 mr-4">
                    <div
                        class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300"
                        :class="
                            hasChildren
                                ? 'bg-gradient-to-r from-indigo-500 to-purple-500'
                                : 'bg-gradient-to-r from-cyan-500 to-blue-500'
                        "
                    >
                        <v-icon
                            color="white"
                            size="large"
                            :class="{ 'rotate-90': expanded && hasChildren }"
                            class="transition-transform duration-300"
                        >
                            {{
                                hasChildren
                                    ? expanded
                                        ? "mdi-folder-open"
                                        : "mdi-folder"
                                    : "mdi-file-document"
                            }}
                        </v-icon>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                        {{ node.title }}
                    </h3>
                    <div
                        class="flex items-center space-x-4 text-sm text-gray-600"
                    >
                        <div v-if="hasChildren" class="flex items-center">
                            <v-icon class="mr-1" size="small" color="indigo"
                                >mdi-file-tree</v-icon
                            >
                            <span>{{ node.children.length }} sub-item</span>
                        </div>
                        <div
                            v-if="node.files && node.files.length"
                            class="flex items-center"
                        >
                            <v-icon class="mr-1" size="small" color="green"
                                >mdi-paperclip</v-icon
                            >
                            <span>{{ node.files.length }} file</span>
                        </div>
                        <div
                            v-if="
                                !hasChildren &&
                                (!node.files || !node.files.length)
                            "
                            class="flex items-center"
                        >
                            <v-icon class="mr-1" size="small" color="gray"
                                >mdi-information</v-icon
                            >
                            <span>Tidak ada konten</span>
                        </div>
                    </div>
                </div>

                <!-- Expand Arrow -->
                <div
                    v-if="hasChildren || (node.files && node.files.length)"
                    class="flex-shrink-0 ml-4"
                >
                    <div
                        class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center"
                    >
                        <v-icon
                            size="small"
                            color="gray-600"
                            :class="{ 'rotate-180': expanded }"
                            class="transition-transform duration-300"
                        >
                            mdi-chevron-down
                        </v-icon>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expanded Content -->
        <v-expand-transition>
            <div v-if="expanded" class="mt-6 ml-8">
                <!-- Files Section -->
                <div v-if="node.files && node.files.length" class="mb-6">
                    <div
                        class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden"
                    >
                        <!-- Files Header -->
                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-3"
                                >
                                    <v-icon color="white" size="small"
                                        >mdi-file-multiple</v-icon
                                    >
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900">
                                    File Dokumen
                                </h4>
                                <span
                                    class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full"
                                >
                                    {{ node.files.length }} file
                                </span>
                            </div>
                        </div>

                        <!-- Files Grid -->
                        <div class="p-6">
                            <div
                                class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-4"
                            >
                                <div
                                    v-for="file in node.files"
                                    :key="file.id"
                                    @click="openFileViewer(file)"
                                    class="group relative bg-gray-50 rounded-lg p-4 hover:bg-gray-100 cursor-pointer transition-all duration-300 hover:shadow-md border border-gray-200 hover:border-indigo-300"
                                >
                                    <div class="flex items-center">
                                        <!-- File Type Icon -->
                                        <div
                                            class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 flex-shrink-0"
                                            :class="
                                                getFileTypeClass(file.file_name)
                                            "
                                        >
                                            <v-icon color="white" size="small">
                                                {{
                                                    getFileIcon(file.file_name)
                                                }}
                                            </v-icon>
                                        </div>

                                        <!-- File Info -->
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-sm font-medium text-gray-900 truncate group-hover:text-indigo-600 transition-colors duration-200"
                                            >
                                                {{ file.file_name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{
                                                    getFileType(file.file_name)
                                                }}
                                            </p>
                                        </div>

                                        <!-- Action Icon -->
                                        <div class="flex-shrink-0 ml-2">
                                            <v-icon
                                                size="small"
                                                color="gray-400"
                                                class="group-hover:text-indigo-600 transition-colors duration-200"
                                            >
                                                mdi-eye
                                            </v-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recursive Children -->
                <div v-if="hasChildren" class="space-y-4">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center mr-3"
                        >
                            <v-icon color="white" size="small"
                                >mdi-file-tree</v-icon
                            >
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">
                            Sub Kategori
                        </h4>
                        <span
                            class="ml-2 px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full"
                        >
                            {{ node.children.length }} item
                        </span>
                    </div>

                    <div class="space-y-4 border-l-2 border-indigo-200 pl-6">
                        <PublicNodeItem
                            v-for="child in node.children"
                            :key="child.id"
                            :node="child"
                        />
                    </div>
                </div>
            </div>
        </v-expand-transition>

        <!-- Modern File Viewer Modal -->
        <v-dialog
            v-model="showFileViewer"
            max-width="1200"
            class="modern-dialog"
        >
            <v-card class="rounded-2xl overflow-hidden">
                <!-- Modal Header -->
                <div
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-white">
                            <div
                                class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-3"
                            >
                                <v-icon color="white" size="small">
                                    {{ getFileIcon(fileToView.file_name) }}
                                </v-icon>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">
                                    {{ fileToView.file_name }}
                                </h3>
                                <p class="text-indigo-200 text-sm">
                                    {{ getFileType(fileToView.file_name) }}
                                </p>
                            </div>
                        </div>
                        <button
                            @click="closeFileViewer"
                            class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors duration-200"
                        >
                            <v-icon color="white">mdi-close</v-icon>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6 bg-gray-50 min-h-[400px]">
                    <!-- Image Preview -->
                    <div
                        v-if="isImage(fileToView.file_name)"
                        class="text-center"
                    >
                        <v-img
                            :src="`/storage/${fileToView.file_path}`"
                            contain
                            max-height="600"
                        ></v-img>
                    </div>

                    <!-- PDF Preview -->
                    <div
                        v-else-if="isPdf(fileToView.file_name)"
                        class="bg-white rounded-xl overflow-hidden shadow-sm"
                    >
                        <iframe
                            :src="`/storage/${fileToView.file_path}`"
                            width="100%"
                            height="600px"
                            style="border: none"
                            class="rounded-xl"
                        ></iframe>
                    </div>

                    <!-- No Preview Available -->
                    <div v-else class="text-center py-12">
                        <div
                            class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mb-6 mx-auto"
                        >
                            <v-icon color="gray-500" size="40"
                                >mdi-file-question</v-icon
                            >
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Preview Tidak Tersedia
                        </h3>
                        <p class="text-gray-600 mb-6">
                            File ini tidak dapat ditampilkan dalam browser.
                            Silakan download untuk melihat isinya.
                        </p>
                        <a
                            :href="`/storage/${fileToView.file_path}`"
                            target="_blank"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-xl hover:shadow-lg transition-all duration-300 hover:scale-105"
                        >
                            <v-icon class="mr-2" color="white"
                                >mdi-download</v-icon
                            >
                            Download File
                        </a>
                    </div>
                </div>
            </v-card>
        </v-dialog>
    </div>
</template>

<script setup>
defineOptions({ name: "PublicNodeItem" });

import { ref, computed } from "vue";

const props = defineProps({
    node: { type: Object, required: true },
});

const expanded = ref(false);
const hasChildren = computed(
    () => Array.isArray(props.node.children) && props.node.children.length > 0
);

function toggle() {
    expanded.value = !expanded.value;
}

function isImage(fileName) {
    return /\.(jpg|jpeg|png|gif|webp|svg|bmp)$/i.test(fileName);
}

function isPdf(fileName) {
    return /\.pdf$/i.test(fileName);
}

function getFileIcon(fileName) {
    if (isImage(fileName)) return "mdi-image";
    if (isPdf(fileName)) return "mdi-file-pdf-box";
    if (/\.(doc|docx)$/i.test(fileName)) return "mdi-file-word";
    if (/\.(xls|xlsx)$/i.test(fileName)) return "mdi-file-excel";
    if (/\.(ppt|pptx)$/i.test(fileName)) return "mdi-file-powerpoint";
    if (/\.(txt)$/i.test(fileName)) return "mdi-file-document";
    return "mdi-file";
}

function getFileType(fileName) {
    if (isImage(fileName)) return "Gambar";
    if (isPdf(fileName)) return "PDF Document";
    if (/\.(doc|docx)$/i.test(fileName)) return "Word Document";
    if (/\.(xls|xlsx)$/i.test(fileName)) return "Excel Spreadsheet";
    if (/\.(ppt|pptx)$/i.test(fileName)) return "PowerPoint Presentation";
    if (/\.(txt)$/i.test(fileName)) return "Text Document";
    return "File";
}

function getFileTypeClass(fileName) {
    if (isImage(fileName)) return "bg-green-500";
    if (isPdf(fileName)) return "bg-red-500";
    if (/\.(doc|docx)$/i.test(fileName)) return "bg-blue-500";
    if (/\.(xls|xlsx)$/i.test(fileName)) return "bg-emerald-500";
    if (/\.(ppt|pptx)$/i.test(fileName)) return "bg-orange-500";
    if (/\.(txt)$/i.test(fileName)) return "bg-gray-500";
    return "bg-purple-500";
}

// File Viewer Logic
const showFileViewer = ref(false);
const fileToView = ref({});

function openFileViewer(file) {
    fileToView.value = file;
    showFileViewer.value = true;
}

function closeFileViewer() {
    showFileViewer.value = false;
    fileToView.value = {};
}
</script>

<style scoped>
/* Custom Animations */
.rotate-90 {
    transform: rotate(90deg);
}

.rotate-180 {
    transform: rotate(180deg);
}

/* Glassmorphism Effect */
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
}

/* Modern Node Item Styling */
.modern-node-item {
    position: relative;
}

/* Hover Effects */
.node-card:hover {
    transform: translateY(-2px);
}

/* Custom Shadows */
.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.hover\:shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Dialog Styling */
.modern-dialog :deep(.v-overlay__content) {
    margin: 24px;
}

/* Smooth Transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* File Grid Hover Effects */
.group:hover .group-hover\:text-indigo-600 {
    color: rgb(79 70 229);
}

/* Border Animations */
.hover\:border-indigo-300:hover {
    border-color: rgb(165 180 252);
}
</style>
