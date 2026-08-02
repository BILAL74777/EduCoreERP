<script setup>
const props = defineProps({
    form: Object,
    errors: Object,
    disciplines: {
        type: Array,
        default: () => [],
    },
    nextClasses: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["clear-error"]);
</script>

<template>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <!-- Discipline -->
        <div>
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Discipline
            </label>

            <select
                v-model="form.discipline_id"
                @change="emit('clear-error', 'discipline_id')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20':
                        errors.discipline_id,
                }"
            >
                <option value="">Select Discipline</option>

                <option
                    v-for="discipline in disciplines"
                    :key="discipline.id"
                    :value="discipline.id"
                >
                    {{ discipline.name }}
                </option>
            </select>

            <p v-if="errors.discipline_id" class="mt-1 text-sm text-red-600">
                {{ errors.discipline_id[0] }}
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
                placeholder="BSCS-1"
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

        <!-- Class Name -->
        <div>
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Class Name
            </label>

            <input
                v-model="form.name"
                type="text"
                placeholder="First Year"
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

        <!-- Level -->
        <div>
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Level
            </label>

            <input
                v-model="form.level"
                type="number"
                min="1"
                @input="emit('clear-error', 'level')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20': errors.level,
                }"
            />

            <p v-if="errors.level" class="mt-1 text-sm text-red-600">
                {{ errors.level[0] }}
            </p>
        </div>

        <!-- Next Class -->
        <div>
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Next Class
            </label>

            <select
                v-model="form.next_class_id"
                @change="emit('clear-error', 'next_class_id')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20':
                        errors.next_class_id,
                }"
            >
                <option value="">None</option>

                <option
                    v-for="item in nextClasses"
                    :key="item.id"
                    :value="item.id"
                    :disabled="item.id === form.id"
                >
                    {{ item.name }}
                </option>
            </select>

            <p v-if="errors.next_class_id" class="mt-1 text-sm text-red-600">
                {{ errors.next_class_id[0] }}
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
                placeholder="Enter class description..."
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
