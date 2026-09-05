<script setup>
import { ref } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";

const search = ref("");
const students = ref([]);
const selectedStudent = ref(null);

const attendance = ref(null);
const gate = ref(null);

const loading = ref(false);

const searchStudents = async () => {
    if (!search.value.trim()) {
        students.value = [];
        return;
    }

    try {
        const response =
            await axios.get(
                "/api/attendance/students/search",
                {
                    params: {
                        search:
                            search.value,
                    },
                }
            );

        students.value =
            response.data.students || [];
    } catch {
        toast.error(
            "Unable to search students."
        );
    }
};

const selectStudent = async (student) => {
    selectedStudent.value = student;
    students.value = [];

    await loadStudentHistory();
};

const loadStudentHistory = async () => {
    if (!selectedStudent.value) {
        return;
    }

    loading.value = true;

    try {
        const [
            attendanceResponse,
            gateResponse,
        ] = await Promise.all([
            axios.get(
                `/api/attendance/students/${selectedStudent.value.id}/history`,
                {
                    params: {
                        per_page: 50,
                    },
                }
            ),

            axios.get(
                `/api/attendance/gate/students/${selectedStudent.value.id}/history`,
                {
                    params: {
                        per_page: 50,
                    },
                }
            ),
        ]);

        attendance.value =
            attendanceResponse.data.attendance;

        gate.value =
            gateResponse.data.attendance;
    } catch {
        toast.error(
            "Unable to load student attendance."
        );
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <div
        class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <!-- Search -->

        <div class="relative">
            <label
                class="mb-1 block text-sm font-medium"
            >
                Search Student
            </label>

            <input
                v-model="search"
                @input="searchStudents"
                type="text"
                placeholder="Name, admission no, registration no or card no"
                class="w-full rounded-lg border border-gray-300 px-4 py-3 dark:border-gray-700 dark:bg-gray-900"
            />

            <div
                v-if="students.length"
                class="absolute z-20 mt-1 max-h-72 w-full overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900"
            >
                <button
                    v-for="student in students"
                    :key="student.id"
                    type="button"
                    @click="selectStudent(student)"
                    class="flex w-full items-center justify-between px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-gray-800"
                >
                    <div>
                        <div class="font-medium">
                            {{ student.name }}
                        </div>

                        <div class="text-xs text-gray-500">
                            Admission:
                            {{ student.admission_no }}
                        </div>
                    </div>

                    <div class="text-xs text-gray-500">
                        {{ student.card_no }}
                    </div>
                </button>
            </div>
        </div>

        <!-- Student Profile -->

        <div
            v-if="selectedStudent"
            class="rounded-xl border border-gray-200 p-5 dark:border-gray-700"
        >
            <div
                class="grid grid-cols-1 gap-5 md:grid-cols-[100px_1fr]"
            >
                <div
                    class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800"
                >
                    <img
                        v-if="
                            selectedStudent.photo?.url
                        "
                        :src="
                            selectedStudent.photo.url
                        "
                        class="h-full w-full object-cover"
                    />

                    <span
                        v-else
                        class="text-2xl font-bold text-gray-400"
                    >
                        {{
                            selectedStudent.name
                                ?.charAt(0)
                                ?.toUpperCase()
                        }}
                    </span>
                </div>

                <div>
                    <h2 class="text-xl font-bold">
                        {{ selectedStudent.name }}
                    </h2>

                    <div
                        class="mt-2 grid grid-cols-1 gap-2 text-sm md:grid-cols-3"
                    >
                        <div>
                            <span class="text-gray-500">
                                Admission:
                            </span>

                            {{ selectedStudent.admission_no }}
                        </div>

                        <div>
                            <span class="text-gray-500">
                                Registration:
                            </span>

                            {{
                                selectedStudent.registration_no ||
                                "-"
                            }}
                        </div>

                        <div>
                            <span class="text-gray-500">
                                Card:
                            </span>

                            {{
                                selectedStudent.card_no ||
                                "-"
                            }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="loading"
            class="py-10 text-center text-gray-500"
        >
            Loading student history...
        </div>

        <template v-else-if="attendance">

            <!-- Summary -->

            <div>
                <h3 class="mb-3 text-lg font-semibold">
                    Classroom Attendance
                </h3>

                <div
                    class="grid grid-cols-2 gap-3 md:grid-cols-6"
                >
                    <div class="rounded-lg bg-gray-100 p-4 dark:bg-gray-900">
                        <div class="text-xs text-gray-500">
                            Total
                        </div>

                        <div class="mt-1 text-xl font-bold">
                            {{
                                attendance.summary.total
                            }}
                        </div>
                    </div>

                    <div class="rounded-lg bg-green-50 p-4 dark:bg-green-950">
                        <div class="text-xs text-gray-500">
                            Present
                        </div>

                        <div class="mt-1 text-xl font-bold">
                            {{
                                attendance.summary.present
                            }}
                        </div>
                    </div>

                    <div class="rounded-lg bg-red-50 p-4 dark:bg-red-950">
                        <div class="text-xs text-gray-500">
                            Absent
                        </div>

                        <div class="mt-1 text-xl font-bold">
                            {{
                                attendance.summary.absent
                            }}
                        </div>
                    </div>

                    <div class="rounded-lg bg-yellow-50 p-4 dark:bg-yellow-950">
                        <div class="text-xs text-gray-500">
                            Late
                        </div>

                        <div class="mt-1 text-xl font-bold">
                            {{
                                attendance.summary.late
                            }}
                        </div>
                    </div>

                    <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-950">
                        <div class="text-xs text-gray-500">
                            Leave
                        </div>

                        <div class="mt-1 text-xl font-bold">
                            {{
                                attendance.summary.leave
                            }}
                        </div>
                    </div>

                    <div class="rounded-lg bg-purple-50 p-4 dark:bg-purple-950">
                        <div class="text-xs text-gray-500">
                            Percentage
                        </div>

                        <div class="mt-1 text-xl font-bold">
                            {{
                                attendance.summary.percentage
                            }}%
                        </div>
                    </div>
                </div>
            </div>

            <!-- Classroom history -->

            <div class="overflow-x-auto">
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

                            <th class="border px-3 py-2 text-center">
                                Status
                            </th>

                            <th class="border px-3 py-2 text-left">
                                Remarks
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="record in attendance.history.data"
                            :key="record.id"
                        >
                            <td class="border px-3 py-2">
                                {{
                                    record.attendance_session
                                        ?.attendance_date
                                        ?.substring(0, 10)
                                }}
                            </td>

                            <td class="border px-3 py-2">
                                {{
                                    record.attendance_session
                                        ?.session
                                        ?.session_name
                                }}
                            </td>

                            <td class="border px-3 py-2">
                                {{
                                    record.attendance_session
                                        ?.academic_class
                                        ?.name
                                }}
                            </td>

                            <td class="border px-3 py-2 text-center">
                                <span
                                    class="rounded-full px-3 py-1 text-xs font-medium"
                                    :class="{
                                        'bg-green-100 text-green-700':
                                            record.status === 'Present',

                                        'bg-red-100 text-red-700':
                                            record.status === 'Absent',

                                        'bg-yellow-100 text-yellow-700':
                                            record.status === 'Late',

                                        'bg-blue-100 text-blue-700':
                                            record.status === 'Leave',
                                    }"
                                >
                                    {{ record.status }}
                                </span>
                            </td>

                            <td class="border px-3 py-2">
                                {{
                                    record.remarks ||
                                    "-"
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </template>

        <!-- Gate History -->

        <div
            v-if="gate"
            class="space-y-4"
        >
            <h3 class="text-lg font-semibold">
                Gate Movement History
            </h3>

            <div
                class="grid grid-cols-2 gap-3 md:grid-cols-3"
            >
                <div class="rounded-lg bg-gray-100 p-4 dark:bg-gray-900">
                    <div class="text-xs text-gray-500">
                        Total Events
                    </div>

                    <div class="mt-1 text-xl font-bold">
                        {{ gate.summary.total_events }}
                    </div>
                </div>

                <div class="rounded-lg bg-green-50 p-4 dark:bg-green-950">
                    <div class="text-xs text-gray-500">
                        IN
                    </div>

                    <div class="mt-1 text-xl font-bold">
                        {{ gate.summary.in }}
                    </div>
                </div>

                <div class="rounded-lg bg-red-50 p-4 dark:bg-red-950">
                    <div class="text-xs text-gray-500">
                        OUT
                    </div>

                    <div class="mt-1 text-xl font-bold">
                        {{ gate.summary.out }}
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900">
                            <th class="border px-3 py-2 text-left">
                                Date
                            </th>

                            <th class="border px-3 py-2 text-left">
                                Time
                            </th>

                            <th class="border px-3 py-2 text-center">
                                Event
                            </th>

                            <th class="border px-3 py-2 text-center">
                                Method
                            </th>

                            <th class="border px-3 py-2 text-left">
                                Gate
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="record in gate.history.data"
                            :key="record.id"
                        >
                            <td class="border px-3 py-2">
                                {{
                                    record.event_time
                                        ?.substring(0, 10)
                                }}
                            </td>

                            <td class="border px-3 py-2">
                                {{
                                    record.event_time
                                        ?.substring(11, 19)
                                }}
                            </td>

                            <td class="border px-3 py-2 text-center">
                                <span
                                    class="rounded-full px-3 py-1 text-xs font-medium"
                                    :class="
                                        record.event_type === 'IN'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-red-100 text-red-700'
                                    "
                                >
                                    {{ record.event_type }}
                                </span>
                            </td>

                            <td class="border px-3 py-2 text-center">
                                {{ record.method }}
                            </td>

                            <td class="border px-3 py-2">
                                {{ record.gate_id || "-" }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>
