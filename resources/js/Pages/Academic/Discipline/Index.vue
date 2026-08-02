<script setup>
import Master from "@/Layouts/Master.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";

import Modal from "@/Components/Modal/Modal.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import DeleteConfirmModal from "@/Components/Modal/DeleteConfirmModal.vue";

import DisciplineTable from "./DisciplineTable.vue";
import DisciplineForm from "./DisciplineForm.vue";

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const showFormModal = ref(false);
const showDeleteModal = ref(false);

const editing = ref(false);
const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);

const disciplines = ref({});
const faculties = ref([]);

const selectedDiscipline = ref(null);

const search = ref("");

const errors = ref({});

const form = ref({
    id: null,
    faculty_id: "",
    code: "",
    name: "",
    duration_years: 4,
    is_active: true,
    description: "",
});

/*
|--------------------------------------------------------------------------
| Fetch Disciplines
|--------------------------------------------------------------------------
*/

async function getDisciplines(page = 1) {
    loading.value = true;

    try {
        const response = await axios.get("/api/academic/disciplines", {
            params: {
                page,
                search: search.value,
            },
        });

        disciplines.value = response.data;
    } catch (error) {
        console.error(error);
        toast.error("Failed to load disciplines.");
    } finally {
        loading.value = false;
    }
}

/*
|--------------------------------------------------------------------------
| Fetch Faculties
|--------------------------------------------------------------------------
*/

async function getFaculties() {
    try {
        const response = await axios.get("/api/academic/faculties/dropdown");

        faculties.value = response.data.faculties;
    } catch (error) {
        console.error(error);
        toast.error("Failed to load faculties");
    }
}

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

function searchDisciplines(value) {
    search.value = value;

    getDisciplines();
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
        faculty_id: "",
        code: "",
        name: "",
        duration_years: 4,
        is_active: true,
        description: "",
    };

    showFormModal.value = true;
}

/*
|--------------------------------------------------------------------------
| Edit
|--------------------------------------------------------------------------
*/

function editDiscipline(discipline) {
    clearErrors();

    editing.value = true;

    form.value = {
        id: discipline.id,
        faculty_id: discipline.faculty_id,
        code: discipline.code,
        name: discipline.name,
        duration_years: discipline.duration_years,
        is_active: discipline.is_active,
        description: discipline.description,
    };

    showFormModal.value = true;
}

/*
|--------------------------------------------------------------------------
| Save
|--------------------------------------------------------------------------
*/

async function saveDiscipline() {
    saving.value = true;

    try {
        errors.value = {};

        if (editing.value) {
            await axios.put(
                `/api/academic/disciplines/${form.value.id}`,
                form.value
            );
        } else {
            await axios.post("/api/academic/disciplines", form.value);
        }

        toast.success(
            `Discipline ${editing.value ? "updated" : "created"} successfully`
        );

        showFormModal.value = false;

        await getDisciplines();
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;

            toast.error("Please fix the validation errors.");
        } else {
            toast.error("Failed to save discipline.");
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

function askDelete(id) {
    selectedDiscipline.value = id;

    showDeleteModal.value = true;
}

async function deleteDiscipline() {
    deleting.value = true;

    try {
        await axios.delete(
            `/api/academic/disciplines/${selectedDiscipline.value}`
        );

        toast.success("Discipline deleted successfully.");

        showDeleteModal.value = false;

        await getDisciplines();
    } catch (error) {
        console.error(error);

        toast.error("Failed to delete discipline.");
    } finally {
        deleting.value = false;
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
    await Promise.all([getDisciplines(), getFaculties()]);
});
</script>
<template>
    <Head title="Disciplines" />

    <Master>
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Discipline Management
            </h1>

            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a
                            href="#"
                            class="text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
                        >
                            Home
                        </a>
                    </li>

                    <li>
                        <div class="flex items-center">
                            <svg
                                class="w-4 h-4 text-gray-400"
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
                                Disciplines
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Card -->
        <div class="mt-10 rounded-lg bg-white p-6 shadow dark:bg-gray-800">
            <div class="mb-6 flex justify-end">
                <button
                    @click="openCreateModal"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                >
                    Add New
                </button>
            </div>

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
                v-else-if="!disciplines.data || disciplines.data.length === 0"
                class="py-10 text-center text-gray-500"
            >
                No disciplines found.
            </div>

            <!-- Table -->
            <DisciplineTable
                v-else
                :disciplines="disciplines"
                :search-value="search"
                :show-delete-modal="showDeleteModal"
                :deleting="deleting"
                @search="searchDisciplines"
                @page-change="getDisciplines"
                @edit="editDiscipline"
                @delete="askDelete"
                @confirm-delete="deleteDiscipline"
                @close-delete="showDeleteModal = false"
            />
        </div>

        <!-- Form Modal -->
        <Modal :show="showFormModal" @close="showFormModal = false">
            <div class="p-6">
                <h2 class="mb-5 text-xl font-bold">
                    {{ editing ? "Update Discipline" : "Add Discipline" }}
                </h2>

                <DisciplineForm
                    :form="form"
                    :errors="errors"
                    :editing="editing"
                    :saving="saving"
                    :faculties="faculties"
                    @save="saveDiscipline"
                    @clear-error="clearError"
                />

                <div class="mt-6 flex justify-end">
                    <SuccessButton @click="saveDiscipline" :loading="saving">
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
            @confirm="deleteDiscipline"
        />
    </Master>
</template>
