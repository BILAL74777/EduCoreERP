<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";

const search = ref("");
const student = ref(null);

const eventType = ref("IN");

const history = ref(null);

const loading = ref(false);
const marking = ref(false);

const searchStudent = async () => {
    if (!search.value.trim()) {
        student.value = null;
        return;
    }

    loading.value = true;

    try {
        const response =
            await axios.get(
                "/api/attendance/gate/search",
                {
                    params: {
                        search:
                            search.value,
                    },
                }
            );

        student.value =
            response.data.student;

        if (!student.value) {
            toast.warning(
                "Student not found."
            );
        }
    } catch {
        toast.error(
            "Unable to search student."
        );
    } finally {
        loading.value = false;
    }
};

const markAttendance = async () => {
    if (!student.value) {
        toast.error(
            "Please search a student first."
        );

        return;
    }

    marking.value = true;

    try {
        const response =
            await axios.post(
                "/api/attendance/gate",
                {
                    student_enrollment_id:
                        student.value.id,

                    event_type:
                        eventType.value,

                    method: "MANUAL",
                }
            );

        toast.success(
            response.data.message
        );

        await loadHistory();
    } catch (error) {
        toast.error(
            error.response?.data?.message ||
                "Unable to mark attendance."
        );
    } finally {
        marking.value = false;
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

onMounted(loadHistory);
</script>

<template>
    <div
        class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <div
            class="grid grid-cols-1 gap-4 lg:grid-cols-[1fr_180px_auto]"
        >
            <input
                v-model="search"
                @keyup.enter="searchStudent"
                type="text"
                placeholder="Admission / Registration / Card No"
                class="rounded-lg border border-gray-300 px-4 py-3 dark:border-gray-700 dark:bg-gray-900"
            />

            <select
                v-model="eventType"
                class="rounded-lg border border-gray-300 px-4 py-3 dark:border-gray-700 dark:bg-gray-900"
            >
                <option value="IN">
                    MARK IN
                </option>

                <option value="OUT">
                    MARK OUT
                </option>
            </select>

            <button
                type="button"
                @click="searchStudent"
                class="rounded-lg bg-blue-600 px-5 py-3 font-medium text-white hover:bg-blue-700"
            >
                Search
            </button>
        </div>

        <div
            v-if="student"
            class="rounded-xl border border-gray-200 p-5 dark:border-gray-700"
        >
            <div
                class="grid grid-cols-1 gap-5 md:grid-cols-[100px_1fr]"
            >
                <div
                    class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-xl bg-gray-100"
                >
                    <img
                        v-if="student.student?.photo?.url"
                        :src="student.student.photo.url"
                        class="h-full w-full object-cover"
                    />

                    <span
                        v-else
                        class="text-2xl font-bold text-gray-400"
                    >
                        {{
                            student.student?.name
                                ?.charAt(0)
                        }}
                    </span>
                </div>

                <div>
                    <h2 class="text-xl font-bold">
                        {{ student.student?.name }}
                    </h2>

                    <div class="mt-2 space-y-1 text-sm">
                        <div>
                            Admission:
                            {{ student.student?.admission_no }}
                        </div>

                        <div>
                            Registration:
                            {{
                                student.student
                                    ?.registration_no ||
                                "-"
                            }}
                        </div>

                        <div>
                            Card:
                            {{
                                student.student?.card_no ||
                                "-"
                            }}
                        </div>

                        <div>
                            Class:
                            {{
                                student.academic_class?.name ||
                                "-"
                            }}
                        </div>
                    </div>

                    <button
                        type="button"
                        :disabled="marking"
                        @click="markAttendance"
                        class="mt-4 rounded-lg bg-green-600 px-6 py-2.5 font-medium text-white hover:bg-green-700 disabled:opacity-50"
                    >
                        {{
                            marking
                                ? "Processing..."
                                : `MARK ${eventType}`
                        }}
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <h3 class="mb-3 text-lg font-semibold">
                Today's Gate History
            </h3>

            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900">
                        <th class="border px-3 py-2 text-left">
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