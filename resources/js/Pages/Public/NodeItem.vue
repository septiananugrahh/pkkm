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
                <div class="modal-content-wrapper">
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
                        class="bg-white overflow-hidden shadow-sm"
                    >
                        <iframe
                            :src="`/storage/${fileToView.file_path}`"
                            width="100%"
                            height="600px"
                            style="border: none"
                        ></iframe>
                    </div>

                    <!-- DOCX Preview - DIPERBAIKI -->
                    <div
                        v-else-if="isDocx(fileToView.file_name)"
                        class="docx-viewer"
                    >
                        <div id="docx-container" class="pa-6"></div>

                        <!-- Loading State -->
                        <div v-if="docxLoading" class="docx-loading">
                            <v-progress-circular
                                indeterminate
                                color="purple"
                                size="64"
                            ></v-progress-circular>
                            <p class="mt-4 text-grey-darken-1">
                                Memuat dokumen...
                            </p>
                        </div>

                        <!-- DOCX Content -->
                        <div
                            v-else-if="docxContent"
                            class="docx-content-wrapper"
                        >
                            <div
                                class="docx-content"
                                v-html="docxContent"
                            ></div>
                        </div>

                        <!-- Error State -->
                        <div v-else-if="docxError" class="docx-error">
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
import { renderAsync } from "docx-preview";

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

const docxContent = ref("");
const docxLoading = ref(false);
const docxError = ref("");

function isImage(fileName) {
    return /\.(jpg|jpeg|png|gif|webp|svg|bmp)$/i.test(fileName);
}

function isPdf(fileName) {
    return /\.pdf$/i.test(fileName);
}

const isDocx = (fileName) => /\.docx$/i.test(fileName); // TAMBAHKAN

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

const openFileViewer = async (file) => {
    fileToView.value = file;
    showFileViewer.value = true;

    if (isDocx(file.file_name)) {
        await loadDocxContent(file.file_path);
    }
};

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

/* Modal Content Wrapper */
.modal-content-wrapper {
    padding: 24px;
    background: #f9fafb;
    min-height: 400px;
}

/* Loading State */
.docx-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
}

/* Error State */
.docx-error {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
    text-align: center;
    padding: 32px;
}

/* DOCX Content Wrapper */
.docx-content-wrapper {
    background: white;
    overflow-y: auto;
    overflow-x: hidden;
    height: 100%;
    padding: 48px 24px;
}

/* DOCX Content */
.docx-content {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 48px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border-radius: 8px;
}

/* Deep Selectors untuk konten v-html */
.docx-content :deep(h1) {
    font-size: 2em;
    font-weight: bold;
    margin-top: 24px;
    margin-bottom: 16px;
    color: #1a202c;
    line-height: 1.3;
}

.docx-content :deep(h2) {
    font-size: 1.5em;
    font-weight: bold;
    margin-top: 20px;
    margin-bottom: 12px;
    color: #2d3748;
    line-height: 1.3;
}

.docx-content :deep(h3) {
    font-size: 1.25em;
    font-weight: bold;
    margin-top: 16px;
    margin-bottom: 10px;
    color: #4a5568;
    line-height: 1.3;
}

.docx-content :deep(p) {
    margin-bottom: 12px;
    line-height: 1.8;
    color: #4a5568;
    text-align: justify;
}

.docx-content :deep(ul),
.docx-content :deep(ol) {
    margin-left: 24px;
    margin-bottom: 16px;
    padding-left: 8px;
}

.docx-content :deep(li) {
    margin-bottom: 8px;
    line-height: 1.8;
}

.docx-content :deep(table) {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 0.95em;
}

.docx-content :deep(table td),
.docx-content :deep(table th) {
    border: 1px solid #e2e8f0;
    padding: 10px 14px;
    text-align: left;
}

.docx-content :deep(table th) {
    background-color: #f7fafc;
    font-weight: bold;
    color: #2d3748;
}

.docx-content :deep(table tr:nth-child(even)) {
    background-color: #f9fafb;
}

.docx-content :deep(strong),
.docx-content :deep(b) {
    font-weight: 700;
    color: #2d3748;
}

.docx-content :deep(em),
.docx-content :deep(i) {
    font-style: italic;
}

.docx-content :deep(u) {
    text-decoration: underline;
}

.docx-content :deep(img) {
    max-width: 100%;
    height: auto;
    margin: 20px 0;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.docx-content :deep(a) {
    color: #4c51bf;
    text-decoration: none;
    border-bottom: 1px solid #4c51bf;
    transition: all 0.2s;
}

.docx-content :deep(a:hover) {
    color: #5a67d8;
    border-bottom-color: #5a67d8;
}

.docx-content :deep(blockquote) {
    border-left: 4px solid #4c51bf;
    padding-left: 20px;
    margin: 20px 0;
    font-style: italic;
    color: #718096;
}

.docx-content :deep(code) {
    background-color: #f7fafc;
    padding: 2px 6px;
    border-radius: 4px;
    font-family: "Courier New", monospace;
    font-size: 0.9em;
    color: #e53e3e;
}

.docx-content :deep(pre) {
    background-color: #2d3748;
    color: #e2e8f0;
    padding: 16px;
    border-radius: 8px;
    overflow-x: auto;
    margin: 20px 0;
}

.docx-content :deep(pre code) {
    background: none;
    color: inherit;
    padding: 0;
}

/* Custom Scrollbar */
.docx-content-wrapper::-webkit-scrollbar {
    width: 10px;
}

.docx-content-wrapper::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 5px;
}

.docx-content-wrapper::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #4c51bf, #667eea);
    border-radius: 5px;
}

.docx-content-wrapper::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #434190, #5a67d8);
}

/* Animation */
.docx-content-wrapper {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.docx-viewer {
    background: #f5f5f5;
    min-height: 600px;
    max-height: 600px;
    overflow-y: auto;
}

/* Responsive */
@media (max-width: 768px) {
    .docx-content {
        padding: 24px 16px;
    }

    .docx-content-wrapper {
        padding: 24px 12px;
    }
}
</style>
