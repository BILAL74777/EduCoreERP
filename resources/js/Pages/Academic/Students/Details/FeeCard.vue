<script setup>
import { computed } from "vue";

const props = defineProps({
    student: Object,
});

const fee = computed(() => props.student.fee_structure ?? {});

const netPayable = computed(() => {
    return (Number(fee.value.annual_fee) || 0) - (Number(fee.value.paid_fee) || 0);
});
</script>

<template>
    <div
        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">
            Fee Information
        </h3>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Annual Fee</p>

                <p class="text-lg font-semibold text-blue-600">
                    Rs. {{ $formatAmount(fee.annual_fee ?? 0) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Paid Fee</p>

                <p class="text-lg font-semibold text-green-600">
                    Rs. {{ $formatAmount(fee.paid_fee ?? 0) }}
                </p>
            </div>

            <div class="col-span-2">
                <p class="text-sm text-gray-500">Net Payable</p>

                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    Rs. {{ $formatAmount(netPayable) }}
                </p>
            </div>
        </div>
    </div>
</template>