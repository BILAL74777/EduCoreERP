<script setup>
import { ref, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";
import { toast } from "vue3-toastify";

import Master from "@/Layouts/Master.vue";
import StudentTable from "./StudentTable.vue";

const students = ref({});
const searchValue = ref("");
const loading = ref(false);
const faculties = ref([]);
const disciplines = ref([]);
const classes = ref([]);
const sessions = ref([]);
const filters = ref({
    faculty_id: "",
    discipline_id: "",
    class_id: "",
    session_id: "",
});

/*
|--------------------------------------------------------------------------
| Fetch Students
|--------------------------------------------------------------------------
*/

async function loadDropdowns() {
    try {
        const response = await axios.get("/api/academic/students/dropdowns");

        faculties.value = response.data.faculties || [];
        sessions.value = response.data.sessions || [];
    } catch (error) {
        console.error(error);
        toast.error("Failed to load filter options");
    }
}

async function loadDisciplines(facultyId) {
    if (!facultyId) {
        disciplines.value = [];
        classes.value = [];
        return;
    }

    try {
        const response = await axios.get(
            `/api/academic/students/disciplines/${facultyId}`
        );

        disciplines.value = response.data.disciplines || [];
        classes.value = [];
    } catch (error) {
        console.error(error);
        toast.error("Failed to load disciplines");
    }
}

async function loadClasses(disciplineId) {
    if (!disciplineId) {
        classes.value = [];
        return;
    }

    try {
        const response = await axios.get(
            `/api/academic/students/classes/${disciplineId}`
        );

        classes.value = response.data.classes || [];
    } catch (error) {
        console.error(error);
        toast.error("Failed to load classes");
    }
}

async function fetchStudents(page = 1, search = "", activeFilters = filters.value) {
    try {
        loading.value = true;

        const params = {
            page,
        };

        if (search) {
            params.search = search;
        }

        if (activeFilters.faculty_id) {
            params.faculty_id = activeFilters.faculty_id;
        }

        if (activeFilters.discipline_id) {
            params.discipline_id = activeFilters.discipline_id;
        }

        if (activeFilters.class_id) {
            params.class_id = activeFilters.class_id;
        }

        if (activeFilters.session_id) {
            params.session_id = activeFilters.session_id;
        }

        const response = await axios.get("/api/academic/students", {
            params,
        });

        students.value = response.data;
    } catch (error) {
        console.error(error);

        toast.error("Failed to load students");
    } finally {
        loading.value = false;
    }
}

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

function searchStudents(value) {
    searchValue.value = value;

    fetchStudents(1, value, filters.value);
}

/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

function changePage(page) {
    fetchStudents(page, searchValue.value, filters.value);
}

/*
|--------------------------------------------------------------------------
| Create
|--------------------------------------------------------------------------
*/

function createStudent() {
    router.visit("/academic/students/create");
}

/*
|--------------------------------------------------------------------------
| Edit
|--------------------------------------------------------------------------
*/

function editStudent(student) {
    alert("called");
    router.visit(`academic/students/show/${student.id}`);
}

/*
|--------------------------------------------------------------------------
| Delete
|--------------------------------------------------------------------------
*/

async function deleteStudent(id) {
    if (!confirm("Are you sure you want to delete this student?")) {
        return;
    }

    try {
        await axios.delete(`/api/academic/students/${id}`);

        toast.success("Student deleted successfully");

        fetchStudents(students.value.current_page, searchValue.value, filters.value);
    } catch (error) {
        toast.error("Delete failed");
    }
}

async function applyFilters(nextFilters) {
    if (nextFilters.faculty_id !== filters.value.faculty_id) {
        nextFilters.discipline_id = "";
        nextFilters.class_id = "";
        disciplines.value = [];
        classes.value = [];

        if (nextFilters.faculty_id) {
            await loadDisciplines(nextFilters.faculty_id);
        }
    } else if (nextFilters.discipline_id !== filters.value.discipline_id) {
        nextFilters.class_id = "";
        classes.value = [];

        if (nextFilters.discipline_id) {
            await loadClasses(nextFilters.discipline_id);
        }
    }

    filters.value = nextFilters;

    fetchStudents(1, searchValue.value, filters.value);
}

function resetFilters() {
    filters.value = {
        faculty_id: "",
        discipline_id: "",
        class_id: "",
        session_id: "",
    };

    disciplines.value = [];
    classes.value = [];

    fetchStudents(1, searchValue.value, filters.value);
}

onMounted(() => {
    loadDropdowns();
    fetchStudents();
});
</script>

<template>
    <Master>
        <div class="space-y-6">
            <!-- Header -->

            <div class="flex items-center justify-between">
                <div>
                    <h1
                        class="text-xl font-semibold text-gray-800 dark:text-white"
                    >
                        Students
                    </h1>

                    <p class="text-sm text-gray-500">Manage students</p>
                </div>

                <button
                    @click="createStudent"
                    class="rounded-lg bg-blue-600 px-5 py-2 text-white hover:bg-blue-700"
                >
                    Add Student
                </button>
            </div>

            <!-- Table -->

            <StudentTable
                :students="students"
                :search-value="searchValue"
                :loading="loading"
                :faculties="faculties"
                :disciplines="disciplines"
                :classes="classes"
                :sessions="sessions"
                :active-filters="filters"
                @search="searchStudents"
                @page-change="changePage"
                @edit="editStudent"
                @delete="deleteStudent"
                @filter-change="applyFilters"
                @reset-filters="resetFilters"
            />
        </div>
    </Master>
</template>
