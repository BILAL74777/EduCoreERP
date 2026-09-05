<script setup>
import Master from "@/Layouts/Master.vue";
import { Head } from "@inertiajs/vue3";

import ClassroomAttendance from "./ClassroomAttendance.vue";
import AttendanceHistory from "./AttendanceHistory.vue";
import StudentAttendance from "./StudentAttendance.vue";
import GateAttendance from "./GateAttendance.vue";
import BarcodeAttendance from "./BarcodeAttendance.vue";

import { ref } from "vue";

const activeTab = ref("classroom");

const tabs = [
    {
        key: "classroom",
        label: "Classroom Attendance",
    },
    {
        key: "history",
        label: "Attendance History",
    },
    {
        key: "student",
        label: "Student Report",
    },
    {
        key: "gate",
        label: "Gate Attendance",
    },
    {
        key: "barcode",
        label: "Barcode Scanner",
    },
];
</script>

<template>
    <Head title="Attendance" />

    <Master>
        <div class="space-y-6">

            <div>
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white"
                >
                    Attendance
                </h1>

                <p
                    class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                >
                    Manage classroom attendance, student history and
                    gate attendance.
                </p>
            </div>

            <div
                class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
            >
                <div class="flex min-w-max">
                    <button
                        v-for="tab in tabs"
                        :key="tab.key"
                        type="button"
                        @click="activeTab = tab.key"
                        class="border-b-2 px-5 py-3 text-sm font-medium transition"
                        :class="
                            activeTab === tab.key
                                ? 'border-blue-600 text-blue-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white'
                        "
                    >
                        {{ tab.label }}
                    </button>
                </div>
            </div>

            <ClassroomAttendance
                v-if="activeTab === 'classroom'"
            />

            <AttendanceHistory
                v-if="activeTab === 'history'"
            />

            <StudentAttendance
                v-if="activeTab === 'student'"
            />

            <GateAttendance
                v-if="activeTab === 'gate'"
            />

            <BarcodeAttendance
                v-if="activeTab === 'barcode'"
            />

        </div>
    </Master>
</template>
