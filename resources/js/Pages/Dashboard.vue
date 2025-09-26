<template>
    <AuthenticatedLayout>
        <Head title="Dashboard" />

        <template #header>
            <div class="dashboard-header">
                <h1 class="text-3xl font-bold text-purple-800 mb-2">
                    Dashboard PKKM
                </h1>
                <p class="text-purple-600">
                    Pantau dan kelola data PKKM dengan mudah
                </p>
            </div>
        </template>

        <!-- Filter Section -->
        <v-card class="mb-6" elevation="1">
            <v-card-text class="pb-2">
                <v-row align="center">
                    <v-col cols="12" sm="4">
                        <v-select
                            v-model="selectedYear"
                            :items="years"
                            item-title="year"
                            item-value="id"
                            label="Pilih Tahun"
                            variant="outlined"
                            density="compact"
                            color="purple"
                            @update:modelValue="reload"
                        >
                            <template v-slot:prepend-inner>
                                <v-icon color="purple" size="small"
                                    >mdi-calendar</v-icon
                                >
                            </template>
                        </v-select>
                    </v-col>
                    <v-col cols="12" sm="8">
                        <div class="text-right">
                            <span
                                class="text-h6 text-purple-800 font-weight-bold"
                            >
                                Selamat Datang,
                                {{ $page.props.auth.user.name }}! 👋
                            </span>
                            <div class="text-purple-600 text-sm mt-1">
                                Kelola data PKKM dengan mudah dan efisien
                            </div>
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>

        <!-- Statistics Cards -->
        <v-row class="mb-6">
            <v-col cols="12" sm="6" md="3">
                <v-card class="stat-card stat-card-purple" elevation="2">
                    <v-card-text class="text-center pa-4">
                        <div class="stat-icon-container mb-3">
                            <v-icon size="32" color="white"
                                >mdi-folder-multiple-outline</v-icon
                            >
                        </div>
                        <div class="text-h4 font-weight-bold text-white mb-1">
                            {{ totalNodes }}
                        </div>
                        <div class="text-subtitle-2 text-purple-100">
                            Total Nodes
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="3">
                <v-card class="stat-card stat-card-blue" elevation="2">
                    <v-card-text class="text-center pa-4">
                        <div class="stat-icon-container mb-3">
                            <v-icon size="32" color="white"
                                >mdi-file-multiple-outline</v-icon
                            >
                        </div>
                        <div class="text-h4 font-weight-bold text-white mb-1">
                            {{ totalFiles }}
                        </div>
                        <div class="text-subtitle-2 text-blue-100">
                            Total Files
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="3">
                <v-card class="stat-card stat-card-teal" elevation="2">
                    <v-card-text class="text-center pa-4">
                        <div class="stat-icon-container mb-3">
                            <v-icon size="32" color="white"
                                >mdi-calendar-check-outline</v-icon
                            >
                        </div>
                        <div class="text-h4 font-weight-bold text-white mb-1">
                            {{ totalYears }}
                        </div>
                        <div class="text-subtitle-2 text-teal-100">
                            Total Tahun
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="3">
                <v-card class="stat-card stat-card-green" elevation="2">
                    <v-card-text class="text-center pa-4">
                        <div class="stat-icon-container mb-3">
                            <v-icon size="32" color="white"
                                >mdi-chart-pie</v-icon
                            >
                        </div>
                        <div class="text-h4 font-weight-bold text-white mb-1">
                            {{ completionPercentage }}%
                        </div>
                        <div class="text-subtitle-2 text-green-100">
                            Persentase Selesai
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- Chart and Progress Section -->
        <v-row class="mb-6">
            <v-col cols="12" md="6">
                <v-card elevation="2" class="h-100">
                    <v-card-title class="d-flex align-center">
                        <v-icon color="purple" class="mr-2"
                            >mdi-chart-donut</v-icon
                        >
                        Progress Node (Parent)
                    </v-card-title>
                    <v-card-text>
                        <div class="chart-container">
                            <canvas
                                ref="pieChart"
                                width="300"
                                height="300"
                            ></canvas>
                        </div>
                        <div class="mt-4">
                            <v-row>
                                <v-col cols="6">
                                    <div class="d-flex align-center">
                                        <div
                                            class="legend-dot bg-green mr-2"
                                        ></div>
                                        <span class="text-sm"
                                            >Selesai:
                                            {{ nodeStats.completed }}</span
                                        >
                                    </div>
                                </v-col>
                                <v-col cols="6">
                                    <div class="d-flex align-center">
                                        <div
                                            class="legend-dot bg-orange mr-2"
                                        ></div>
                                        <span class="text-sm"
                                            >Belum Selesai:
                                            {{ nodeStats.incomplete }}</span
                                        >
                                    </div>
                                </v-col>
                            </v-row>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" md="6">
                <v-card elevation="2" class="h-100">
                    <v-card-title class="d-flex align-center">
                        <v-icon color="purple" class="mr-2"
                            >mdi-progress-check</v-icon
                        >
                        Detail Progress
                    </v-card-title>
                    <v-card-text>
                        <div class="progress-detail">
                            <div class="mb-4">
                                <div class="d-flex justify-space-between mb-2">
                                    <span class="font-weight-medium"
                                        >Node Selesai</span
                                    >
                                    <span class="text-green font-weight-bold">{{
                                        nodeStats.completed
                                    }}</span>
                                </div>
                                <v-progress-linear
                                    :model-value="
                                        (nodeStats.completed / totalNodes) * 100
                                    "
                                    color="green"
                                    height="8"
                                    rounded
                                ></v-progress-linear>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-space-between mb-2">
                                    <span class="font-weight-medium"
                                        >Node Belum Selesai</span
                                    >
                                    <span
                                        class="text-orange font-weight-bold"
                                        >{{ nodeStats.incomplete }}</span
                                    >
                                </div>
                                <v-progress-linear
                                    :model-value="
                                        (nodeStats.incomplete / totalNodes) *
                                        100
                                    "
                                    color="orange"
                                    height="8"
                                    rounded
                                ></v-progress-linear>
                            </div>

                            <v-divider class="my-4"></v-divider>

                            <div class="text-center">
                                <div
                                    class="text-h5 font-weight-bold text-purple-800 mb-2"
                                >
                                    {{ completionPercentage }}%
                                </div>
                                <div class="text-purple-600">
                                    Tingkat Penyelesaian Keseluruhan
                                </div>
                                <v-progress-linear
                                    :model-value="completionPercentage"
                                    color="purple"
                                    height="12"
                                    rounded
                                    class="mt-3"
                                ></v-progress-linear>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- Action Buttons -->
        <v-card elevation="2">
            <v-card-title class="d-flex align-center">
                <v-icon color="purple" class="mr-2">mdi-cog-outline</v-icon>
                Aksi Cepat
            </v-card-title>
            <v-card-text>
                <div class="d-flex justify-center flex-wrap gap-4">
                    <Link :href="route('nodes.index')">
                        <v-btn
                            color="purple"
                            size="large"
                            variant="elevated"
                            class="action-btn"
                        >
                            <v-icon class="mr-2">mdi-file-tree</v-icon>
                            Kelola Data
                        </v-btn>
                    </Link>

                    <Link :href="route('show.index')" target="_blank">
                        <v-btn
                            color="teal"
                            size="large"
                            variant="elevated"
                            class="action-btn"
                        >
                            <v-icon class="mr-2">mdi-eye-outline</v-icon>
                            Tampilan Publik
                        </v-btn>
                    </Link>
                </div>
            </v-card-text>
        </v-card>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, computed, onMounted, nextTick } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    totalNodes: Number,
    totalFiles: Number,
    totalYears: Number,
    years: Array,
    year: [Number, String],
    completionPercentage: Number,
    nodeStats: {
        type: Object,
        default: () => ({ completed: 0, incomplete: 0 }),
    },
});

const selectedYear = ref(props.year);
const pieChart = ref(null);

// Computed untuk statistik node
const nodeStats = computed(() => {
    if (props.nodeStats) {
        return props.nodeStats;
    }
    // Fallback calculation jika nodeStats tidak tersedia
    const completed = Math.round(
        (props.completionPercentage / 100) * props.totalNodes
    );
    return {
        completed: completed,
        incomplete: props.totalNodes - completed,
    };
});

const reload = () => {
    router.get(
        route("dashboard"),
        { year: selectedYear.value },
        { preserveState: true }
    );
};

const drawPieChart = () => {
    if (!pieChart.value) return;

    const canvas = pieChart.value;
    const ctx = canvas.getContext("2d");
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    const radius = Math.min(centerX, centerY) - 20;

    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    const total = nodeStats.value.completed + nodeStats.value.incomplete;
    if (total === 0) return;

    const completedAngle = (nodeStats.value.completed / total) * 2 * Math.PI;

    // Draw completed section (green)
    ctx.beginPath();
    ctx.moveTo(centerX, centerY);
    ctx.arc(
        centerX,
        centerY,
        radius,
        -Math.PI / 2,
        -Math.PI / 2 + completedAngle
    );
    ctx.closePath();
    ctx.fillStyle = "#4CAF50";
    ctx.fill();

    // Draw incomplete section (orange)
    if (nodeStats.value.incomplete > 0) {
        ctx.beginPath();
        ctx.moveTo(centerX, centerY);
        ctx.arc(
            centerX,
            centerY,
            radius,
            -Math.PI / 2 + completedAngle,
            -Math.PI / 2 + 2 * Math.PI
        );
        ctx.closePath();
        ctx.fillStyle = "#FF9800";
        ctx.fill();
    }

    // Draw center circle (white)
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius * 0.5, 0, 2 * Math.PI);
    ctx.fillStyle = "white";
    ctx.fill();

    // Draw percentage text in center
    ctx.fillStyle = "#7C3AED";
    ctx.font = "bold 24px Arial";
    ctx.textAlign = "center";
    ctx.fillText(`${props.completionPercentage}%`, centerX, centerY + 8);
};

onMounted(async () => {
    await nextTick();
    drawPieChart();
});
</script>

<style scoped>
.dashboard-header {
    text-align: center;
    padding: 20px 0;
}

.stat-card {
    border-radius: 16px !important;
    transition: transform 0.2s ease !important;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-card-purple {
    background: linear-gradient(135deg, #b794f6 0%, #9f7aea 100%) !important;
}

.stat-card-blue {
    background: linear-gradient(135deg, #63b3ed 0%, #4299e1 100%) !important;
}

.stat-card-teal {
    background: linear-gradient(135deg, #4fd1c7 0%, #38b2ac 100%) !important;
}

.stat-card-green {
    background: linear-gradient(135deg, #68d391 0%, #48bb78 100%) !important;
}

.stat-icon-container {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.chart-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 300px;
}

.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.bg-green {
    background-color: #4caf50;
}

.bg-orange {
    background-color: #ff9800;
}

.progress-detail {
    padding: 16px;
}

.action-btn {
    min-width: 160px !important;
    text-transform: none !important;
    font-weight: 600 !important;
}

.action-btn:hover {
    transform: translateY(-1px);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .chart-container canvas {
        max-width: 250px;
        max-height: 250px;
    }

    .dashboard-header h1 {
        font-size: 1.5rem;
    }

    .action-btn {
        width: 100%;
        margin-bottom: 8px;
    }
}
</style>
