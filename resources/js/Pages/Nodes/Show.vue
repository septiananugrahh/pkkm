<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    node: Object,
});

const form = useForm({
    files: [],
});

const submit = () => {
    form.post(route("files.store", props.node.id), {
        forceFormData: true, // penting untuk upload file
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <div>
        <h3>{{ node.name }}</h3>

        <!-- Upload Files -->
        <v-form @submit.prevent="submit">
            <v-file-input
                v-model="form.files"
                multiple
                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                label="Upload files"
                show-size
                outlined
            />
            <v-btn type="submit" color="primary">Upload</v-btn>
        </v-form>

        <!-- List Files -->
        <v-list>
            <v-list-item
                v-for="file in node.files"
                :key="file.id"
                :href="`/storage/${file.file_path}`"
                target="_blank"
            >
                <v-list-item-title>{{ file.file_name }}</v-list-item-title>
                <v-list-item-subtitle>{{
                    file.file_type
                }}</v-list-item-subtitle>
            </v-list-item>
        </v-list>
    </div>
</template>
