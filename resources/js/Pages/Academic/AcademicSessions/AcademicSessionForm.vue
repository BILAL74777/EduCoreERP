<script setup>
const props = defineProps({
    form: Object,
    errors: Object,
    editing: Boolean,
});

const emit = defineEmits(["clear-error"]);
</script>

<template>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <!-- Session Name -->
        <div class="md:col-span-2">
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Session Name
            </label>

            <input
                v-model="form.session_name"
                type="text"
                placeholder="2025 - 2026"
                @input="emit('clear-error', 'session_name')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20':
                        errors.session_name,
                }"
            />

            <p v-if="errors.session_name" class="mt-1 text-sm text-red-600">
                {{ errors.session_name[0] }}
            </p>
        </div>

        <!-- Start Date -->
        <div>
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Start Date
            </label>

            <input
                v-model="form.start_date"
                type="date"
                @input="emit('clear-error', 'start_date')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20':
                        errors.start_date,
                }"
            />

            <p v-if="errors.start_date" class="mt-1 text-sm text-red-600">
                {{ errors.start_date[0] }}
            </p>
        </div>

        <!-- End Date -->
        <div>
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                End Date
            </label>

            <input
                v-model="form.end_date"
                type="date"
                @input="emit('clear-error', 'end_date')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20':
                        errors.end_date,
                }"
            />

            <p v-if="errors.end_date" class="mt-1 text-sm text-red-600">
                {{ errors.end_date[0] }}
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

        <!-- Remarks -->
        <div class="md:col-span-2">
            <label
                class="mb-2 block font-medium text-gray-700 dark:text-gray-300"
            >
                Remarks
            </label>

            <textarea
                v-model="form.remarks"
                rows="4"
                placeholder="Enter remarks..."
                @input="emit('clear-error', 'remarks')"
                class="w-full rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                :class="{
                    'border-red-500 bg-red-50 dark:bg-red-900/20':
                        errors.remarks,
                }"
            ></textarea>

            <p v-if="errors.remarks" class="mt-1 text-sm text-red-600">
                {{ errors.remarks[0] }}
            </p>
        </div>
    </div>
</template>
