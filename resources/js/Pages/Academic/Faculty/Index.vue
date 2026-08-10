<script setup>
import Master from "@/Layouts/Master.vue";
import { Head } from "@inertiajs/vue3";
import { ref, reactive, onMounted } from "vue";
import axios from "axios";

import { toast } from "vue3-toastify";

import FacultyTable from "./FacultyTable.vue";
import FacultyForm from "./FacultyForm.vue";

import DeleteConfirmModal from "@/Components/Modal/DeleteConfirmModal.vue";

const faculties = ref({});

const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);

const showForm = ref(false);
const editMode = ref(false);

const showDeleteModal = ref(false);

const selectedFaculty = ref(null);

const searchValue = ref("");

const form = reactive({
    id: "",
    faculty: "",
    is_active: true,
});

const errors = reactive({
    faculty: "",
});

const pagination = ref({});

const sortField = ref("created_at");
const sortDirection = ref("desc");

const clearErrors = () => {
    errors.faculty = "";
};

const resetForm = () => {
    form.id = "";
    form.faculty = "";
    form.is_active = true;

    clearErrors();
};

const openCreateModal = () => {
    resetForm();
    clearErrors();
    editMode.value = false;
    showForm.value = true;
};

const closeModal = () => {
    showForm.value = false;
    resetForm();
    clearErrors();
};

const editFaculty = (faculty) => {
    resetForm();

    clearErrors();
    editMode.value = true;

    form.id = faculty.id;
    form.faculty = faculty.faculty;
    form.is_active = faculty.is_active;

    showForm.value = true;
};

const fetchFaculties = async (page = 1) => {
    loading.value = true;

    try {
        const response = await axios.get("/api/academic/faculties", {
            params: {
                page,
                search: searchValue.value,
                sort_field: sortField.value,
                sort_direction: sortDirection.value,
            },
        });

        faculties.value = response.data;

        pagination.value = response.data.pagination;
    } catch (error) {
        toast.error("Unable to load faculties.");
    } finally {
        loading.value = false;
    }
};

const saveFaculty = async () => {
    saving.value = true;

    clearErrors();

    try {
        if (editMode.value) {
            await axios.put(`/api/academic/faculties/${form.id}`, form);

            toast.success("Faculty updated successfully.");
        } else {
            await axios.post("/api/academic/faculties", form);

            toast.success("Faculty created successfully.");
        }

        showForm.value = false;

        fetchFaculties(pagination.value.current_page ?? 1);
    } catch (error) {
        if (error.response?.status === 422) {
            Object.assign(errors, error.response.data.errors);
        } else {
            toast.error("Something went wrong.");
        }
    } finally {
        saving.value = false;
    }
};

const confirmDelete = (faculty) => {
    selectedFaculty.value = faculty;

    showDeleteModal.value = true;
};

const deleteFaculty = async () => {
    deleting.value = true;

    try {
        await axios.delete(
            `/api/academic/faculties/${selectedFaculty.value.id}`
        );

        toast.success("Faculty deleted successfully.");

        showDeleteModal.value = false;

        fetchFaculties(pagination.value.current_page ?? 1);
    } catch (error) {
        toast.error("Unable to delete faculty.");
    } finally {
        deleting.value = false;
    }
};

const toggleStatus = async (faculty) => {
    try {
        await axios.patch(`/api/academic/faculties/${faculty.id}/status`);

        toast.success("Status updated.");

        fetchFaculties(pagination.value.current_page ?? 1);
    } catch (error) {
        toast.error("Unable to update status.");
    }
};

const changeSorting = (field) => {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortField.value = field;

        sortDirection.value = "asc";
    }

    fetchFaculties();
};

const changePage = (page) => {
    fetchFaculties(page);
};

const searchFaculties = (value) => {
    searchValue.value = value;
    fetchFaculties();
};

onMounted(() => {
    fetchFaculties();
});
</script>
<template>
    <Head title="Faculties" />

    <Master>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-start justify-between">
                <!-- Left -->
                <div>
                    <h1
                        class="text-2xl font-bold text-gray-900 dark:text-white"
                    >
                        Faculty Management
                    </h1>

                    <nav class="mt-2 flex" aria-label="Breadcrumb">
                        <ol
                            class="inline-flex items-center space-x-1 md:space-x-3"
                        >
                            <!-- Home -->
                            <li class="inline-flex items-center">
                                <a
                                    href="#"
                                    class="text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
                                >
                                    Home
                                </a>
                            </li>

                            <!-- Faculties -->
                            <li>
                                <div class="flex items-center">
                                    <svg
                                        class="h-4 w-4 text-gray-400"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>

                                    <span
                                        class="ml-1 text-sm font-medium text-gray-700 dark:text-gray-400"
                                    >
                                        Faculties
                                    </span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <!-- Right -->
                <button
                    type="button"
                    @click="openCreateModal"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                >
                    Add New
                </button>
            </div>

            <!-- Table -->

            <!-- Card -->
            <div class="rounded-lg bg-white dark:bg-gray-800">
                <!-- Loading -->
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-12"
                >
                    <svg
                        class="h-10 w-10 animate-spin text-blue-600"
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
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                        />
                    </svg>
                </div>

                <!-- Empty -->
                <div
                    v-else-if="!faculties.data || faculties.data.length === 0"
                    class="py-10 text-center text-gray-500"
                >
                    No faculties found.
                </div>

                <!-- Table -->
                <FacultyTable
                    :faculties="faculties"
                    :search-value="searchValue"
                    @search="searchFaculties"
                    @page-change="changePage"
                    @edit="editFaculty"
                    @delete="confirmDelete"
                    @status="toggleStatus"
                />
            </div>

            <!-- Pagination -->

            <Pagination
                v-if="pagination.total > 0"
                :pagination="pagination"
                @page-change="changePage"
            />

            <!-- Form -->

            <FacultyForm
                :show="showForm"
                :edit-mode="editMode"
                :loading="saving"
                :form="form"
                :errors="errors"
                @close="closeModal"
                @save="saveFaculty"
            />

            <!-- Delete -->

            <DeleteConfirmModal
                :show="showDeleteModal"
                :loading="deleting"
                @close="showDeleteModal = false"
                @confirm="deleteFaculty"
            />
        </div>
    </Master>
</template>
