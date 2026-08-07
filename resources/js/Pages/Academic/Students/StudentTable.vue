<script setup>
import { ref, computed, watch } from "vue";
import debounce from "lodash/debounce";
import { Link } from "@inertiajs/vue3";

import {
    PencilSquareIcon,
    TrashIcon,
    EyeIcon,
} from "@heroicons/vue/24/outline";

import Pagination from "@/Components/Pagination/Pagination.vue";

import { useStudentPhoto } from "@/composables/useDefaultStudentPhoto"; 
const { getPhotoUrl, handlePhotoError } = useStudentPhoto();

const props = defineProps({
    students: Object,
    searchValue: String,
    loading: Boolean,
    faculties: Array,
    disciplines: Array,
    classes: Array,
    sessions: Array,
    activeFilters: Object,
});

const emit = defineEmits([
    "search",
    "edit",
    "delete",
    "page-change",
    "filter-change",
    "reset-filters",
]);

const search = ref(props.searchValue || "");
const statusFilter = ref("All");
const filters = ref({
    faculty_id: props.activeFilters?.faculty_id || "",
    discipline_id: props.activeFilters?.discipline_id || "",
    class_id: props.activeFilters?.class_id || "",
    session_id: props.activeFilters?.session_id || "",
});

watch(
    () => props.searchValue,
    (value) => {
        search.value = value || "";
    }
);

watch(
    () => props.activeFilters,
    (value) => {
        filters.value = {
            faculty_id: value?.faculty_id || "",
            discipline_id: value?.discipline_id || "",
            class_id: value?.class_id || "",
            session_id: value?.session_id || "",
        };
    },
    { deep: true }
);

const studentList = computed(() => {
    return props.students?.data || [];
});

const filteredStudents = computed(() => {
    return studentList.value.filter((student) => {
        if (statusFilter.value === "All") {
            return true;
        }

        if (statusFilter.value === "Active") {
            return student.current_enrollment?.status === "Active";
        }

        return student.current_enrollment?.status !== "Active";
    });
});

const hasActiveFilters = computed(() => {
    return Object.values(filters.value).some((value) => Boolean(value));
});

function setFilter(value) {
    statusFilter.value = value;
}

function applyFilter(key, value) {
    const nextFilters = {
        ...filters.value,
        [key]: value,
    };

    filters.value = nextFilters;

    emit("filter-change", nextFilters);
}

const searchStudent = debounce(() => {
    emit("search", search.value);
}, 500);

function resetSearch() {
    search.value = "";
    statusFilter.value = "All";
    filters.value = {
        faculty_id: "",
        discipline_id: "",
        class_id: "",
        session_id: "",
    };

    emit("search", "");
    emit("reset-filters");
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
                    All Students
                </h3>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage registered students
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <!-- Status -->

                <div
                    class="inline-flex rounded-lg bg-gray-100 p-1 dark:bg-gray-900"
                >
                    <button
                        @click="setFilter('All')"
                        :class="[
                            'px-4 py-2 rounded-md text-sm',
                            statusFilter === 'All'
                                ? 'bg-white dark:bg-gray-800 shadow'
                                : 'text-gray-500',
                        ]"
                    >
                        All
                    </button>

                    <button
                        @click="setFilter('Active')"
                        :class="[
                            'px-4 py-2 rounded-md text-sm',
                            statusFilter === 'Active'
                                ? 'bg-white dark:bg-gray-800 shadow'
                                : 'text-gray-500',
                        ]"
                    >
                        Active
                    </button>

                    <button
                        @click="setFilter('Inactive')"
                        :class="[
                            'px-4 py-2 rounded-md text-sm',
                            statusFilter === 'Inactive'
                                ? 'bg-white dark:bg-gray-800 shadow'
                                : 'text-gray-500',
                        ]"
                    >
                        Inactive
                    </button>
                </div>

                <!-- Search -->

                <div class="flex flex-wrap gap-2">
                    <input
                        v-model="search"
                        @input="searchStudent"
                        type="text"
                        placeholder="Search students..."
                        class="h-11 rounded-lg border border-gray-300 px-4 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    />

                    <button
                        v-if="
                            search || statusFilter !== 'All' || hasActiveFilters
                        "
                        @click="resetSearch"
                        class="h-11 rounded-lg bg-gray-200 px-4 text-gray-700 dark:bg-gray-700 dark:text-white"
                    >
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <div
            class="flex flex-wrap gap-3 border-b border-gray-200 px-5 py-4 dark:border-gray-800"
        >
            <select
                :value="filters.faculty_id"
                @change="applyFilter('faculty_id', $event.target.value)"
                class="h-11 min-w-[180px] rounded-lg border border-gray-300 px-3 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
            >
                <option value="">All Faculties</option>
                <option
                    v-for="faculty in faculties"
                    :key="faculty.id"
                    :value="faculty.id"
                >
                    {{ faculty.faculty }}
                </option>
            </select>

            <select
                :value="filters.discipline_id"
                @change="applyFilter('discipline_id', $event.target.value)"
                :disabled="!filters.faculty_id"
                class="h-11 min-w-[180px] rounded-lg border border-gray-300 px-3 text-sm disabled:cursor-not-allowed disabled:opacity-60 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
            >
                <option value="">All Disciplines</option>
                <option
                    v-for="discipline in disciplines"
                    :key="discipline.id"
                    :value="discipline.id"
                >
                    {{ discipline.discipline }}
                </option>
            </select>

            <select
                :value="filters.class_id"
                @change="applyFilter('class_id', $event.target.value)"
                :disabled="!filters.discipline_id"
                class="h-11 min-w-[180px] rounded-lg border border-gray-300 px-3 text-sm disabled:cursor-not-allowed disabled:opacity-60 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
            >
                <option value="">All Classes</option>
                <option
                    v-for="classItem in classes"
                    :key="classItem.id"
                    :value="classItem.id"
                >
                    {{ classItem.name }}
                </option>
            </select>

            <select
                :value="filters.session_id"
                @change="applyFilter('session_id', $event.target.value)"
                class="h-11 min-w-[180px] rounded-lg border border-gray-300 px-3 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
            >
                <option value="">All Sessions</option>
                <option
                    v-for="session in sessions"
                    :key="session.id"
                    :value="session.id"
                >
                    {{ session.session_name }}
                </option>
            </select>
        </div>

        <!-- Table -->

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="p-4 text-left text-xs text-gray-500">#</th>

                        <th class="p-4 text-left text-xs text-gray-500">
                            Photo
                        </th>

                        <th class="p-4 text-left text-xs text-gray-500">
                            Admission No
                        </th>

                        <th class="p-4 text-left text-xs text-gray-500">
                            Name
                        </th>

                        <th class="p-4 text-left text-xs text-gray-500">
                            Father Name
                        </th>

                        <th class="p-4 text-left text-xs text-gray-500">
                            Gender
                        </th>

                        <th class="p-4 text-left text-xs text-gray-500">
                            Class
                        </th>

                        <th class="p-4 text-left text-xs text-gray-500">
                            Contact
                        </th>

                        <!-- <th class="p-4 text-left text-xs text-gray-500">
                            Status
                        </th> -->

                        <th class="p-4 text-center text-xs text-gray-500">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="(student, index) in filteredStudents"
                        :key="student.id"
                        class="border-b border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-900"
                    >
                        <td class="p-4">
                            {{
                                (students.current_page - 1) *
                                    students.per_page +
                                index +
                                1
                            }}
                        </td>

                       <td class="p-4">
    <img
        :src="getPhotoUrl(student)"
        :alt="student.name"
        @error="handlePhotoError($event, student.gender)"
        class="h-10 w-10 rounded-full object-cover"
    />
</td>

                        <td class="p-4">
                            {{ student.admission_no }}
                        </td>

                        <td class="p-4">
                            {{ student.name }}
                        </td>

                        <td class="p-4">
                            {{ student.father_name }}
                        </td>
                        <td class="p-4">
                            {{ student.gender }}
                        </td>

                        <td class="p-4">
                            {{
                                student.current_enrollment?.academic_class
                                    ?.name ?? "-"
                            }}
                        </td>

                        <td class="p-4">
                            <template
                                v-if="
                                    student.contact_number ||
                                    student.guardian_contact_number
                                "
                            >
                                <div v-if="student.contact_number">
                                    <span class="font-medium text-gray-500"
                                        >S:</span
                                    >
                                    {{ student.contact_number }}
                                </div>

                                <div v-if="student.guardian_contact_number">
                                    <span class="font-medium text-gray-500"
                                        >G:</span
                                    >
                                    {{ student.guardian_contact_number }}
                                </div>
                            </template>

                            <span v-else>-</span>
                        </td>

                        <!-- <td class="p-4">
                            <span
                                :class="[
                                    'rounded-full px-3 py-1 text-xs',
                                    student.current_enrollment?.status ===
                                    'Active'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-red-100 text-red-700',
                                ]"
                            >
                                {{
                                    student.current_enrollment?.status ??
                                    "Inactive"
                                }}
                            </span>
                        </td> -->

                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                <Link
                                    :href="
                                        route(
                                            'academic.students.show',
                                            student.id
                                        )
                                    "
                                    class="rounded-lg bg-emerald-100 p-2 text-emerald-600 hover:bg-emerald-200 dark:bg-emerald-900 dark:text-emerald-300"
                                    title="View Profile"
                                >
                                    <EyeIcon class="h-5 w-5" />
                                </Link>

                                <Link
                                    :href="
                                        route(
                                            'academic.student.edit',
                                            student.id
                                        )
                                    "
                                    class="rounded-lg bg-blue-100 p-2 text-blue-600 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300"
                                    title="View Profile"
                                >
                                    <PencilSquareIcon class="h-5 w-5" />
                                </Link>

                                <!-- <button
                                    @click="emit('delete', student.id)"
                                    class="rounded-lg bg-red-100 p-2 text-red-600 hover:bg-red-200 dark:bg-red-900 dark:text-red-300"
                                    title="Delete"
                                >
                                    <TrashIcon class="h-5 w-5" />
                                </button> -->
                            </div>
                        </td>
                    </tr>

                    <tr v-if="filteredStudents.length === 0">
                        <td colspan="9" class="p-6 text-center text-gray-500">
                            No students found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->

        <Pagination
            :pagination="students"
            @page-change="emit('page-change', $event)"
        />
    </div>
</template>
