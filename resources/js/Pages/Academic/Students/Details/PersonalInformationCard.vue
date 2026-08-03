<script setup>
defineProps({
    student: Object,
});
</script>

<template>
    <div
        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <h3
            class="mb-6 text-lg font-semibold text-gray-900 dark:text-white"
        >
            Personal Information
        </h3>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">
                    Student Name
                </p>

                <p class="font-medium dark:text-white">
                    {{ student.name }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Father Name
                </p>

                <p class="font-medium dark:text-white">
                    {{ student.father_name }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    CNIC
                </p>

                <p class="font-medium dark:text-white">
                    {{ student.father_cnic }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Occupation
                </p>

                <p class="font-medium dark:text-white">
                    {{ student.father_occupation }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Gender
                </p>

                <p class="font-medium dark:text-white">
                    {{ student.gender }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Date of Birth
                </p>

                <p class="font-medium dark:text-white">
                    {{ student.date_of_birth }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Religion
                </p>

                <p class="font-medium dark:text-white">
                    {{ student.religion }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Tribe / Cast
                </p>

                <p class="font-medium dark:text-white">
                    {{ student.tribe_cast }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Contact Number
                </p>

                <p class="font-medium dark:text-white">
                    {{ student.contact_number }}
                </p>
            </div>

            <div class="col-span-2">
                <p class="text-sm text-gray-500">
                    Address
                </p>

                <p class="font-medium dark:text-white">
                    {{ student.address }}
                </p>
            </div>
        </div>
    </div>
</template>