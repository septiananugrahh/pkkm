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

    <v-app class="login-app">
        <!-- Background with gradient -->
        <div class="login-background">
            <div class="background-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>
        </div>

        <v-container class="login-container">
            <v-row justify="center" align="center" class="min-vh-100">
                <v-col cols="12" sm="10" md="8" lg="5" xl="4">
                    <!-- Login Card -->
                    <v-card class="login-card" elevation="0">
                        <!-- Header Section -->
                        <div class="login-header">
                            <div class="logo-container">
                                <div class="logo-circle">
                                    <v-icon size="48" color="white"
                                        >mdi-cog</v-icon
                                    >
                                </div>
                            </div>

                            <h1 class="login-title">Selamat Datang</h1>
                            <p class="login-subtitle">Sistem Manajemen PKKM</p>
                        </div>

                        <!-- Status Alert -->
                        <v-alert
                            v-if="status"
                            type="success"
                            variant="tonal"
                            color="purple"
                            class="mb-6 status-alert"
                            density="compact"
                        >
                            <template v-slot:prepend>
                                <v-icon>mdi-check-circle</v-icon>
                            </template>
                            {{ status }}
                        </v-alert>

                        <!-- Login Form -->
                        <div class="login-form">
                            <v-form @submit.prevent="submit">
                                <!-- Email Field -->
                                <div class="form-field">
                                    <v-text-field
                                        v-model="form.email"
                                        label="Alamat Email"
                                        type="email"
                                        variant="outlined"
                                        color="purple"
                                        density="comfortable"
                                        :error-messages="form.errors.email"
                                        required
                                        class="custom-input"
                                    >
                                        <template v-slot:prepend-inner>
                                            <v-icon
                                                color="purple-lighten-1"
                                                size="20"
                                            >
                                                mdi-email-outline
                                            </v-icon>
                                        </template>
                                    </v-text-field>
                                </div>

                                <!-- Password Field -->
                                <div class="form-field">
                                    <v-text-field
                                        v-model="form.password"
                                        label="Kata Sandi"
                                        :type="
                                            showPassword ? 'text' : 'password'
                                        "
                                        variant="outlined"
                                        color="purple"
                                        density="comfortable"
                                        :error-messages="form.errors.password"
                                        required
                                        class="custom-input"
                                    >
                                        <template v-slot:prepend-inner>
                                            <v-icon
                                                color="purple-lighten-1"
                                                size="20"
                                            >
                                                mdi-lock-outline
                                            </v-icon>
                                        </template>
                                        <template v-slot:append-inner>
                                            <v-btn
                                                icon
                                                variant="text"
                                                size="small"
                                                color="purple-lighten-1"
                                                @click="
                                                    showPassword = !showPassword
                                                "
                                            >
                                                <v-icon size="20">
                                                    {{
                                                        showPassword
                                                            ? "mdi-eye-off-outline"
                                                            : "mdi-eye-outline"
                                                    }}
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                    </v-text-field>
                                </div>

                                <!-- Remember Me & Forgot Password -->
                                <div class="form-options">
                                    <v-checkbox
                                        v-model="form.remember"
                                        label="Ingat Saya"
                                        color="purple"
                                        density="compact"
                                        hide-details
                                        class="remember-checkbox"
                                    />

                                    <!-- <Link
                                        v-if="canResetPassword"
                                        :href="route('password.request')"
                                        class="forgot-password-link"
                                    >
                                        Lupa Password?
                                    </Link> -->
                                </div>

                                <!-- Login Button -->
                                <v-btn
                                    type="submit"
                                    color="purple"
                                    variant="elevated"
                                    size="x-large"
                                    block
                                    class="login-button"
                                    :loading="form.processing"
                                    :disabled="form.processing"
                                >
                                    <v-icon class="mr-2" size="20"
                                        >mdi-login</v-icon
                                    >
                                    Masuk ke Sistem
                                </v-btn>
                            </v-form>
                        </div>

                        <!-- Footer -->
                        <div class="login-footer">
                            <div class="footer-text">
                                <v-icon
                                    size="16"
                                    color="purple-lighten-2"
                                    class="mr-1"
                                >
                                    mdi-shield-check
                                </v-icon>
                                Sistem terlindungi dan aman
                            </div>
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
</template>

<style scoped>
/* App Background */
.login-app {
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}

.login-background {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        135deg,
        #f8f4ff 0%,
        #f3e8ff 25%,
        #e6d7ff 50%,
        #ddd6fe 75%,
        #c4b5fd 100%
    );
    z-index: -1;
}

/* Background Shapes */
.background-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, #b794f6, #9f7aea);
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 150px;
    height: 150px;
    background: linear-gradient(135deg, #c084fc, #a855f7);
    top: 60%;
    right: 15%;
    animation-delay: 2s;
}

.shape-3 {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #ddd6fe, #c4b5fd);
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
}

@keyframes float {
    0%,
    100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
    }
}

/* Container */
.login-container {
    position: relative;
    z-index: 1;
}

.min-vh-100 {
    min-height: 100vh;
}

/* Login Card */
.login-card {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(10px);
    border-radius: 24px !important;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 20px 40px rgba(183, 148, 246, 0.15) !important;
    overflow: hidden;
    max-width: 480px;
    margin: 0 auto;
}

/* Header */
.login-header {
    text-align: center;
    padding: 40px 32px 32px;
    background: linear-gradient(
        135deg,
        rgba(183, 148, 246, 0.05),
        rgba(248, 244, 255, 0.3)
    );
}

.logo-container {
    margin-bottom: 24px;
}

.logo-circle {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #b794f6 0%, #9f7aea 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 8px 24px rgba(183, 148, 246, 0.3);
}

.login-title {
    font-size: 2rem;
    font-weight: 700;
    color: #553c9a;
    margin-bottom: 8px;
}

.login-subtitle {
    color: #7c3aed;
    font-size: 1rem;
    font-weight: 500;
    opacity: 0.8;
}

/* Status Alert */
.status-alert {
    margin: 0 32px 24px;
    border-radius: 12px !important;
}

/* Form */
.login-form {
    padding: 0 32px;
}

.form-field {
    margin-bottom: 20px;
}

.custom-input {
    border-radius: 12px;
}

.custom-input :deep(.v-field) {
    border-radius: 12px !important;
    background-color: rgba(248, 244, 255, 0.3);
    border: 1px solid rgba(183, 148, 246, 0.2);
    transition: all 0.2s ease;
}

.custom-input :deep(.v-field:hover) {
    background-color: rgba(248, 244, 255, 0.5);
    border-color: rgba(183, 148, 246, 0.4);
}

.custom-input :deep(.v-field--focused) {
    background-color: rgba(255, 255, 255, 0.8);
    border-color: #b794f6;
    box-shadow: 0 0 0 2px rgba(183, 148, 246, 0.1);
}

.custom-input :deep(.v-label) {
    color: #7c3aed;
    font-weight: 500;
}

/* Form Options */
.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.remember-checkbox :deep(.v-label) {
    color: #6b46c1 !important;
    font-weight: 500;
    font-size: 0.875rem;
}

.forgot-password-link {
    color: #7c3aed;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    transition: color 0.2s ease;
}

.forgot-password-link:hover {
    color: #6b46c1;
    text-decoration: underline;
}

/* Login Button */
.login-button {
    background: linear-gradient(135deg, #b794f6 0%, #9f7aea 100%) !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
    text-transform: none !important;
    letter-spacing: 0.5px;
    height: 52px !important;
    box-shadow: 0 4px 12px rgba(183, 148, 246, 0.3) !important;
    transition: all 0.2s ease !important;
}

.login-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(183, 148, 246, 0.4) !important;
}

.login-button:active {
    transform: translateY(0);
}

/* Footer */
.login-footer {
    padding: 24px 32px 32px;
    text-align: center;
}

.footer-text {
    color: #9ca3af;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Responsive Design */
@media (max-width: 768px) {
    .login-card {
        margin: 16px;
        border-radius: 20px !important;
    }

    .login-header {
        padding: 32px 24px 24px;
    }

    .login-form {
        padding: 0 24px;
    }

    .login-footer {
        padding: 20px 24px 28px;
    }

    .login-title {
        font-size: 1.75rem;
    }

    .logo-circle {
        width: 64px;
        height: 64px;
    }

    .shape {
        display: none; /* Hide floating shapes on mobile for better performance */
    }
}

/* Loading State */
.login-button.v-btn--loading {
    pointer-events: none;
}

/* Focus States */
.custom-input :deep(.v-field--focused .v-field__outline) {
    --v-field-border-width: 2px;
    --v-field-border-opacity: 1;
}

/* Error States */
.custom-input :deep(.v-field--error .v-field__outline) {
    border-color: #ef4444;
}

.custom-input :deep(.v-field--error:hover .v-field__outline) {
    border-color: #dc2626;
}
</style>
