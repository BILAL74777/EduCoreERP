<script setup>
import { nextTick, onMounted, ref } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";

const scannerInput = ref(null);

const barcode = ref("");
const student = ref(null);

const eventType = ref("IN");

const processing = ref(false);

const history = ref(null);

const focusScanner = async () => {
    await nextTick();

    scannerInput.value?.focus();
};

const scan = async () => {
    const value =
        barcode.value.trim();

    if (!value) {
        return;
    }

    processing.value = true;

    try {
        const response =
            await axios.get(
                "/api/attendance/gate/search",
                {
                    params: {
                        search: value,
                    },
                }
            );

        student.value =
            response.data.student;

        if (!student.value) {
            toast.error(
                "Student not found."
            );

            barcode.value = "";

            await focusScanner();

            return;
        }

        await markAttendance();

    } catch {
        toast.error(
            "Barcode processing failed."
        );
    } finally {
        processing.value = false;

        barcode.value = "";

        await focusScanner();
    }
};

const markAttendance = async () => {
    if (!student.value) {
        return;
    }

    try {
        const response =
            await axios.post(
                "/api/attendance/gate",
                {
                    student_enrollment_id:
                        student.value.id,

                    event_type:
                        eventType.value,

                    method: "BARCODE",
                }
            );

        toast.success(
            `${student.value.student?.name}: ${response.data.message}`
        );

        await loadHistory();

    } catch (error) {
        toast.error(
            error.response?.data?.message ||
                "Unable to mark attendance."
        );
    }
};

const loadHistory = async () => {
    try {
        const response =
            await axios.get(
                "/api/attendance/gate/history"
            );

        history.value =
            response.data.attendance;
    } catch {
        toast.error(
            "Unable to load gate history."
        );
    }
};

onMounted(async () => {
    await loadHistory();
    await focusScanner();
});
</script>

<template>
    <div
        class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <div class="text-center">
            <h2 class="text-2xl font-bold">
                Barcode Attendance
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Scan the student's ID card barcode.
            </p>
        </div>

        <div
            class="mx-auto max-w-xl space-y-4"
        >
            <select
                v-model="eventType"
                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-center text-lg font-semibold dark:border-gray-700 dark:bg-gray-900"
            >
                <option value="IN">
                    MARK IN
                </option>

                <option value="OUT">
                    MARK OUT
                </option>
            </select>

            <input
                ref="scannerInput"
                v-model="barcode"
                @keyup.enter="scan"
                type="text"
                autofocus
                placeholder="Scan barcode..."
                class="w-full rounded-xl border-2 border-blue-500 px-5 py-5 text-center text-xl outline-none dark:bg-gray-900"
            />

            <div
                v-if="processing"
                class="text-center text-gray-500"
            >
                Processing...
            </div>
        </div>

        <div
            v-if="student"
            class="mx-auto max-w-xl rounded-xl border border-gray-200 p-5 text-center dark:border-gray-700"
        >
            <h3 class="text-xl font-bold">
                {{ student.student?.name }}
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                {{ student.student?.admission_no }}
            </p>

            <p class="mt-1 text-sm text-gray-500">
                Card:
                {{ student.student?.card_no }}
            </p>

            <div
                class="mt-4 inline-flex rounded-full px-4 py-2 text-sm font-semibold"
                :class="
                    eventType === 'IN'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-red-100 text-red-700'
                "
            >
                {{ eventType }}
            </div>
        </div>

        <div class="overflow-x-auto">
            <h3 class="mb-3 text-lg font-semibold">
                Today's Scans
            </h3>

            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900">
                        <th class="border px-3 py-2">
                            Student
                        </th>

                        <th class="border px-3 py-2">
                            Event
                        </th>

                        <th class="border px-3 py-2">
                            Method
                        </th>

                        <th class="border px-3 py-2">
                            Time
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="item in history?.data || []"
                        :key="item.id"
                    >
                        <td class="border px-3 py-2">
                            {{
                                item.enrollment?.student?.name
                            }}
                        </td>

                        <td class="border px-3 py-2 text-center">
                            {{ item.event_type }}
                        </td>

                        <td class="border px-3 py-2 text-center">
                            {{ item.method }}
                        </td>

                        <td class="border px-3 py-2 text-center">
                            {{
                                item.event_time
                                    ?.substring(11, 19)
                            }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>