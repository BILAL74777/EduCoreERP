<script setup>
const props = defineProps({
    form: Object,

    errors: Object,
});
</script>

<template>
    <div class="space-y-5">
        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Remarks
            </h2>

            <p class="text-sm text-gray-500">Additional student information</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            <!-- Orphan -->

            <label
                class="flex items-center gap-3 rounded-lg border p-4 dark:border-gray-700"
            >
                <input
                    v-model="form.is_orphan"
                    type="checkbox"
                    class="h-4 w-4"
                />

                <span class="text-sm text-gray-700 dark:text-gray-300">
                    Orphan Student
                </span>
            </label>

            <!-- Father Shaheed -->

            <label
                class="flex items-center gap-3 rounded-lg border p-4 dark:border-gray-700"
            >
                <input
                    v-model="form.is_father_shaheed"
                    type="checkbox"
                    class="h-4 w-4"
                />

                <span class="text-sm text-gray-700 dark:text-gray-300">
                    Father Shaheed
                </span>
            </label>
        </div>

        <div>
            <label
                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
            >
                Remarks
            </label>

            <textarea
                v-model="form.remarks"
                rows="4"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                placeholder="Any additional remarks..."
            ></textarea>
        </div>
    </div>
</template>
