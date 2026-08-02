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
                    {{ faculty.name }}
                </option>
            </select>

            <p v-if="errors.faculty_id" class="mt-1 text-sm text-red-600">
                {{ errors.faculty_id[0] }}
            </p>
        </div>

        <!-- Code -->
        <div>
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Code
            </label>

            <input
                v-model="form.code"
                type="text"
                placeholder="BSCS"
                @input="emit('clear-error', 'code')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20': errors.code,
                }"
            />

            <p v-if="errors.code" class="mt-1 text-sm text-red-600">
                {{ errors.code[0] }}
            </p>
        </div>

        <!-- Discipline Name -->
        <div>
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Discipline Name
            </label>

            <input
                v-model="form.name"
                type="text"
                placeholder="Bachelor of Computer Science"
                @input="emit('clear-error', 'name')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20': errors.name,
                }"
            />

            <p v-if="errors.name" class="mt-1 text-sm text-red-600">
                {{ errors.name[0] }}
            </p>
        </div>

        <!-- Duration -->
        <div>
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Duration (Years)
            </label>

            <input
                v-model="form.duration_years"
                type="number"
                min="1"
                max="10"
                @input="emit('clear-error', 'duration_years')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20':
                        errors.duration_years,
                }"
            />

            <p v-if="errors.duration_years" class="mt-1 text-sm text-red-600">
                {{ errors.duration_years[0] }}
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

        <!-- Description -->
        <div class="md:col-span-2">
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Description
            </label>

            <textarea
                v-model="form.description"
                rows="4"
                placeholder="Enter discipline description..."
                @input="emit('clear-error', 'description')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20':
                        errors.description,
                }"
            ></textarea>

            <p v-if="errors.description" class="mt-1 text-sm text-red-600">
                {{ errors.description[0] }}
            </p>
        </div>
    </div>
</template>
