<script setup>
import { computed } from "vue";

const props = defineProps({
    student: Object,
});

const enrollment = computed(() => props.student.current_enrollment ?? {});
const academicClass = computed(() => enrollment.value.academic_class ?? {});
const discipline = computed(() => academicClass.value.discipline ?? {});
const faculty = computed(() => discipline.value.faculty ?? {});
const session = computed(() => enrollment.value.academic_session ?? {});

</script>

<template>
    <div
        class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 h-28"></div>

        <div class="-mt-14 px-8 pb-8">
            <div
                class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between"
            >
                <div class="flex gap-6">
                    <img
                        :src="student.photo?.url ?? '/images/avatar.png'"
                        class="h-40 w-30 rounded-lg border-4 border-white object-cover shadow-lg"
                    />

                    <div class="pt-14">
                        <h2
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ student.name }}
                        </h2>

                        <p class="mt-1 text-gray-500">
                            {{ student.registration_no }}
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span
                                class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700"
                            >
                                {{ faculty.name }}
                            </span>

                            <span
                                class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700"
                            >
                                {{ discipline.name }}
                            </span>

                            <span
                                class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700"
                            >
                                {{ academicClass.name }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 text-sm">
                    <div>
                        <p class="text-gray-500">Admission No</p>

                        <p class="font-semibold dark:text-white">
                            {{ student.admission_no }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Roll No</p>

                        <p class="font-semibold dark:text-white">
                            {{ enrollment.roll_no }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Session</p>

                        <p class="font-semibold dark:text-white">
                            {{ session.session_name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Status</p>

                        <span
                            class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700"
                        >
                            Active
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
