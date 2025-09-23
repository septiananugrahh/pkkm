<template>
    <div class="node-item">
        <v-row align="center" class="py-1 node-header" @click.stop="toggle">
            <v-col cols="auto">
                <v-icon small v-if="hasChildren">mdi-folder</v-icon>
                <v-icon small v-else>mdi-file-outline</v-icon>
            </v-col>

            <v-col>
                <div>{{ node.title }}</div>
            </v-col>
        </v-row>

        <v-expand-transition>
            <div v-if="expanded" class="node-children-wrapper">
                <div class="node-indented-content">
                    <!-- File List -->
                    <v-card
                        class="mt-2 pa-3"
                        outlined
                        v-if="node.files && node.files.length"
                    >
                        <strong>Files:</strong>
                        <v-list dense>
                            <v-list-item
                                v-for="f in node.files"
                                :key="f.id"
                                class="flex-column align-start"
                            >
                                <div class="d-flex align-center w-100">
                                    <a
                                        href="#"
                                        class="mr-2"
                                        @click.prevent="openFileViewer(f)"
                                    >
                                        {{ f.file_name }}
                                    </a>
                                </div>
                                <div v-if="isImage(f.file_name)" class="mt-2">
                                    <v-img
                                        :src="`/storage/${f.file_path}`"
                                        max-width="200"
                                        class="rounded"
                                        cover
                                        @click="openFileViewer(f)"
                                        style="cursor: pointer"
                                    />
                                </div>
                            </v-list-item>
                        </v-list>
                    </v-card>

                    <!-- Recursive Child -->
                    <div v-if="hasChildren" class="mt-3">
                        <PublicNodeItem
                            v-for="child in node.children"
                            :key="child.id"
                            :node="child"
                        />
                    </div>
                </div>
            </div>
        </v-expand-transition>

        <!-- File Viewer Modal -->
        <v-dialog v-model="showFileViewer" max-width="1200">
            <v-card>
                <v-toolbar dense flat>
                    <v-toolbar-title>{{
                        fileToView.file_name
                    }}</v-toolbar-title>
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
                        />
                    </div>
                    <div v-else-if="isPdf(fileToView.file_name)">
                        <iframe
                            :src="`/storage/${fileToView.file_path}`"
                            width="100%"
                            height="600px"
                            style="border: none"
                        ></iframe>
                    </div>
                    <div v-else>
                        <p>Tidak ada preview tersedia untuk tipe file ini.</p>
                        <v-btn
                            :href="`/storage/${fileToView.file_path}`"
                            target="_blank"
                            color="primary"
                            text
                        >
                            Download File
                        </v-btn>
                    </div>
                </v-card-text>
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
    return /\.(jpg|jpeg|png|gif|webp)$/i.test(fileName);
}

function isPdf(fileName) {
    return /\.pdf$/i.test(fileName);
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
.node-item {
    border-bottom: 1px dashed rgba(0, 0, 0, 0.03);
    position: relative;
}

.node-header {
    cursor: pointer;
}

.node-children-wrapper {
    position: relative;
    padding-left: 20px;
}

.node-children-wrapper::before {
    content: "";
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 1px;
    background-color: #bdbdbd;
}

.node-item .node-item {
    border-left: 1px solid transparent;
    position: relative;
}

.node-item .node-item::before {
    content: "";
    position: absolute;
    left: -10px;
    top: 15px;
    width: 10px;
    height: 1px;
    background-color: #bdbdbd;
}
</style>
