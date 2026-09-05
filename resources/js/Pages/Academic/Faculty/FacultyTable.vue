<script setup>
import { ref, computed, watch } from "vue";
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/outline";
import Pagination from "@/Components/Pagination/Pagination.vue";
import debounce from "lodash/debounce";

const props = defineProps({
    faculties: {
        type: Object,
        default: () => ({}),
    },

    searchValue: {
        type: String,
        default: "",
    },
});

const emit = defineEmits([
    "edit",
    "delete",
    "status",
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

const facultyList = computed(() => {
    return props.faculties?.data || [];
});

const filteredFaculties = computed(() => {
    return facultyList.value.filter((faculty) => {
        if (statusFilter.value === "All") {
            return true;
        }

        if (statusFilter.value === "Active") {
            return faculty.is_active;
        }

        return !faculty.is_active;
    });
});

function setFilter(filter) {
    statusFilter.value = filter;
}

const searchFaculty = debounce(() => {
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
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    All Faculties
                </h3>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage all registered faculties
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <!-- Status Filter -->
                <div
                    class="inline-flex rounded-lg bg-gray-100 p-1 dark:bg-gray-900"
                >
                    <button
                        type="button"
                        @click="setFilter('All')"
                        :class="[
                            'rounded-md px-4 py-2 text-sm font-medium transition',
                            statusFilter === 'All'
                                ? 'bg-white text-gray-900 shadow dark:bg-gray-800 dark:text-white'
                                : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white',
                        ]"
                    >
                        All
                    </button>

                    <button
                        type="button"
                        @click="setFilter('Active')"
                        :class="[
                            'rounded-md px-4 py-2 text-sm font-medium transition',
                            statusFilter === 'Active'
                                ? 'bg-white text-gray-900 shadow dark:bg-gray-800 dark:text-white'
                                : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white',
                        ]"
                    >
                        Active
                    </button>

                    <button
                        type="button"
                        @click="setFilter('Inactive')"
                        :class="[
                            'rounded-md px-4 py-2 text-sm font-medium transition',
                            statusFilter === 'Inactive'
                                ? 'bg-white text-gray-900 shadow dark:bg-gray-800 dark:text-white'
                                : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white',
                        ]"
                    >
                        Inactive
                    </button>
                </div>

                <!-- Search -->
                <div class="flex gap-2">
                    <input
                        v-model="search"
                        @input="searchFaculty"
                        type="text"
                        placeholder="Search faculties..."
                        class="h-11 rounded-lg border border-gray-300 px-4 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    />

                    <button
                        v-if="search || statusFilter !== 'All'"
                        type="button"
                        @click="resetSearch"
                        class="h-11 rounded-lg bg-gray-200 px-4 text-gray-700 transition hover:bg-gray-300 dark:bg-gray-700 dark:text-white"
                    >
                        Reset
                    </button>
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
                            Faculty
                        </th>

                        <th
                            class="p-4 text-center text-xs font-medium text-gray-500"
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
                    <!-- Records -->
                    <tr
                        v-for="(faculty, index) in filteredFaculties"
                        :key="faculty.id"
                        class="border-b border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-900"
                    >
                        <!-- Number -->
                        <td class="p-4">
                            {{
                                (faculties.pagination?.current_page - 1) *
                                    faculties.pagination?.per_page +
                                index +
                                1
                            }}
                        </td>

                        <!-- Faculty -->
                        <td class="p-4">
                            {{ faculty.faculty ?? "-" }}
                        </td>

                        <!-- Status -->
                        <td class="p-4 text-center">
                            <button
                                type="button"
                                @click="emit('status', faculty)"
                                :class="[
                                    'rounded-full px-3 py-1 text-xs font-medium transition',
                                    faculty.is_active
                                        ? 'bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-500/15 dark:text-green-500'
                                        : 'bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-500/15 dark:text-red-500',
                                ]"
                            >
                                {{
                                    faculty.is_active
                                        ? "Active"
                                        : "Inactive"
                                }}
                            </button>
                        </td>

                        <!-- Actions -->
                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                <button
                                    type="button"
                                    @click="emit('edit', faculty)"
                                    class="rounded-lg bg-blue-100 p-2 text-blue-600 transition hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300"
                                    title="Edit"
                                >
                                    <PencilSquareIcon class="h-5 w-5" />
                                </button>

                                <button
                                    type="button"
                                    @click="emit('delete', faculty)"
                                    class="rounded-lg bg-red-100 p-2 text-red-600 transition hover:bg-red-200 dark:bg-red-900 dark:text-red-300"
                                    title="Delete"
                                >
                                    <TrashIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Empty -->
                    <tr v-if="filteredFaculties.length === 0">
                        <td
                            colspan="4"
                            class="p-6 text-center text-gray-500"
                        >
                            No faculties found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <Pagination
            v-if="faculties.pagination"
            :pagination="faculties"
            @page-change="emit('page-change', $event)"
        />
    </div>
</template> 