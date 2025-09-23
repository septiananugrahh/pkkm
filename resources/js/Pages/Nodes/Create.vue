<template>
    <v-container>
        <h2>Tambah Node</h2>

        <v-form @submit.prevent="submit">
            <v-text-field
                v-model="form.title"
                label="Judul Node"
                required
            ></v-text-field>

            <!-- Jika menambahkan child node -->
            <div v-if="parent">
                <p>
                    Parent: <strong>{{ parent.title }}</strong>
                </p>
            </div>

            <v-select
                v-model="form.year_id"
                :items="years"
                item-title="year"
                item-value="id"
                label="Tahun"
                required
            ></v-select>

            <v-btn type="submit" color="primary">Simpan</v-btn>
        </v-form>
    </v-container>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    parent: Object,
    years: Array,
});

const form = useForm({
    title: "",
    parent_id: props.parent ? props.parent.id : null,
    year_id: "",
});

const submit = () => {
    form.post(route("nodes.store"));
};
</script>
