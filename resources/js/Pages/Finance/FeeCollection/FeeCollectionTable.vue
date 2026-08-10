<script setup>
import { ref, watch } from "vue";
import { TrashIcon } from "@heroicons/vue/24/outline";
import axios from "axios";
import { toast } from "vue3-toastify";
import debounce from "lodash/debounce";

const props = defineProps({
    payments: {
        type: Object,
        default: () => ({
            data: [],
            current_page: 1,
            last_page: 1,
            total: 0,
        }),
    },

    searchValue: {
        type: String,
        default: "",
    },

    loading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits([
    "search",
    "page-change",
]);

const search = ref(props.searchValue);

watch(
    () => props.searchValue,
    (value) => {
        search.value = value;
    }
);

const handleSearch = debounce(() => {
    emit("search", search.value);
}, 400);

const deletePayment = async (payment) => {
    if (
        !confirm(
            "Are you sure you want to delete this payment?"
        )
    ) {
        return;
    }

    try {
        const response = await axios.delete(
            `/api/finance/fee-collection/${payment.id}`
        );

        if (response.data.success) {
            toast.success(response.data.message);

            emit(
                "page-change",
                props.payments.current_page
            );
        }
    } catch (error) {
        toast.error(
            error.response?.data?.message ||
                "Failed to delete payment."
        );
    }
};

const formatAmount = (amount) => {
    return Number(amount || 0).toLocaleString();
};

const formatDate = (date) => {
    if (!date) {
        return "-";
    }

    return new Date(date).toLocaleDateString();
};
</script>

<template>
    <div
        class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <!-- Header -->
        <div
            class="flex flex-col gap-4 border-b border-gray-200 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800"
        >
            <div>
                <h2
                    class="text-lg font-semibold text-gray-900 dark:text-white"
                >
                    Payment History
                </h2>

                <p
                    class="text-sm text-gray-500 dark:text-gray-400"
                >
                    All collected student fee payments.
                </p>
            </div>

            <input
                v-model="search"
                @input="handleSearch"
                type="text"
                placeholder="Search student..."
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 sm:w-72 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            />
        </div>

        <!-- Loading -->
        <div
            v-if="loading"
            class="p-10 text-center text-sm text-gray-500"
        >
            Loading payments...
        </div>

        <!-- Table -->
        <div v-else class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead
                    class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-900 dark:text-gray-400"
                >
                    <tr>
                        <th class="px-5 py-3">
                            Student
                        </th>

                        <th class="px-5 py-3">
                            Admission No
                        </th>

                        <th class="px-5 py-3">
                            Payment Date
                        </th>

                        <th class="px-5 py-3 text-right">
                            Amount
                        </th>

                        <th class="px-5 py-3">
                            Note
                        </th>

                        <th class="px-5 py-3 text-right">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody
                    class="divide-y divide-gray-200 dark:divide-gray-800"
                >
                    <tr
                        v-for="payment in payments.data"
                        :key="payment.id"
                        class="hover:bg-gray-50 dark:hover:bg-gray-900"
                    >
                        <td
                            class="px-5 py-4 font-medium text-gray-900 dark:text-white"
                        >
                            {{ payment.student?.name || "-" }}
                        </td>

                        <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                            {{ payment.student?.admission_no || "-" }}
                        </td>

                        <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                            {{ formatDate(payment.payment_date) }}
                        </td>

                        <td
                            class="px-5 py-4 text-right font-semibold text-green-600"
                        >
                            {{ formatAmount(payment.amount) }}
                        </td>

                        <td
                            class="max-w-xs truncate px-5 py-4 text-gray-600 dark:text-gray-400"
                        >
                            {{ payment.note || "-" }}
                        </td>

                        <td class="px-5 py-4 text-right">
                            <button
                                type="button"
                                @click="deletePayment(payment)"
                                class="rounded-lg p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-950"
                                title="Delete"
                            >
                                <TrashIcon class="h-5 w-5" />
                            </button>
                        </td>
                    </tr>

                    <tr
                        v-if="
                            !payments.data ||
                            payments.data.length === 0
                        "
                    >
                        <td
                            colspan="6"
                            class="px-5 py-10 text-center text-gray-500"
                        >
                            No fee payments found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            v-if="payments.last_page > 1"
            class="flex items-center justify-between border-t border-gray-200 px-5 py-4 dark:border-gray-800"
        >
            <div
                class="text-sm text-gray-500 dark:text-gray-400"
            >
                Page {{ payments.current_page }}
                of {{ payments.last_page }}
            </div>

            <div class="flex gap-2">
                <button
                    type="button"
                    :disabled="payments.current_page <= 1"
                    @click="
                        emit(
                            'page-change',
                            payments.current_page - 1
                        )
                    "
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm disabled:opacity-40 dark:border-gray-700"
                >
                    Previous
                </button>

                <button
                    type="button"
                    :disabled="
                        payments.current_page >=
                        payments.last_page
                    "
                    @click="
                        emit(
                            'page-change',
                            payments.current_page + 1
                        )
                    "
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm disabled:opacity-40 dark:border-gray-700"
                >
                    Next
                </button>
            </div>
        </div>
    </div>
</template>