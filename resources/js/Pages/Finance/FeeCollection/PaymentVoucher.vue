<script setup>
import { computed } from "vue";

const props = defineProps({
    payment: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits([
    "close",
]);

const student = computed(
    () => props.payment?.student || {}
);

const feeStructure = computed(
    () =>
        props.payment?.fee_structure || {}
);

const amount = computed(
    () =>
        Number(
            props.payment?.amount || 0
        )
);

const previousDue = computed(() => {

    const currentDue = Number(
        feeStructure.value.calculated_due ||
        feeStructure.value.net_fee ||
        0
    );

    return currentDue + amount.value;
});

const remainingDue = computed(() => {

    return Number(
        feeStructure.value.calculated_due ||
        feeStructure.value.net_fee ||
        0
    );
});

const money = (value) => {

    return Number(
        value || 0
    ).toLocaleString(
        "en-PK",
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }
    );
};

const formatDate = (date) => {

    if (!date) {
        return "-";
    }

    return new Date(
        date
    ).toLocaleDateString(
        "en-GB"
    );
};

const printVoucher = () => {

    window.print();
};

const close = () => {

    emit("close");
};
</script>

<template>
    <div
        class="fixed inset-0 z-[999] flex items-center justify-center bg-black/60 p-4"
    >

        <!-- Screen Modal -->
        <div
            class="voucher-modal w-full max-w-md overflow-hidden rounded-xl bg-white shadow-2xl"
        >

            <!-- Modal Header -->
            <div
                class="no-print flex items-center justify-between border-b border-gray-200 px-5 py-4"
            >

                <div>
                    <h2
                        class="text-lg font-semibold text-gray-900"
                    >
                        Payment Voucher
                    </h2>

                    <p
                        class="text-xs text-gray-500"
                    >
                        Payment collected successfully.
                    </p>
                </div>

                <button
                    type="button"
                    class="rounded-lg p-2 text-gray-500 hover:bg-gray-100"
                    @click="close"
                >
                    ✕
                </button>

            </div>

            <!-- Voucher -->
            <div
                id="payment-voucher-print"
                class="voucher-paper p-5"
            >

                <!-- Logo -->
                <div
                    class="text-center"
                >

                    <img
                        :src="'/images/logo/edcore-erp-logo.PNG'"
                        alt="EduCore ERP"
                        class="mx-auto h-14 object-contain"
                    />

                    <h1
                        class="mt-2 text-base font-bold text-gray-900"
                    >
                        Modern Era Institute of Sciences
                    </h1>

                    <p
                        class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-600"
                    >
                        Fee Payment Voucher
                    </p>

                </div>

                <div
                    class="my-4 border-t border-dashed border-gray-500"
                ></div>

                <!-- Student -->
                <div
                    class="space-y-2 text-xs"
                >

                    <div
                        class="flex justify-between gap-3"
                    >
                        <span class="font-semibold">
                            Student
                        </span>

                        <span class="text-right">
                            {{ student.name }}
                        </span>
                    </div>

                    <div
                        class="flex justify-between gap-3"
                    >
                        <span class="font-semibold">
                            Admission No
                        </span>

                        <span>
                            {{ student.admission_no || "-" }}
                        </span>
                    </div>

                    <div
                        class="flex justify-between gap-3"
                    >
                        <span class="font-semibold">
                            Class
                        </span>

                        <span>
                            {{
                                feeStructure.academic_class?.name ||
                                "-"
                            }}
                        </span>
                    </div>

                    <div
                        class="flex justify-between gap-3"
                    >
                        <span class="font-semibold">
                            Payment Date
                        </span>

                        <span>
                            {{
                                formatDate(
                                    payment.payment_date
                                )
                            }}
                        </span>
                    </div>

                </div>

                <div
                    class="my-4 border-t border-dashed border-gray-500"
                ></div>

                <!-- Payment -->
                <div
                    class="space-y-2 text-xs"
                >

                    <div
                        class="flex justify-between"
                    >
                        <span>
                            Previous Due
                        </span>

                        <span>
                            Rs.
                            {{ money(previousDue) }}
                        </span>
                    </div>

                    <div
                        class="flex justify-between text-sm font-bold"
                    >
                        <span>
                            Amount Paid
                        </span>

                        <span>
                            Rs.
                            {{ money(amount) }}
                        </span>
                    </div>

                    <div
                        class="flex justify-between text-sm font-bold"
                    >
                        <span>
                            Remaining Due
                        </span>

                        <span>
                            Rs.
                            {{ money(remainingDue) }}
                        </span>
                    </div>

                </div>

                <div
                    class="my-4 border-t border-dashed border-gray-500"
                ></div>

                <div
                    v-if="payment.note"
                    class="mb-4 text-xs"
                >
                    <span class="font-semibold">
                        Note:
                    </span>

                    {{ payment.note }}
                </div>

                <!-- Signatures -->
                <div
                    class="mt-8 grid grid-cols-2 gap-5 text-center text-[10px]"
                >

                    <div>
                        <div
                            class="mb-1 border-t border-gray-500"
                        ></div>

                        Student / Parent
                    </div>

                    <div>
                        <div
                            class="mb-1 border-t border-gray-500"
                        ></div>

                        Authorized Signature
                    </div>

                </div>

                <p
                    class="mt-5 text-center text-[9px] text-gray-500"
                >
                    Thank you for your payment.
                </p>

            </div>

            <!-- Buttons -->
            <div
                class="no-print flex justify-end gap-2 border-t border-gray-200 p-4"
            >

                <button
                    type="button"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    @click="close"
                >
                    Close
                </button>

                <button
                    type="button"
                    class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                    @click="printVoucher"
                >
                    Print Voucher
                </button>

            </div>

        </div>
    </div>
</template>

<style>
@media print {
    body * {
        visibility: hidden;
    }

    #payment-voucher-print,
    #payment-voucher-print * {
        visibility: visible;
    }

    #payment-voucher-print {
        position: absolute;
        left: 0;
        top: 0;

        width: 80mm;

        padding: 5mm !important;

        margin: 0;

        background: white !important;
    }

    .no-print {
        display: none !important;
    }

    .voucher-modal {
        width: 80mm !important;
        max-width: 80mm !important;

        border-radius: 0 !important;
        box-shadow: none !important;
    }

    @page {
        size: 80mm auto;
        margin: 0;
    }
}
</style>