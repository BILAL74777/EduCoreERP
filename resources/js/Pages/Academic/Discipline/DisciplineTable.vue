<script setup>
import { ref, computed, watch } from "vue";
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/outline";
import Pagination from "@/Components/Pagination/Pagination.vue";
import debounce from "lodash/debounce";

const props = defineProps({
    disciplines: Object,
    searchValue: String,

    showDeleteModal: Boolean,
    deleting: Boolean,
});

const emit = defineEmits([
    "edit",
    "delete",
    "confirm-delete",
    "close-delete",
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

const disciplineList = computed(() => {
    return props.disciplines?.data || [];
});

const filteredDisciplines = computed(() => {
    return disciplineList.value.filter((discipline) => {
        if (statusFilter.value === "All") {
            return true;
        }

        if (statusFilter.value === "Active") {
            return discipline.is_active;
        }

        return !discipline.is_active;
    });
});

function setFilter(filter) {
    statusFilter.value = filter;
}

const searchDiscipline = debounce(() => {
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
                    All Disciplines
                </h3>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage all registered disciplines
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <!-- Status Filter -->

                <div
                    class="inline-flex rounded-lg bg-gray-100 p-1 dark:bg-gray-900"
                >
                    <button
                        @click="setFilter('All')"
                        :class="[
                            'rounded-md px-4 py-2 text-sm font-medium',
                            statusFilter === 'All'
                                ? 'bg-white text-gray-900 shadow dark:bg-gray-800 dark:text-white'
                                : 'text-gray-500',
                        ]"
                    >
                        All
                    </button>

                    <button
                        @click="setFilter('Active')"
                        :class="[
                            'rounded-md px-4 py-2 text-sm font-medium',
                            statusFilter === 'Active'
                                ? 'bg-white text-gray-900 shadow dark:bg-gray-800 dark:text-white'
                                : 'text-gray-500',
                        ]"
                    >
                        Active
                    </button>

                    <button
                        @click="setFilter('Inactive')"
                        :class="[
                            'rounded-md px-4 py-2 text-sm font-medium',
                            statusFilter === 'Inactive'
                                ? 'bg-white text-gray-900 shadow dark:bg-gray-800 dark:text-white'
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
                        @input="searchDiscipline"
                        type="text"
                        placeholder="Search disciplines..."
                        class="h-11 rounded-lg border border-gray-300 px-4 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    />

                    <button
                        v-if="search || statusFilter !== 'All'"
                        @click="resetSearch"
                        class="h-11 rounded-lg bg-gray-200 px-4 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-white"
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
                            Code
                        </th>

                        <th
                            class="p-4 text-left text-xs font-medium text-gray-500"
                        >
                            Discipline
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
                    <tr
                        v-for="(discipline, index) in filteredDisciplines"
                        :key="discipline.id"
                        class="border-b border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-900"
                    >
                        <td class="p-4">
                            {{
                                (disciplines.current_page - 1) *
                                    disciplines.per_page +
                                index +
                                1
                            }}
                        </td>

                        <td class="p-4">
                            {{ discipline.discipline ?? discipline.name ?? "-" }}
                        </td>

                        <td class="p-4">
                            {{ discipline.faculty?.faculty ?? "-" }}
                        </td>

                        <td class="p-4 text-center">
                            <span
                                :class="[
                                    'rounded-full px-2 py-1 text-xs font-medium',
                                    discipline.is_active
                                        ? 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-500'
                                        : 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-500',
                                ]"
                            >
                                {{
                                    discipline.is_active ? "Active" : "Inactive"
                                }}
                            </span>
                        </td>

                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                <button
                                    @click="emit('edit', discipline)"
                                    class="rounded-lg p-2 text-blue-600 hover:bg-blue-50"
                                >
                                    <PencilSquareIcon class="h-5 w-5" />
                                </button>

                                <button
                                    @click="emit('delete', discipline.id)"
                                    class="rounded-lg p-2 text-red-600 hover:bg-red-50"
                                >
                                    <TrashIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="filteredDisciplines.length === 0">
                        <td colspan="6" class="p-6 text-center text-gray-500">
                            No disciplines found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->

        <Pagination
            :pagination="disciplines"
            @page-change="emit('page-change', $event)"
        />
    </div>
</template>
