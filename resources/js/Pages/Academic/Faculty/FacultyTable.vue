<script setup>
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/outline";

defineProps({
    faculties: {
        type: Object,
        required: true,
    },

    loading: {
        type: Boolean,
        default: false,
    },

    sortField: {
        type: String,
        default: "",
    },

    sortDirection: {
        type: String,
        default: "asc",
    },
});

const emit = defineEmits(["edit", "delete", "status", "sort"]);
</script>

<template>
    <div class="overflow-hidden rounded-xl bg-white shadow dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table
                class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
            >
                <!-- Head -->

                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th
                            @click="emit('sort', 'code')"
                            class="cursor-pointer whitespace-nowrap px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300"
                        >
                            Code
                        </th>

                        <th
                            @click="emit('sort', 'name')"
                            class="cursor-pointer whitespace-nowrap px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300"
                        >
                            Faculty
                        </th>

                        <th
                            class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300"
                        >
                            Description
                        </th>

                        <th
                            class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300"
                        >
                            Status
                        </th>

                        <th
                            class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>

                <!-- Body -->

                <tbody
                    class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800"
                >
                    <!-- Loading -->

                    <tr v-if="loading">
                        <td colspan="5" class="px-6 py-10 text-center">
                            <svg
                                class="mx-auto h-8 w-8 animate-spin text-brand-500"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                />

                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.372 0 0 5.373 0 12h4z"
                                />
                            </svg>

                            <p class="mt-3 text-gray-500">Loading...</p>
                        </td>
                    </tr>

                    <!-- Empty -->

                    <tr
                        v-else-if="
                            !faculties.data || faculties.data.length === 0
                        "
                    >
                        <td
                            colspan="5"
                            class="px-6 py-10 text-center text-gray-500"
                        >
                            No Faculties Found.
                        </td>
                    </tr>

                    <!-- Records -->

                    <tr
                        v-for="faculty in faculties.data"
                        :key="faculty.id"
                        class="hover:bg-gray-50 dark:hover:bg-gray-700"
                    >
                        <!-- Code -->

                        <td class="whitespace-nowrap px-6 py-4">
                            {{ faculty.code }}
                        </td>

                        <!-- Name -->

                        <td class="whitespace-nowrap px-6 py-4 font-medium">
                            {{ faculty.name }}
                        </td>

                        <!-- Description -->

                        <td class="px-6 py-4">
                            {{ faculty.description ?? "-" }}
                        </td>

                        <!-- Status -->

                        <td class="px-6 py-4 text-center">
                            <button
                                @click="emit('status', faculty)"
                                :class="
                                    faculty.is_active
                                        ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                        : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300'
                                "
                                class="rounded-full px-4 py-1 text-xs font-semibold transition"
                            >
                                {{ faculty.is_active ? "Active" : "Inactive" }}
                            </button>
                        </td>

                        <!-- Actions -->

                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <button
                                    @click="emit('edit', faculty)"
                                    class="rounded-lg bg-blue-100 p-2 text-blue-600 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300"
                                >
                                    <PencilSquareIcon class="h-5 w-5" />
                                </button>

                                <button
                                    @click="emit('delete', faculty)"
                                    class="rounded-lg bg-red-100 p-2 text-red-600 hover:bg-red-200 dark:bg-red-900 dark:text-red-300"
                                >
                                    <TrashIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
