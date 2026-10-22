<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";

const sessions = ref([]);
const classes = ref([]);
const students = ref([]);

const selectedSession = ref("");
const selectedClass = ref("");
const section = ref("");

const loading = ref(false);
const generating = ref(false);

const loadFilters = async () => {
    try {
        const response = await axios.get("/api/attendance");

        sessions.value = response.data.sessions || [];
        classes.value = response.data.classes || [];
    } catch (e) {
        toast.error("Unable to load filters.");
    }
};

const loadStudents = async () => {
    if (!selectedSession.value || !selectedClass.value) {
        students.value = [];
        return;
    }

    loading.value = true;

    try {
        const response = await axios.get("/api/attendance/students", {
            params: {
                session_id: selectedSession.value,
                class_id: selectedClass.value,
                section: section.value || null,
            },
        });

        students.value = response.data.students || [];
    } catch (e) {
        toast.error("Unable to load students.");
        students.value = [];
    } finally {
        loading.value = false;
    }
};

const generateCards = async () => {
    if (!selectedSession.value || !selectedClass.value) {
        toast.info("Please select session and class first.");
        return;
    }

    if (!students.value.length) {
        toast.info("No students to generate cards for.");
        return;
    }

    generating.value = true;

    try {
        const response = await axios.post(
            "/api/academic/students/id-cards/generate",
            {
                session_id: selectedSession.value,
                class_id: selectedClass.value,
                section: section.value || null,
            },
            {
                responseType: "blob",
            }
        );

        const url = window.URL.createObjectURL(
            new Blob([response.data], {
                type: "application/pdf",
            })
        );

        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", "student_id_cards.pdf");

        document.body.appendChild(link);
        link.click();
        link.remove();

        window.URL.revokeObjectURL(url);

        toast.success("ID cards generated successfully.");
    } catch (e) {
        toast.error("Unable to generate ID cards.");
    } finally {
        generating.value = false;
    }
};

const previewCards = async () => {
    if (!selectedSession.value || !selectedClass.value) {
        toast.info("Please select session and class to preview.");
        return;
    }

    if (!students.value.length) {
        toast.info("No students available for preview.");
        return;
    }

    generating.value = true;

    try {
        const response = await axios.post(
            "/api/academic/students/id-cards/preview",
            {
                session_id: selectedSession.value,
                class_id: selectedClass.value,
                section: section.value || null,
            },
            {
                responseType: "text",
            }
        );

        const previewWindow = window.open("", "_blank");

        if (!previewWindow) {
            toast.error("Please allow popups to preview the cards.");
            return;
        }

        previewWindow.document.open();
        previewWindow.document.write(response.data);
        previewWindow.document.close();
    } catch (e) {
        toast.error("Unable to generate preview.");
    } finally {
        generating.value = false;
    }
};

onMounted(async () => {
    await loadFilters();
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 p-4 dark:bg-gray-950 sm:p-6">
        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Header -->
            <div
                class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 via-indigo-700 to-purple-700 p-6 text-white shadow-lg"
            >
                <!-- Background decoration -->
                <div
                    class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/10"
                ></div>

                <div
                    class="absolute -bottom-20 right-24 h-48 w-48 rounded-full bg-white/5"
                ></div>

                <div
                    class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between"
                >
                    <!-- Logo + Title -->
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-25 w-25 shrink-0 items-center justify-center rounded-full bg-white shadow-md overflow-hidden"
                        >
                            <img
                                :src="'/images/logo/transparent-logo.png'"
                                alt="Institute Logo"
                                class="h-full w-full object-contain"
                            />
                        </div>

                        <div>
                            <p
                                class="text-sm font-medium uppercase tracking-wider text-blue-100"
                            >
                                Student Management
                            </p>

                            <h1
                                class="mt-1 text-2xl font-bold tracking-tight sm:text-3xl"
                            >
                                Student Identity Cards
                            </h1>

                            <p class="mt-1 max-w-2xl text-sm text-blue-100">
                                Generate professional student identity cards
                                with front, back and barcode.
                            </p>
                        </div>
                    </div>

                    <!-- Student Count -->
                    <div
                        class="flex items-center gap-3 self-start rounded-xl bg-white/10 px-4 py-3 backdrop-blur-sm sm:self-auto"
                    >
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/15"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-6 w-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.125-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"
                                />
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs text-blue-100">Students Found</p>

                            <p class="text-xl font-bold">
                                {{ students.length }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Card -->
            <div
                class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900"
            >
                <!-- Card Header -->
                <div
                    class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-5 w-5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M10.5 6h9.75M10.5 6a3 3 0 1 1-6 0m6 0a3 3 0 1 0-6 0M3.75 6H4.5m6 12h9.75m-9.75 0a3 3 0 1 1-6 0m6 0a3 3 0 1 0-6 0m-6 0h.75m9-6h9.75m-9.75 0a3 3 0 1 1-6 0m6 0a3 3 0 1 0-6 0m-6 0h.75"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2
                                class="font-semibold text-gray-900 dark:text-white"
                            >
                                Card Generation Filters
                            </h2>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Select the students for whom you want to
                                generate identity cards.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="p-5 sm:p-6">
                    <div
                        class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4"
                    >
                        <!-- Session -->
                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Academic Session
                            </label>

                            <div class="relative">
                                <select
                                    v-model="selectedSession"
                                    @change="loadStudents"
                                    class="w-full appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                                >
                                    <option value="">Select Session</option>

                                    <option
                                        v-for="s in sessions"
                                        :key="s.id"
                                        :value="s.id"
                                    >
                                        {{ s.session_name }}
                                    </option>
                                </select>

                                <svg
                                    class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m19 9-7 7-7-7"
                                    />
                                </svg>
                            </div>
                        </div>

                        <!-- Class -->
                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Class
                            </label>

                            <div class="relative">
                                <select
                                    v-model="selectedClass"
                                    @change="loadStudents"
                                    class="w-full appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                                >
                                    <option value="">Select Class</option>

                                    <option
                                        v-for="c in classes"
                                        :key="c.id"
                                        :value="c.id"
                                    >
                                        {{ c.name }}
                                    </option>
                                </select>

                                <svg
                                    class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m19 9-7 7-7-7"
                                    />
                                </svg>
                            </div>
                        </div>

                        <!-- Section -->
                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Section
                                <span class="font-normal text-gray-400">
                                    (Optional)
                                </span>
                            </label>

                            <input
                                v-model="section"
                                @change="loadStudents"
                                type="text"
                                placeholder="e.g. A"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                            />
                        </div>

                        <!-- Buttons -->
                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Actions
                            </label>

                            <div class="flex gap-2">
                                <!-- Preview -->
                                <button
                                    type="button"
                                    :disabled="
                                        generating ||
                                        !selectedSession ||
                                        !selectedClass
                                    "
                                    @click="previewCards"
                                    class="flex flex-1 items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-3 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-750"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                        class="h-5 w-5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.423 7.51 7.36 5 12 5c4.64 0 8.577 2.51 9.964 6.678.052.16.052.332 0 .644C20.577 16.49 16.64 19 12 19c-4.64 0-8.577-2.51-9.964-6.678Z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                        />
                                    </svg>

                                    Preview
                                </button>

                                <!-- Generate -->
                                <button
                                    type="button"
                                    :disabled="
                                        generating ||
                                        !selectedSession ||
                                        !selectedClass
                                    "
                                    @click="generateCards"
                                    class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-3 py-3 text-sm font-semibold text-white shadow-sm transition hover:from-blue-700 hover:to-indigo-700 hover:shadow-md disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <svg
                                        v-if="!generating"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                        class="h-5 w-5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"
                                        />
                                    </svg>

                                    <svg
                                        v-else
                                        class="h-5 w-5 animate-spin"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                        />

                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                        />
                                    </svg>

                                    {{
                                        generating
                                            ? "Generating..."
                                            : "Generate PDF"
                                    }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Active Filters -->
                    <div
                        v-if="selectedSession || selectedClass || section"
                        class="mt-5 flex flex-wrap items-center gap-2 border-t border-gray-100 pt-5 dark:border-gray-800"
                    >
                        <span
                            class="text-xs font-semibold uppercase tracking-wide text-gray-400"
                        >
                            Selected:
                        </span>

                        <span
                            v-if="selectedSession"
                            class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-400"
                        >
                            Session selected
                        </span>

                        <span
                            v-if="selectedClass"
                            class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400"
                        >
                            Class selected
                        </span>

                        <span
                            v-if="section"
                            class="rounded-full bg-purple-50 px-3 py-1 text-xs font-medium text-purple-700 dark:bg-purple-500/10 dark:text-purple-400"
                        >
                            Section: {{ section }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Students -->
            <div
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900"
            >
                <!-- Table Header -->
                <div
                    class="flex flex-col gap-3 border-b border-gray-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 dark:border-gray-800"
                >
                    <div>
                        <h2 class="font-semibold text-gray-900 dark:text-white">
                            Students
                        </h2>

                        <p
                            class="mt-0.5 text-sm text-gray-500 dark:text-gray-400"
                        >
                            Students matching the selected filters
                        </p>
                    </div>

                    <div
                        v-if="students.length"
                        class="inline-flex w-fit items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700 dark:bg-blue-500/10 dark:text-blue-400"
                    >
                        <span
                            class="flex h-2 w-2 rounded-full bg-blue-500"
                        ></span>

                        {{ students.length }} Students
                    </div>
                </div>

                <!-- Loading -->
                <div
                    v-if="loading"
                    class="flex flex-col items-center justify-center py-16"
                >
                    <div
                        class="h-10 w-10 animate-spin rounded-full border-4 border-gray-200 border-t-blue-600"
                    ></div>

                    <p
                        class="mt-4 text-sm font-medium text-gray-600 dark:text-gray-400"
                    >
                        Loading students...
                    </p>
                </div>

                <!-- Table -->
                <div v-else-if="students.length" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr
                                class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-800/50"
                            >
                                <th
                                    class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                                >
                                    #
                                </th>

                                <th
                                    class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                                >
                                    Student
                                </th>

                                <th
                                    class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                                >
                                    Admission No
                                </th>

                                <th
                                    class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                                >
                                    Card No
                                </th>

                                <th
                                    class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                                >
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody
                            class="divide-y divide-gray-100 dark:divide-gray-800"
                        >
                            <tr
                                v-for="(st, idx) in students"
                                :key="st.id"
                                class="transition hover:bg-blue-50/50 dark:hover:bg-blue-500/5"
                            >
                                <!-- Number -->
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-400"
                                    >
                                        {{ idx + 1 }}
                                    </span>
                                </td>

                                <!-- Student -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-sm font-bold text-white"
                                        >
                                            {{
                                                (
                                                    st.student?.name ||
                                                    st.name ||
                                                    "S"
                                                )
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </div>

                                        <div>
                                            <p
                                                class="font-semibold text-gray-900 dark:text-white"
                                            >
                                                {{
                                                    st.student?.name ||
                                                    st.name ||
                                                    "-"
                                                }}
                                            </p>

                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                Student
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Admission -->
                                <td
                                    class="whitespace-nowrap px-5 py-4 font-medium text-gray-700 dark:text-gray-300"
                                >
                                    {{ st.student?.admission_no || "-" }}
                                </td>

                                <!-- Card -->
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span
                                        class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-1.5 font-mono text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                                    >
                                        {{
                                            st.student?.card_no ||
                                            st.card_no ||
                                            "-"
                                        }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td
                                    class="whitespace-nowrap px-5 py-4 text-center"
                                >
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400"
                                    >
                                        <span
                                            class="h-1.5 w-1.5 rounded-full bg-emerald-500"
                                        ></span>

                                        Ready
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty -->
                <div
                    v-else
                    class="flex flex-col items-center justify-center px-6 py-16 text-center"
                >
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-500 dark:bg-blue-500/10 dark:text-blue-400"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-8 w-8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 9h.01M12 12h.01M9 15h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9Z"
                            />
                        </svg>
                    </div>

                    <h3
                        class="mt-4 font-semibold text-gray-900 dark:text-white"
                    >
                        No Students Found
                    </h3>

                    <p
                        class="mt-1 max-w-md text-sm text-gray-500 dark:text-gray-400"
                    >
                        Select an academic session and class above to load the
                        students and generate their identity cards.
                    </p>
                </div>
            </div>

            <!-- Footer Information -->
            <div
                class="flex items-start gap-3 rounded-xl border border-blue-100 bg-blue-50 p-4 dark:border-blue-500/20 dark:bg-blue-500/5"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="mt-0.5 h-5 w-5 shrink-0 text-blue-600 dark:text-blue-400"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M11.25 11.25.011 3.75a2.25 2.25 0 0 1 2.25-2.25h16.5a2.25 2.25 0 0 1 2.25 2.25v16.5a2.25 2.25 0 0 1-2.25 2.25H3.75a2.25 2.25 0 0 1-2.25-2.25V3.75Z"
                    />
                </svg>

                <div>
                    <p
                        class="text-sm font-semibold text-blue-800 dark:text-blue-300"
                    >
                        ID Card Information
                    </p>

                    <p
                        class="mt-0.5 text-sm text-blue-700/80 dark:text-blue-400/80"
                    >
                        Generated cards will include the student's information,
                        photograph, institute details and barcode. Use Preview
                        to verify the cards before downloading the final PDF.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
