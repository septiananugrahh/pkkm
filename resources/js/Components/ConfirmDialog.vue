<template>
    <v-dialog v-model="internalVisible" max-width="450">
        <v-card>
            <v-card-title class="headline">{{ title }}</v-card-title>
            <v-card-text>
                {{ message }}
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="grey" text @click="cancel">Batal</v-btn>
                <v-btn color="error" text @click="confirm">Hapus</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    visible: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: "Konfirmasi Penghapusan",
    },
    message: {
        type: String,
        default:
            "Apakah Anda yakin ingin menghapus ini? Tindakan ini tidak dapat dibatalkan.",
    },
});

const emit = defineEmits(["confirm", "cancel", "update:visible"]);

const internalVisible = ref(props.visible);

watch(
    () => props.visible,
    (newVal) => {
        internalVisible.value = newVal;
    }
);

watch(internalVisible, (newVal) => {
    emit("update:visible", newVal);
});

const confirm = () => {
    emit("confirm");
    internalVisible.value = false;
};

const cancel = () => {
    emit("cancel");
    internalVisible.value = false;
};
</script>
