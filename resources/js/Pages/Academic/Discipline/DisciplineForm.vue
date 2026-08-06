<script setup>
const props = defineProps({
    form: Object,
    errors: Object,
    faculties: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["clear-error"]);
</script>

<template>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <!-- Faculty -->
        <div>
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Faculty
            </label>

            <select
                v-model="form.faculty_id"
                @change="emit('clear-error', 'faculty_id')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20':
                        errors.faculty_id,
                }"
            >
                <option value="">Select Faculty</option>

                <option
                    v-for="faculty in faculties"
                    :key="faculty.id"
                    :value="faculty.id"
                >
                    {{ faculty.faculty }}
                    <!-- eg. Faculty of Arts || Facutly Of Science -->
                </option>
            </select>

            <p v-if="errors.faculty_id" class="mt-1 text-sm text-red-600">
                {{ errors.faculty_id[0] }}
            </p>
        </div>

         

        <!-- Discipline -->
        <div>
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Discipline
            </label>

            <input
                v-model="form.discipline"
                type="text"
                placeholder="Pre-Medical"
                @input="emit('clear-error', 'discipline')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20': errors.discipline,
                }"
            />

            <p v-if="errors.discipline" class="mt-1 text-sm text-red-600">
                {{ errors.discipline[0] }}
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
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
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
