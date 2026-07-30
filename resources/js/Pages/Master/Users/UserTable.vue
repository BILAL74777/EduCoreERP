<script setup>
import { ref, computed, watch } from "vue";
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/outline";
import Pagination from "@/Components/Pagination/Pagination.vue";
import debounce from "lodash/debounce";

const props = defineProps({
    users: Object,
    searchValue: String,
});

const emit = defineEmits([
    "edit",
    "delete",
    "search",
    "page-change",
]);

const search = ref(props.searchValue);
const statusFilter = ref("All");

watch(
    () => props.searchValue,
    (value) => {
        search.value = value;
    }
);

const userList = computed(() => {
    return props.users?.data || [];
});

const filteredUsers = computed(() => {
    return userList.value.filter((user) => {

        if (statusFilter.value === "All") {
            return true;
        }

        if (statusFilter.value === "Active") {
            return user.status == 1;
        }

        return user.status == 0;
    });
});

function setFilter(filter) {
    statusFilter.value = filter;
}

const searchUsers = debounce(() => {
    emit("search", search.value);
}, 500);

function resetSearch() {
    search.value = "";
    statusFilter.value = "All";
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
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white"
                >
                    All Users
                </h3>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage all system users
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">

                <!-- Status Filter -->

                <div class="inline-flex rounded-lg bg-gray-100 p-1 dark:bg-gray-900">

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

                <div class="flex gap-2">

                    <input
                        v-model="search"
                        @input="searchUsers"
                        type="text"
                        placeholder="Search users..."
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

        <!-- Table -->  

         

            <table class="w-full">

                <thead>

                    <tr class="border-b border-gray-200 dark:border-gray-800">

                        <th class="p-4 text-left text-xs font-medium text-gray-500">
                            #
                        </th>

                        <th class="p-4 text-left text-xs font-medium text-gray-500">
                            Image
                        </th>

                        <th class="p-4 text-left text-xs font-medium text-gray-500">
                            Name
                        </th>

                        <th class="p-4 text-left text-xs font-medium text-gray-500">
                            Email
                        </th>

                        
                        <th class="p-4 text-left text-xs font-medium text-gray-500">
                            Status
                        </th>

                        <th class="p-4 text-center text-xs font-medium text-gray-500">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <tr
                        v-for="(user, index) in filteredUsers"
                        :key="user.id"
                        class="border-b border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-900"
                    >

                        <td class="p-4 text-gray-900 dark:text-white">
                            {{
                                (users.current_page - 1) *
                                users.per_page +
                                index +
                                1
                            }}
                        </td>

                        <!-- Image -->

                        <td class="p-4 text-gray-900 dark:text-white">

                            <img
                                v-if="user.image"
                                :src="user.image.url"
                                class="w-10 h-10 rounded-full object-cover border"
                            />

                            <div
                                v-else
                                class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center"
                            >
                                👤
                            </div>

                        </td>

                        <td class="p-4 text-gray-900 dark:text-white">
                            {{ user.name }}
                        </td>

                        <td class="p-4 text-gray-900 dark:text-white">
                            {{ user.email }}
                        </td>

                        
                        <td class="p-4 text-gray-900 dark:text-white">

                            <span
                                :class="[
                                    'rounded-full px-2 py-1 text-xs font-medium',
                                    user.status
                                        ? 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-500'
                                        : 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-500'
                                ]"
                            >
                                {{ user.status ? "Active" : "Inactive" }}
                            </span>

                        </td>

                        <td class="p-4 text-gray-900 dark:text-white">

                            <div class="flex justify-center gap-3">

                                <button
                                    @click="emit('edit', user)"
                                    class="rounded-lg p-2 text-blue-600 hover:bg-blue-50"
                                >
                                    <PencilSquareIcon class="h-5 w-5" />
                                </button>

                                <button
                                    @click="emit('delete', user.id)"
                                    class="rounded-lg p-2 text-red-600 hover:bg-red-50"
                                >
                                    <TrashIcon class="h-5 w-5" />
                                </button>

                            </div>

                        </td>

                    </tr>

                    <tr v-if="filteredUsers.length === 0">

                        <td
                            colspan="7"
                            class="p-6 text-center text-gray-500"
                        >
                            No users found.
                        </td>

                    </tr>

                </tbody>

            </table>

       

        <Pagination
            :pagination="users"
            @page-change="emit('page-change', $event)"
        />

    </div>
</template>