<script setup>
import { ref, computed, watch } from "vue";
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/outline";
import Pagination from "@/Components/Pagination.vue";
import debounce from "lodash/debounce";

const props = defineProps({
    customers: {
        type: Object,
        required: true,
    },

    searchValue: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["edit", "delete", "page-change", "search"]);

const search = ref(props.searchValue);
const statusFilter = ref("All");

watch(
    () => props.searchValue,
    (value) => {
        search.value = value;
    }
);

const customerList = computed(() => {
    return props.customers?.data || [];
});

const filteredCustomers = computed(() => {
    return customerList.value.filter((customer) => {
        if (statusFilter.value === "All") {
            return true;
        }

        if (statusFilter.value === "Active") {
            return customer.status == 1;
        }

        return customer.status == 0;
    });
});

function setFilter(filter) {
    statusFilter.value = filter;
}

const searchCustomer = debounce(() => {
    emit("search", search.value);
}, 500);

function resetSearch() {
    search.value = "";
    statusFilter.value = "";
    emit("search", "");
}
</script>

<template>
    <div
        class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <!-- Header -->
        <div
            class="flex flex-col gap-4 border-b border-gray-200 px-5 py-4 lg:flex-row lg:items-center lg:justify-between dark:border-gray-800"
        >
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    All Customers
                </h3>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage all registered customers
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <!-- Status Filter -->

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <!-- Filter -->

                    <div
                        class="inline-flex rounded-lg bg-gray-100 p-1 dark:bg-gray-900"
                    >
                        <button
                            @click="setFilter('All')"
                            :class="[
                                'px-4 py-2 rounded-md text-sm font-medium',
                                statusFilter === 'All'
                                    ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow'
                                    : 'text-gray-500',
                            ]"
                        >
                            All
                        </button>

                        <button
                            @click="setFilter('Active')"
                            :class="[
                                'px-4 py-2 rounded-md text-sm font-medium',
                                statusFilter === 'Active'
                                    ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow'
                                    : 'text-gray-500',
                            ]"
                        >
                            Active
                        </button>

                        <button
                            @click="setFilter('Inactive')"
                            :class="[
                                'px-4 py-2 rounded-md text-sm font-medium',
                                statusFilter === 'Inactive'
                                    ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow'
                                    : 'text-gray-500',
                            ]"
                        >
                            Inactive
                        </button>
                    </div>
                    <!-- Search -->
                    <div class="flex gap-2">
                        <input
                            v-model="search"
                            @input="searchCustomer"
                            type="text"
                            placeholder="Search customers..."
                            class="h-11 rounded-lg border border-gray-300 px-4 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        />

                        <button
                            v-if="search || statusFilter !== 'All'"
                            @click="resetSearch"
                            class="h-11 px-4 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-white"
                        >
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th
                            class="p-4 text-left text-xs font-medium text-gray-500"
                        >
                            #
                        </th>

                        <th
                            class="p-4 text-left text-xs font-medium text-gray-500"
                        >
                            Name
                        </th>

                        <th
                            class="p-4 text-left text-xs font-medium text-gray-500"
                        >
                            Phone
                        </th>

                        <th
                            class="p-4 text-left text-xs font-medium text-gray-500"
                        >
                            CNIC
                        </th>

                        <th
                            class="p-4 text-left text-xs font-medium text-gray-500"
                        >
                            Opening Balance
                        </th>

                        <th
                            class="p-4 text-left text-xs font-medium text-gray-500"
                        >
                            Credit Limit
                        </th>

                        <th
                            class="p-4 text-left text-xs font-medium text-gray-500"
                        >
                            Status
                        </th>

                        <th
                            class="p-4 text-center text-xs font-medium text-gray-500"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="(customer, index) in filteredCustomers"
                        :key="customer.id"
                        class="border-b border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-900"
                    >
                        <!-- Serial Number -->
                        <td class="p-4">
                            {{
                                (customers.current_page - 1) *
                                    customers.per_page +
                                index +
                                1
                            }}
                        </td>

                        <td class="p-4">{{ customer.name }}</td>
                        <td class="p-4">{{ customer.phone }}</td>
                        <td class="p-4">{{ customer.cnic }}</td>
                        <td class="p-4">{{ customer.opening_balance }}</td>
                        <td class="p-4">{{ customer.credit_limit }}</td>

                        <td class="p-4">
                            <span
                                :class="[
                                    'rounded-full px-2 py-1 text-xs font-medium',
                                    customer.status
                                        ? 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-500'
                                        : 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-500',
                                ]"
                            >
                                {{ customer.status ? "Active" : "Inactive" }}
                            </span>
                        </td>

                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                <button
                                    @click="emit('edit', customer)"
                                    class="rounded-lg p-2 text-blue-600 hover:bg-blue-50"
                                >
                                    <PencilSquareIcon class="h-5 w-5" />
                                </button>

                                <button
                                    @click="emit('delete', customer.id)"
                                    class="rounded-lg p-2 text-red-600 hover:bg-red-50"
                                >
                                    <TrashIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="filteredCustomers.length === 0">
                        <td colspan="8" class="p-6 text-center text-gray-500">
                            No customers found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->

        <Pagination
            :pagination="customers"
            @page-change="emit('page-change', $event)"
        />

        
    </div>
</template>
