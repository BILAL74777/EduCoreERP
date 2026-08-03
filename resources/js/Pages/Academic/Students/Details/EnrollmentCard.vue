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
        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">
            Enrollment Information
        </h3>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Faculty</p>
                <p class="font-medium dark:text-white">
                    {{ faculty.name ?? "-" }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Discipline</p>
                <p class="font-medium dark:text-white">
                    {{ discipline.name ?? "-" }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Class</p>
                <p class="font-medium dark:text-white">
                    {{ academicClass.name ?? "-" }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Session</p>
                <p class="font-medium dark:text-white">
                    {{ session.session_name ?? "-" }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Roll No</p>
                <p class="font-medium dark:text-white">
                    {{ enrollment.roll_no ?? "-" }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Enrollment Date</p>
                <p class="font-medium dark:text-white">
                    {{ enrollment.created_at ?? "-" }}
                </p>
            </div>
        </div>
    </div>
</template>
