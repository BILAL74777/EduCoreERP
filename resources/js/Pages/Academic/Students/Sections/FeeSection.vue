<script setup>
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
        <!-- Header -->
        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Fee Information
            </h2>

            <p class="text-sm text-gray-500">Admission time decided fee</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            <!-- Annual Fee -->
            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Annual Fee
                </label>

                <input
                    v-model="form.annual_fee"
                    @input="clearError('annual_fee')"
                    type="number"
                    min="0"
                    :class="[
                        'w-full rounded-lg border px-3 py-2 dark:bg-gray-800 dark:text-white',
                        errors.annual_fee
                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                            : 'border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500',
                    ]"
                />

                <p v-if="errors.annual_fee" class="mt-1 text-sm text-red-600">
                    {{ errors.annual_fee[0] }}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    This is the agreed annual fee at admission time.
                </p>
            </div>

            <!-- Paid Fee -->
            <div>
                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Paid Fee
                </label>

                <input
                    v-model="form.paid_fee"
                    @input="clearError('paid_fee')"
                    type="number"
                    min="0"
                    :class="[
                        'w-full rounded-lg border px-3 py-2 dark:bg-gray-800 dark:text-white',
                        errors.paid_fee
                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                            : 'border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500',
                    ]"
                />

                <p v-if="errors.paid_fee" class="mt-1 text-sm text-red-600">
                    {{ errors.paid_fee[0] }}
                </p>
            </div>
        </div>
    </div>
</template>
