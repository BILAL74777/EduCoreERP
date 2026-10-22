<script setup>
import Master from "@/Layouts/Master.vue";
import { Head } from "@inertiajs/vue3";

import ClassroomAttendance from "./ClassroomAttendance.vue";
import AttendanceHistory from "./AttendanceHistory.vue";
import StudentAttendance from "./StudentAttendance.vue";
import GateAttendance from "./GateAttendance.vue";
import BarcodeAttendance from "./BarcodeAttendance.vue";
import StudentIdCards from "./StudentIdCards.vue";

import { ref } from "vue";

const params = new URLSearchParams(window.location.search);
const requested = params.get("tab");
const dateParam = params.get("date");

const activeTab = ref(requested || "classroom");
const initialDate = ref(dateParam || new Date().toISOString().split("T")[0]);
</script>

<template>
    <Head title="Attendance" />

    <Master>
        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Attendance
                </h1>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Manage classroom attendance, student history and gate
                    attendance.
                </p>
            </div>

            <!-- navigation handled by sidebar; active tab selected via URL `?tab=` -->

            <ClassroomAttendance
                v-if="activeTab === 'classroom'"
                :initial-date="initialDate"
            />

            <AttendanceHistory v-if="activeTab === 'history'" />

            <StudentAttendance v-if="activeTab === 'student'" />

            <GateAttendance v-if="activeTab === 'gate'" />

            <BarcodeAttendance v-if="activeTab === 'barcode'" />

            <StudentIdCards v-if="activeTab === 'idcards'" />
        </div>
    </Master>
</template>
