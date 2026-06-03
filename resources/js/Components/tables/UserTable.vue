<script setup>
import { ref, computed } from "vue";

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits([
    "edit",
    "delete",
]);

const search = ref("");
const statusFilter = ref("All");

const currentPage = ref(1);
const perPage = 3;

const filteredUsers = computed(() => {
    return props.users.filter((user) => {
        const searchTerm = search.value.toLowerCase();

        const matchesSearch =
            user.name.toLowerCase().includes(searchTerm) ||
            user.address.toLowerCase().includes(searchTerm) ||
            user.phone.toLowerCase().includes(searchTerm);

        const matchesStatus =
            statusFilter.value === "All"
                ? true
                : user.status === statusFilter.value;

        return matchesSearch && matchesStatus;
    });
});

const totalPages = computed(() =>
    Math.ceil(filteredUsers.value.length / perPage)
);

const paginatedUsers = computed(() => {
    const start = (currentPage.value - 1) * perPage;

    return filteredUsers.value.slice(
        start,
        start + perPage
    );
});

function setFilter(filter) {
    statusFilter.value = filter;
    currentPage.value = 1;
}

function changePage(page) {
    currentPage.value = page;
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
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white"
                >
                    Users Management
                </h3>

                <p
                    class="text-sm text-gray-500 dark:text-gray-400"
                >
                    Manage all registered users
                </p>
            </div>

            <div
                class="flex flex-col gap-3 sm:flex-row sm:items-center"
            >
                <!-- Filter Tabs -->

                <div
                    class="inline-flex rounded-lg bg-gray-100 p-1 dark:bg-gray-900"
                >
                    <button
                        @click="setFilter('All')"
                        :class="[
                            'px-4 py-2 rounded-md text-sm font-medium',
                            statusFilter === 'All'
                                ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow'
                                : 'text-gray-500'
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
                                : 'text-gray-500'
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
                                : 'text-gray-500'
                        ]"
                    >
                        Inactive
                    </button>
                </div>

                <!-- Search -->

                <input
                    v-model="search"
                    type="text"
                    placeholder="Search users..."
                    class="h-11 rounded-lg border border-gray-300 px-4 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                />
            </div>
        </div>

        <!-- Table -->

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full">
                <thead>
                    <tr
                        class="border-b border-gray-200 dark:border-gray-800"
                    >
                        <th
                            class="p-4 text-left text-xs font-medium text-gray-500"
                        >
                            ID
                        </th>

                        <th
                            class="p-4 text-left text-xs font-medium text-gray-500"
                        >
                            Name
                        </th>

                        <th
                            class="p-4 text-left text-xs font-medium text-gray-500"
                        >
                            Address
                        </th>

                        <th
                            class="p-4 text-left text-xs font-medium text-gray-500"
                        >
                            Phone
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
                        v-for="user in paginatedUsers"
                        :key="user.id"
                        class="border-b border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-900"
                    >
                        <td class="p-4">
                            {{ user.id }}
                        </td>

                        <td class="p-4 font-medium">
                            {{ user.name }}
                        </td>

                        <td class="p-4">
                            {{ user.address }}
                        </td>

                        <td class="p-4">
                            {{ user.phone }}
                        </td>

                        <td class="p-4">
                            <span
                                :class="[
                                    'rounded-full px-2 py-1 text-xs font-medium',
                                    user.status === 'Active'
                                        ? 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-500'
                                        : 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-500'
                                ]"
                            >
                                {{ user.status }}
                            </span>
                        </td>

                        <td class="p-4">
                            <div
                                class="flex justify-center gap-3"
                            >
                                <!-- Edit -->

                                <button
                                    @click="emit('edit', user)"
                                    class="text-blue-600 hover:text-blue-800"
                                >
                                    ✏️
                                </button>

                                <!-- Delete -->

                                <button
                                    @click="emit('delete', user.id)"
                                    class="text-red-600 hover:text-red-800"
                                >
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr
                        v-if="paginatedUsers.length === 0"
                    >
                        <td
                            colspan="6"
                            class="p-6 text-center text-gray-500"
                        >
                            No records found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->

        <div
            class="flex flex-col gap-4 border-t border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800"
        >
            <div>
                <span
                    class="text-sm text-gray-500 dark:text-gray-400"
                >
                    Showing
                    <strong>
                        {{
                            filteredUsers.length === 0
                                ? 0
                                : (currentPage - 1) * perPage + 1
                        }}
                    </strong>

                    to

                    <strong>
                        {{
                            Math.min(
                                currentPage * perPage,
                                filteredUsers.length
                            )
                        }}
                    </strong>

                    of

                    <strong>
                        {{ filteredUsers.length }}
                    </strong>

                    records
                </span>
            </div>

            <div class="flex gap-2">
                <button
                    @click="changePage(currentPage - 1)"
                    :disabled="currentPage === 1"
                    class="rounded-lg border px-3 py-2 disabled:opacity-50"
                >
                    Previous
                </button>

                <button
                    v-for="page in totalPages"
                    :key="page"
                    @click="changePage(page)"
                    :class="[
                        'rounded-lg px-4 py-2',
                        currentPage === page
                            ? 'bg-brand-500 text-white'
                            : 'border'
                    ]"
                >
                    {{ page }}
                </button>

                <button
                    @click="changePage(currentPage + 1)"
                    :disabled="
                        currentPage === totalPages ||
                        totalPages === 0
                    "
                    class="rounded-lg border px-3 py-2 disabled:opacity-50"
                >
                    Next
                </button>
            </div>
        </div>
    </div>
</template>