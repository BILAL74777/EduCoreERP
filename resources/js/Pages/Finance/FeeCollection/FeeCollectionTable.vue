<script setup>
import { ref, watch } from "vue";
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

const search = ref(
    props.searchValue
);

watch(
    () => props.searchValue,
    (value) => {
        search.value = value;
    }
);

const doSearch = debounce(
    () => {
        emit(
            "search",
            search.value
        );
    },
    400
);

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

const previousPage = () => {

    if (
        props.payments.current_page >
        1
    ) {
        emit(
            "page-change",
            props.payments.current_page - 1
        );
    }
};

const nextPage = () => {

    if (
        props.payments.current_page <
        props.payments.last_page
    ) {
        emit(
            "page-change",
            props.payments.current_page + 1
        );
    }
};
</script>

<template>
    <div
        class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900"
    >

        <!-- Header -->
        <div
            class="flex flex-col gap-4 border-b border-gray-200 px-6 py-4 md:flex-row md:items-center md:justify-between dark:border-gray-800"
        >

            <div>
                <h2
                    class="text-lg font-semibold text-gray-900 dark:text-white"
                >
                    Payment History
                </h2>

                <p
                    class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                >
                    All collected student payments.
                </p>
            </div>

            <div class="w-full md:w-80">

                <input
                    v-model="search"
                    type="text"
                    placeholder="Search student..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    @input="doSearch"
                />

            </div>

        </div>

        <!-- Table -->
        <div
            class="overflow-x-auto"
        >

            <table
                class="w-full text-left text-sm"
            >

                <thead
                    class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400"
                >

                    <tr>

                        <th class="px-6 py-3">
                            Student
                        </th>

                        <th class="px-6 py-3">
                            Admission
                        </th>

                        <th class="px-6 py-3">
                            Class
                        </th>

                        <th class="px-6 py-3">
                            Date
                        </th>

                        <th class="px-6 py-3">
                            Note
                        </th>

                        <th class="px-6 py-3 text-right">
                            Amount
                        </th>

                    </tr>

                </thead>

                <tbody
                    class="divide-y divide-gray-200 dark:divide-gray-800"
                >

                    <!-- Loading -->
                    <tr v-if="loading">

                        <td
                            colspan="6"
                            class="px-6 py-10 text-center"
                        >

                            <div
                                class="mx-auto h-7 w-7 animate-spin rounded-full border-4 border-gray-200 border-t-blue-600"
                            ></div>

                            <p
                                class="mt-2 text-sm text-gray-500"
                            >
                                Loading payments...
                            </p>

                        </td>

                    </tr>

                    <!-- Data -->
                    <template
                        v-else
                    >

                        <tr
                            v-for="payment in payments.data"
                            :key="payment.id"
                            class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50"
                        >

                            <td
                                class="whitespace-nowrap px-6 py-4"
                            >

                                <div
                                    class="flex items-center gap-3"
                                >

                                    <img
                                        v-if="payment.student?.photo?.url"
                                        :src="payment.student.photo.url"
                                        class="h-9 w-9 rounded-full object-cover"
                                        alt="Student"
                                    />

                                    <div
                                        v-else
                                        class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-200 text-[10px] text-gray-500 dark:bg-gray-700"
                                    >
                                        N/A
                                    </div>

                                    <div>

                                        <p
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{
                                                payment.student?.name ||
                                                "-"
                                            }}
                                        </p>

                                    </div>

                                </div>

                            </td>

                            <td
                                class="whitespace-nowrap px-6 py-4 text-gray-600 dark:text-gray-400"
                            >
                                {{
                                    payment.student?.admission_no ||
                                    "-"
                                }}
                            </td>

                            <td
                                class="whitespace-nowrap px-6 py-4 text-gray-600 dark:text-gray-400"
                            >
                                {{
                                    payment.fee_structure?.academic_class?.name ||
                                    "-"
                                }}
                            </td>

                            <td
                                class="whitespace-nowrap px-6 py-4 text-gray-600 dark:text-gray-400"
                            >
                                {{
                                    formatDate(
                                        payment.payment_date
                                    )
                                }}
                            </td>

                            <td
                                class="max-w-xs truncate px-6 py-4 text-gray-600 dark:text-gray-400"
                            >
                                {{
                                    payment.note ||
                                    "-"
                                }}
                            </td>

                            <td
                                class="whitespace-nowrap px-6 py-4 text-right font-semibold text-gray-900 dark:text-white"
                            >
                                Rs.
                                {{
                                    money(
                                        payment.amount
                                    )
                                }}
                            </td>

                        </tr>

                        <!-- Empty -->
                        <tr
                            v-if="
                                !payments.data?.length
                            "
                        >

                            <td
                                colspan="6"
                                class="px-6 py-12 text-center"
                            >

                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    No payment records found.
                                </p>

                            </td>

                        </tr>

                    </template>

                </tbody>

            </table>

        </div>

        <!-- Pagination -->
        <div
            v-if="payments.last_page > 1"
            class="flex items-center justify-between border-t border-gray-200 px-6 py-4 dark:border-gray-800"
        >

            <p
                class="text-sm text-gray-500 dark:text-gray-400"
            >
                Page
                <span class="font-medium text-gray-900 dark:text-white">
                    {{ payments.current_page }}
                </span>
                of
                <span class="font-medium text-gray-900 dark:text-white">
                    {{ payments.last_page }}
                </span>
            </p>

            <div class="flex gap-2">

                <button
                    type="button"
                    :disabled="
                        payments.current_page <= 1
                    "
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 disabled:cursor-not-allowed disabled:opacity-40 dark:border-gray-700 dark:text-gray-300"
                    @click="previousPage"
                >
                    Previous
                </button>

                <button
                    type="button"
                    :disabled="
                        payments.current_page >=
                        payments.last_page
                    "
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 disabled:cursor-not-allowed disabled:opacity-40 dark:border-gray-700 dark:text-gray-300"
                    @click="nextPage"
                >
                    Next
                </button>

            </div>

        </div>

    </div>
</template>