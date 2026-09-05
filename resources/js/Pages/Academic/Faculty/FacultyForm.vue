<script setup>
const props = defineProps({
    form: Object,
    errors: Object,
});

const emit = defineEmits(["clear-error"]);
</script>

<template>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <!-- Faculty ID -->
        <input v-model="form.id" type="hidden" />

        <!-- Faculty -->
        <div>
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Faculty
            </label>

            <input
                v-model="form.faculty"
                type="text"
                placeholder="Faculty of Science"
                @input="emit('clear-error', 'faculty')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20':
                        errors.faculty,
                }"
            />

            <p v-if="errors.faculty" class="mt-1 text-sm text-red-600">
                {{ errors.faculty[0] }}
            </p>
        </div>

        <!-- Status -->
        <div>
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Status
            </label>

            <select
                v-model="form.is_active"
                @change="emit('clear-error', 'is_active')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20':
                        errors.is_active,
                }"
            >
                <option :value="true">Active</option>
                <option :value="false">Inactive</option>
            </select>

            <p v-if="errors.is_active" class="mt-1 text-sm text-red-600">
                {{ errors.is_active[0] }}
            </p>
        </div>
    </div>
</template> 