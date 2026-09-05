<script setup>
import { computed, onMounted, ref } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";

const sessions = ref([]);
const classes = ref([]);
const students = ref([]);

const selectedSession = ref("");
const selectedClass = ref("");
const section = ref("");
const attendanceDate = ref(
    new Date().toISOString().split("T")[0]
);

const loading = ref(false);
const saving = ref(false);

const remarks = ref("");

const statusOptions = [
    "Present",
    "Absent",
    "Late",
    "Leave",
];

const totalStudents = computed(
    () => students.value.length
);

const presentCount = computed(
    () =>
        students.value.filter(
            (student) =>
                student.status === "Present"
        ).length
);

const absentCount = computed(
    () =>
        students.value.filter(
            (student) =>
                student.status === "Absent"
        ).length
);

const lateCount = computed(
    () =>
        students.value.filter(
            (student) =>
                student.status === "Late"
        ).length
);

const leaveCount = computed(
    () =>
        students.value.filter(
            (student) =>
                student.status === "Leave"
        ).length
);

const attendancePercentage = computed(() => {
    if (!totalStudents.value) {
        return 0;
    }

    return Math.round(
        (
            (presentCount.value +
                lateCount.value) /
            totalStudents.value
        ) * 100
    );
});

const loadInitialData = async () => {
    try {
        const response =
            await axios.get("/api/attendance");

        sessions.value =
            response.data.sessions || [];

        classes.value =
            response.data.classes || [];

        const activeSession =
            sessions.value.find(
                (session) =>
                    session.is_active
            );

        if (activeSession) {
            selectedSession.value =
                activeSession.id;
        }
    } catch (error) {
        toast.error(
            "Unable to load attendance data."
        );
    }
};

const loadStudents = async () => {
    if (
        !selectedSession.value ||
        !selectedClass.value
    ) {
        students.value = [];
        return;
    }

    loading.value = true;

    try {
        const response =
            await axios.get(
                "/api/attendance/sheet",
                {
                    params: {
                        session_id:
                            selectedSession.value,

                        class_id:
                            selectedClass.value,

                        section:
                            section.value || null,

                        date:
                            attendanceDate.value,
                    },
                }
            );

        students.value =
            response.data.students || [];
    } catch (error) {
        students.value = [];

        toast.error(
            error.response?.data?.message ||
                "Unable to load students."
        );
    } finally {
        loading.value = false;
    }
};

const markAll = (status) => {
    students.value.forEach(
        (student) => {
            student.status = status;
        }
    );
};

const saveAttendance = async () => {
    if (
        !selectedSession.value ||
        !selectedClass.value
    ) {
        toast.error(
            "Please select session and class."
        );

        return;
    }

    if (!students.value.length) {
        toast.error(
            "No students found."
        );

        return;
    }

    saving.value = true;

    try {
        const response =
            await axios.post(
                "/api/attendance",
                {
                    session_id:
                        selectedSession.value,

                    class_id:
                        selectedClass.value,

                    section:
                        section.value || null,

                    attendance_date:
                        attendanceDate.value,

                    remarks:
                        remarks.value || null,

                    records:
                        students.value.map(
                            (student) => ({
                                student_enrollment_id:
                                    student.id,

                                status:
                                    student.status,

                                remarks:
                                    student.remarks ||
                                    null,
                            })
                        ),
                }
            );

        toast.success(
            response.data.message ||
                "Attendance saved successfully."
        );

        await loadStudents();
    } catch (error) {
        toast.error(
            error.response?.data?.message ||
                "Unable to save attendance."
        );
    } finally {
        saving.value = false;
    }
};

onMounted(loadInitialData);
</script>

<template>
    <div
        class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <!-- Filters -->

        <div
            class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4"
        >
            <div>
                <label class="mb-1 block text-sm font-medium">
                    Academic Session
                </label>

                <select
                    v-model="selectedSession"
                    @change="loadStudents"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
                >
                    <option value="">
                        Select Session
                    </option>

                    <option
                        v-for="session in sessions"
                        :key="session.id"
                        :value="session.id"
                    >
                        {{ session.session_name }}
                    </option>
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">
                    Class
                </label>

                <select
                    v-model="selectedClass"
                    @change="loadStudents"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
                >
                    <option value="">
                        Select Class
                    </option>

                    <option
                        v-for="item in classes"
                        :key="item.id"
                        :value="item.id"
                    >
                        {{ item.name }}
                    </option>
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">
                    Section
                </label>

                <input
                    v-model="section"
                    @change="loadStudents"
                    type="text"
                    placeholder="Optional"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
                />
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">
                    Attendance Date
                </label>

                <input
                    v-model="attendanceDate"
                    @change="loadStudents"
                    type="date"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
                />
            </div>
        </div>

        <!-- Summary -->

        <div
            class="grid grid-cols-2 gap-3 md:grid-cols-5"
        >
            <div class="rounded-lg bg-gray-100 p-4 dark:bg-gray-900">
                <div class="text-xs text-gray-500">
                    Total
                </div>

                <div class="mt-1 text-xl font-bold">
                    {{ totalStudents }}
                </div>
            </div>

            <div class="rounded-lg bg-green-50 p-4 dark:bg-green-950">
                <div class="text-xs text-gray-500">
                    Present
                </div>

                <div class="mt-1 text-xl font-bold">
                    {{ presentCount }}
                </div>
            </div>

            <div class="rounded-lg bg-red-50 p-4 dark:bg-red-950">
                <div class="text-xs text-gray-500">
                    Absent
                </div>

                <div class="mt-1 text-xl font-bold">
                    {{ absentCount }}
                </div>
            </div>

            <div class="rounded-lg bg-yellow-50 p-4 dark:bg-yellow-950">
                <div class="text-xs text-gray-500">
                    Late
                </div>

                <div class="mt-1 text-xl font-bold">
                    {{ lateCount }}
                </div>
            </div>

            <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-950">
                <div class="text-xs text-gray-500">
                    Attendance
                </div>

                <div class="mt-1 text-xl font-bold">
                    {{ attendancePercentage }}%
                </div>
            </div>
        </div>

        <!-- Actions -->

        <div
            class="flex flex-wrap gap-2"
        >
            <button
                type="button"
                @click="markAll('Present')"
                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700"
            >
                Mark All Present
            </button>

            <button
                type="button"
                @click="markAll('Absent')"
                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
            >
                Mark All Absent
            </button>
        </div>

        <!-- Loading -->

        <div
            v-if="loading"
            class="py-10 text-center text-gray-500"
        >
            Loading students...
        </div>

        <!-- Table -->

        <div
            v-else
            class="overflow-x-auto"
        >
            <table
                class="w-full border-collapse text-sm"
            >
                <thead>
                    <tr
                        class="bg-gray-50 dark:bg-gray-900"
                    >
                        <th
                            class="border px-3 py-2 text-left"
                        >
                            #
                        </th>

                        <th
                            class="border px-3 py-2 text-left"
                        >
                            Roll No
                        </th>

                        <th
                            class="border px-3 py-2 text-left"
                        >
                            Student
                        </th>

                        <th
                            class="border px-3 py-2 text-center"
                        >
                            Status
                        </th>

                        <th
                            class="border px-3 py-2"
                        >
                            Remarks
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="(student, index) in students"
                        :key="student.id"
                    >
                        <td class="border px-3 py-2">
                            {{ index + 1 }}
                        </td>

                        <td class="border px-3 py-2">
                            {{ student.roll_no || "-" }}
                        </td>

                        <td class="border px-3 py-2">
                            <div class="font-medium">
                                {{ student.student?.name }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{
                                    student.student
                                        ?.admission_no
                                }}
                            </div>
                        </td>

                        <td class="border px-3 py-2">
                            <select
                                v-model="student.status"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
                            >
                                <option
                                    v-for="status in statusOptions"
                                    :key="status"
                                    :value="status"
                                >
                                    {{ status }}
                                </option>
                            </select>
                        </td>

                        <td class="border px-3 py-2">
                            <input
                                v-model="student.remarks"
                                type="text"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
                            />
                        </td>
                    </tr>

                    <tr v-if="!students.length">
                        <td
                            colspan="5"
                            class="border px-3 py-10 text-center text-gray-500"
                        >
                            Select session and class to load students.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Remarks -->

        <textarea
            v-model="remarks"
            rows="2"
            placeholder="Attendance remarks..."
            class="w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"
        />

        <!-- Save -->

        <div class="flex justify-end">
            <button
                type="button"
                :disabled="saving"
                @click="saveAttendance"
                class="rounded-lg bg-blue-600 px-6 py-2.5 font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
            >
                {{
                    saving
                        ? "Saving..."
                        : "Save Attendance"
                }}
            </button>
        </div>
    </div>
</template>