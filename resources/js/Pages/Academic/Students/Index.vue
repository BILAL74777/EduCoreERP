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

/*
|--------------------------------------------------------------------------
| Fetch Students
|--------------------------------------------------------------------------
*/

async function fetchStudents(page = 1, search = "") {
    try {
        loading.value = true;

        const response = await axios.get("/api/academic/students", {
            params: {
                page,
                search,
            },
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

    fetchStudents(1, value);
}

/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

function changePage(page) {
    fetchStudents(page, searchValue.value);
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

        fetchStudents(students.value.current_page, searchValue.value);
    } catch (error) {
        toast.error("Delete failed");
    }
}

onMounted(() => {
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
                @search="searchStudents"
                @page-change="changePage"
                @edit="editStudent"
                @delete="deleteStudent"
            />
        </div>
    </Master>
</template>
