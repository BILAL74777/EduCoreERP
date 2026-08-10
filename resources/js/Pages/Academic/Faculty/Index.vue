<script setup>
import Master from "@/Layouts/Master.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";

import Modal from "@/Components/Modal/Modal.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import DeleteConfirmModal from "@/Components/Modal/DeleteConfirmModal.vue";

import FacultyTable from "./FacultyTable.vue";
import FacultyForm from "./FacultyForm.vue";

const showFormModal = ref(false);
const showDeleteModal = ref(false);

const editing = ref(false);
const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);

const faculties = ref({});
const selectedFaculty = ref(null);

const search = ref("");
const errors = ref({});

const form = ref({
    id: null,
    faculty: "",
    is_active: true,
});

/*
|--------------------------------------------------------------------------
| Fetch Faculties
|--------------------------------------------------------------------------
*/

async function getFaculties(page = 1) {
    loading.value = true;

    try {
        const response = await axios.get("/api/academic/faculties", {
            params: {
                page,
                search: search.value,
            },
        });

        faculties.value = response.data;
    } catch (error) {
        console.error(error);
        toast.error("Failed to load faculties.");
    } finally {
        loading.value = false;
    }
}

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

function searchFaculties(value) {
    search.value = value;

    getFaculties();
}

/*
|--------------------------------------------------------------------------
| Create
|--------------------------------------------------------------------------
*/

function openCreateModal() {
    clearErrors();

    editing.value = false;

    form.value = {
        id: null,
        faculty: "",
        is_active: true,
    };

    showFormModal.value = true;
}

/*
|--------------------------------------------------------------------------
| Edit
|--------------------------------------------------------------------------
*/

function editFaculty(faculty) {
    clearErrors();

    editing.value = true;

    form.value = {
        id: faculty.id,
        faculty: faculty.faculty ?? "",
        is_active: faculty.is_active,
    };

    showFormModal.value = true;
}

/*
|--------------------------------------------------------------------------
| Save
|--------------------------------------------------------------------------
*/

async function saveFaculty() {
    saving.value = true;

    try {
        errors.value = {};

        if (editing.value) {
            await axios.put(
                `/api/academic/faculties/${form.value.id}`,
                form.value
            );
        } else {
            await axios.post("/api/academic/faculties", form.value);
        }

        toast.success(
            `Faculty ${editing.value ? "updated" : "created"} successfully`
        );

        showFormModal.value = false;

        await getFaculties();
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;

            toast.error("Please fix the validation errors.");
        } else {
            toast.error("Failed to save faculty.");
        }

        console.error(error);
    } finally {
        saving.value = false;
    }
}

/*
|--------------------------------------------------------------------------
| Delete
|--------------------------------------------------------------------------
*/

function askDelete(faculty) {
    selectedFaculty.value = faculty;

    showDeleteModal.value = true;
}

async function deleteFaculty() {
    deleting.value = true;

    try {
        await axios.delete(
            `/api/academic/faculties/${selectedFaculty.value.id}`
        );

        toast.success("Faculty deleted successfully.");

        showDeleteModal.value = false;

        await getFaculties();
    } catch (error) {
        console.error(error);

        toast.error("Failed to delete faculty.");
    } finally {
        deleting.value = false;
    }
}

/*
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
*/

async function toggleStatus(faculty) {
    try {
        await axios.patch(`/api/academic/faculties/${faculty.id}/status`);

        toast.success("Status updated.");

        await getFaculties();
    } catch (error) {
        console.error(error);

        toast.error("Unable to update status.");
    }
}

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

function clearError(field) {
    delete errors.value[field];
}

function clearErrors() {
    errors.value = {};
}

/*
|--------------------------------------------------------------------------
| Init
|--------------------------------------------------------------------------
*/

onMounted(async () => {
    await getFaculties();
});
</script>

<template>
    <Head title="Faculties" />

    <Master>
        <!-- Header -->
        <div class="space-y-6 flex items-start justify-between">
            <!-- Left -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Faculty Management
                </h1>

                <nav class="mt-2 flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
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

        <!-- Card -->
        <div class="rounded-lg bg-white dark:bg-gray-800">
            <!-- Loading -->
            <div v-if="loading" class="flex justify-center items-center py-12">
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
                v-else
                :faculties="faculties"
                :search-value="search"
                @search="searchFaculties"
                @page-change="getFaculties"
                @edit="editFaculty"
                @delete="askDelete"
                @status="toggleStatus"
            />
        </div>

        <!-- Form Modal -->
        <Modal :show="showFormModal" @close="showFormModal = false">
            <div class="p-6">
                <h2 class="mb-5 text-xl font-bold">
                    {{ editing ? "Update Faculty" : "Add Faculty" }}
                </h2>

                <FacultyForm
                    :form="form"
                    :errors="errors"
                    @clear-error="clearError"
                />

                <div class="mt-6 flex justify-end">
                    <SuccessButton @click="saveFaculty" :loading="saving">
                        {{
                            saving
                                ? editing
                                    ? "Updating..."
                                    : "Saving..."
                                : editing
                                ? "Update"
                                : "Save"
                        }}
                    </SuccessButton>
                </div>
            </div>
        </Modal>

        <!-- Delete Modal -->
        <DeleteConfirmModal
            :show="showDeleteModal"
            :loading="deleting"
            @close="showDeleteModal = false"
            @confirm="deleteFaculty"
        />
    </Master>
</template>
