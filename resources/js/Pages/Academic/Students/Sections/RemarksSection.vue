<script setup>
const props = defineProps({
    form: Object,

    errors: Object,
});

function setBooleanValue(field, value) {
    props.form[field] = value;

    if (field === "is_orphan" && value === false) {
        props.form.is_father_shaheed = false;
    }
}
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
            <div class="rounded-lg border p-4 dark:border-gray-700">
                <p
                    class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Orphan Student
                </p>

                <div class="flex gap-4">
                    <label
                        class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300"
                    >
                        <input
                            :checked="form.is_orphan === 1"
                            type="radio"
                            name="is_orphan"
                            @change="setBooleanValue('is_orphan', 1)"
                        />
                        Yes
                    </label>

                    <label
                        class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300"
                    >
                        <input
                            :checked="form.is_orphan === 0"
                            type="radio"
                            name="is_orphan"
                            @change="setBooleanValue('is_orphan', 0)"
                        />
                        No
                    </label>
                </div>
            </div>

            <!-- Father Shaheed -->
            <div
                v-if="form.is_orphan"
                class="rounded-lg border p-4 dark:border-gray-700"
            >
                <p
                    class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Father Shaheed
                </p>

                <div class="flex gap-4">
                    <label
                        class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300"
                    >
                        <input
                            :checked="form.is_father_shaheed === 1"
                            type="radio"
                            name="is_father_shaheed"
                            @change="setBooleanValue('is_father_shaheed', 1)"
                        />
                        Yes
                    </label>

                    <label
                        class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300"
                    >
                        <input
                            :checked="form.is_father_shaheed === 0"
                            type="radio"
                            name="is_father_shaheed"
                            @change="setBooleanValue('is_father_shaheed', 0)"
                        />
                        No
                    </label>
                </div>
            </div>
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
