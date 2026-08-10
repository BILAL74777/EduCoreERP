<script setup>
import DatePicker from "@/Components/DatePicker.vue";

const props = defineProps({
    form: Object,
    errors: Object,
});

const clearError = (field) => {
    if (props.errors?.[field]) {
        delete props.errors[field];
    }
};
</script>

<template>
    <div class="space-y-5">
        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Admission Information
            </h2>

            <p class="text-sm text-gray-500">Basic admission details</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-4">
            <!-- Registration No -->
            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Registration No
                </label>

                <input
                    v-model="form.registration_no"
                    @input="clearError('registration_no')"
                    type="text"
                    placeholder="REG-0001"
                    :class="[
                        'w-full rounded-lg border px-3 py-2 dark:bg-gray-800 dark:text-white',
                        errors.registration_no
                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                            : 'border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500',
                    ]"
                />

                <p
                    v-if="errors.registration_no"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.registration_no[0] }}
                </p>
            </div>

            <!-- Admission No -->
            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Admission No
                </label>

                <input
                    v-model="form.admission_no"
                    @input="clearError('admission_no')"
                    type="text"
                    placeholder="ADM-0001"
                    :class="[
                        'w-full rounded-lg border px-3 py-2 dark:bg-gray-800 dark:text-white',
                        errors.admission_no
                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                            : 'border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500',
                    ]"
                />

                <p v-if="errors.admission_no" class="mt-1 text-sm text-red-600">
                    {{ errors.admission_no[0] }}
                </p>
            </div>

            <!-- Admission Date -->
            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Date of Admission
                </label>

                <DatePicker
                    v-model="form.date_of_admission"
                    placeholder="Admission Date"
                    :has-error="Boolean(errors.date_of_admission)"
                    @update:model-value="clearError('date_of_admission')"
                />

                <p
                    v-if="errors.date_of_admission"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ errors.date_of_admission[0] }}
                </p>
            </div>
        </div>
    </div>
</template>
