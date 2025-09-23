<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};

const showPassword = ref(false);
</script>

<template>
    <Head title="Log in" />

    <v-app
        class="bg-grey-lighten-3 d-flex align-center justify-center min-vh-100"
    >
        <v-container>
            <v-row justify="center">
                <v-col cols="12" sm="8" md="6" lg="4">
                    <v-card class="pa-8 rounded-xl" elevation="10">
                        <div class="text-center mb-6">
                            <v-avatar size="100" class="mb-4">
                                <v-img
                                    src="https://cdn.vuetifyjs.com/images/logos/vuetify-logo-300.png"
                                    alt="Logo"
                                ></v-img>
                            </v-avatar>
                            <h1 class="text-h4 font-weight-bold text-primary">
                                Selamat Datang
                            </h1>
                            <p class="text-subtitle-1 text-medium-emphasis">
                                Silakan masuk ke akun Anda
                            </p>
                        </div>

                        <v-alert
                            v-if="status"
                            type="success"
                            variant="tonal"
                            class="mb-4 text-center font-weight-medium"
                        >
                            {{ status }}
                        </v-alert>

                        <v-form @submit.prevent="submit">
                            <v-text-field
                                v-model="form.email"
                                label="Email"
                                type="email"
                                variant="outlined"
                                :error-messages="form.errors.email"
                                required
                                class="mb-4"
                                prepend-inner-icon="mdi-email-outline"
                            />

                            <v-text-field
                                v-model="form.password"
                                label="Password"
                                :type="showPassword ? 'text' : 'password'"
                                variant="outlined"
                                :error-messages="form.errors.password"
                                required
                                class="mb-2"
                                prepend-inner-icon="mdi-lock-outline"
                                :append-inner-icon="
                                    showPassword
                                        ? 'mdi-eye-off-outline'
                                        : 'mdi-eye-outline'
                                "
                                @click:append-inner="
                                    showPassword = !showPassword
                                "
                            />

                            <div
                                class="d-flex justify-space-between align-center mb-6"
                            >
                                <v-checkbox
                                    v-model="form.remember"
                                    label="Ingat Saya"
                                    hide-details
                                />

                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-decoration-none text-primary font-weight-medium"
                                >
                                    Lupa Password?
                                </Link>
                            </div>

                            <v-btn
                                type="submit"
                                color="primary"
                                size="large"
                                block
                                class="rounded-lg font-weight-bold"
                                :loading="form.processing"
                                :disabled="form.processing"
                            >
                                Log in
                            </v-btn>
                        </v-form>

                        <div
                            class="text-center mt-8 text-caption text-medium-emphasis"
                        >
                            <!-- Belum punya akun?
                            <Link
                                :href="route('register')"
                                class="text-decoration-none text-primary font-weight-medium"
                            >
                                Daftar Sekarang
                            </Link> -->
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
</template>

<style scoped>
.min-vh-100 {
    min-height: 100vh;
}
</style>
