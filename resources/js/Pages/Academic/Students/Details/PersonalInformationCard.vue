<script setup>
defineProps({
    student: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div
        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">
            Personal Information
        </h3>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            <div>
                <p class="text-sm text-gray-500">Student Name</p>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ student.name || "-" }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Father Name</p>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ student.father_name || "-" }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Father CNIC</p>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ student.father_cnic || "-" }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Father Occupation</p>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ student.father_occupation || "-" }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Gender</p>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ student.gender || "-" }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Date of Birth</p>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ student.date_of_birth || "-" }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Religion</p>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ student.religion || "-" }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tribe / Caste</p>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ student.tribe_cast || "-" }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Contact Number</p>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ student.contact_number || "-" }}
                </p>
            </div>

            <div class="md:col-span-2 xl:col-span-3">
                <p class="text-sm text-gray-500">Address</p>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ student.address || "-" }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Father Shaheed</p>

                <span
                    v-if="student.is_father_shaheed"
                    class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700"
                >
                    Yes
                </span>

                <span
                    v-else
                    class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-200"
                >
                    No
                </span>
            </div>

            <div>
                <p class="text-sm text-gray-500">Orphan</p>

                <span
                    v-if="student.is_orphan"
                    class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700"
                >
                    Yes
                </span>

                <span
                    v-else
                    class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-200"
                >
                    No
                </span>
            </div>
        </div>
    </div>
</template>
