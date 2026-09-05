<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";

const sessions = ref([]);
const classes = ref([]);
const attendance = ref(null);

const selectedSession = ref("");
const selectedClass = ref("");
const section = ref("");
const date = ref("");

const loading = ref(false);

const loadFilters = async () => {
    try {
        const response =
            await axios.get("/api/attendance");

        sessions.value =
            response.data.sessions || [];

        classes.value =
            response.data.classes || [];
    } catch {
        toast.error(
            "Unable to load filters."
        );
    }
};

const loadHistory = async () => {
    loading.value = true;

    try {
        const response =
            await axios.get(
                "/api/attendance/history",
                {
                    params: {
                        session_id:
                            selectedSession.value ||
                            null,

                        class_id:
                            selectedClass.value ||
                            null,

                        section:
                            section.value ||
                            null,

                        date:
                            date.value ||
                            null,

                        per_page: 20,
                    },
                }
            );

        attendance.value =
            response.data.attendance;
    } catch {
        toast.error(
            "Unable to load attendance history."
        );
    } finally {
        loading.value = false;
    }
};

onMounted(async () => {
    await loadFilters();
    await loadHistory();
});
</script>

<template>
    <div
        class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <div
            class="grid grid-cols-1 gap-4 md:grid-cols-4"
        >
            <select
                v-model="selectedSession"
                @change="loadHistory"
                class="rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
            >
                <option value="">
                    All Sessions
                </option>

                <option
                    v-for="session in sessions"
                    :key="session.id"
                    :value="session.id"
                >
                    {{ session.session_name }}
                </option>
            </select>

            <select
                v-model="selectedClass"
                @change="loadHistory"
                class="rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
            >
                <option value="">
                    All Classes
                </option>

                <option
                    v-for="item in classes"
                    :key="item.id"
                    :value="item.id"
                >
                    {{ item.name }}
                </option>
            </select>

            <input
                v-model="section"
                @change="loadHistory"
                type="text"
                placeholder="Section"
                class="rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
            />

            <input
                v-model="date"
                @change="loadHistory"
                type="date"
                class="rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
            />
        </div>

        <div
            v-if="loading"
            class="py-10 text-center text-gray-500"
        >
            Loading history...
        </div>

        <div
            v-else-if="attendance"
            class="overflow-x-auto"
        >
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900">
                        <th class="border px-3 py-2 text-left">
                            Date
                        </th>

                        <th class="border px-3 py-2 text-left">
                            Session
                        </th>

                        <th class="border px-3 py-2 text-left">
                            Class
                        </th>

                        <th class="border px-3 py-2 text-left">
                            Section
                        </th>

                        <th class="border px-3 py-2 text-center">
                            Students
                        </th>

                        <th class="border px-3 py-2 text-center">
                            Present
                        </th>

                        <th class="border px-3 py-2 text-center">
                            Absent
                        </th>

                        <th class="border px-3 py-2 text-center">
                            Late
                        </th>

                        <th class="border px-3 py-2 text-center">
                            Leave
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="item in attendance.data"
                        :key="item.id"
                    >
                        <td class="border px-3 py-2">
                            {{
                                item.attendance_date
                                    ?.substring(0, 10)
                            }}
                        </td>

                        <td class="border px-3 py-2">
                            {{ item.session?.session_name }}
                        </td>

                        <td class="border px-3 py-2">
                            {{ item.academic_class?.name }}
                        </td>

                        <td class="border px-3 py-2">
                            {{ item.section || "-" }}
                        </td>

                        <td class="border px-3 py-2 text-center">
                            {{ item.records?.length || 0 }}
                        </td>

                        <td class="border px-3 py-2 text-center">
                            {{
                                item.records?.filter(
                                    record =>
                                        record.status ===
                                        "Present"
                                ).length || 0
                            }}
                        </td>

                        <td class="border px-3 py-2 text-center">
                            {{
                                item.records?.filter(
                                    record =>
                                        record.status ===
                                        "Absent"
                                ).length || 0
                            }}
                        </td>

                        <td class="border px-3 py-2 text-center">
                            {{
                                item.records?.filter(
                                    record =>
                                        record.status ===
                                        "Late"
                                ).length || 0
                            }}
                        </td>

                        <td class="border px-3 py-2 text-center">
                            {{
                                item.records?.filter(
                                    record =>
                                        record.status ===
                                        "Leave"
                                ).length || 0
                            }}
                        </td>
                    </tr>

                    <tr
                        v-if="
                            !attendance.data ||
                            !attendance.data.length
                        "
                    >
                        <td
                            colspan="9"
                            class="border px-3 py-10 text-center text-gray-500"
                        >
                            No attendance history found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>