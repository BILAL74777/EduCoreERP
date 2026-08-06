<script setup>
defineProps({
    student: Object,
});
</script>

<template>
    <div
        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">
            Admission Information
        </h3>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Registration No</p>

                <p class="font-medium dark:text-white">
                    {{ student.registration_no }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Admission No</p>

                <p class="font-medium dark:text-white">
                    {{ student.admission_no }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Admission Date</p>

                <p class="font-medium dark:text-white">
                    {{ $formatDate(student.date_of_admission) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Roll No</p>

                <p class="font-medium dark:text-white">
                    {{ student.current_enrollment?.roll_no }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Section</p>

                <p class="font-medium dark:text-white">
                    {{ student.section }}
                </p>
            </div>
        </div>
    </div>
</template>
