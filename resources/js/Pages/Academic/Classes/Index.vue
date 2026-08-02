<script setup>
import Master from "@/Layouts/Master.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";

import Modal from "@/Components/Modal/Modal.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import DeleteConfirmModal from "@/Components/Modal/DeleteConfirmModal.vue";

import AcademicClassTable from "./AcademicClassTable.vue";
import AcademicClassForm from "./AcademicClassForm.vue";

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

const academicClasses = ref({});
const disciplines = ref([]);
const nextClasses = ref([]);

const selectedClass = ref(null);

const search = ref("");

const errors = ref({});

const form = ref({
    id: null,
    discipline_id: "",
    code: "",
    name: "",
    level: 1,
    next_class_id: "",
    is_active: true,
    description: "",
});

/*
|--------------------------------------------------------------------------
| Fetch Academic Classes
|--------------------------------------------------------------------------
*/

async function getAcademicClasses(page = 1) {
    loading.value = true;

    try {
        const response = await axios.get("/api/academic/classes", {
            params: {
                page,
                search: search.value,
            },
        });

        academicClasses.value = response.data;
    } catch (error) {
        console.error(error);
        toast.error("Failed to load academic classes.");
    } finally {
        loading.value = false;
    }
}

/*
|--------------------------------------------------------------------------
| Fetch Disciplines
|--------------------------------------------------------------------------
*/

async function getDisciplines() {
    try {
        const response = await axios.get("/api/academic/disciplines/dropdown");

        disciplines.value = response.data.disciplines;
    } catch (error) {
        console.error(error);
        toast.error("Failed to load disciplines.");
    }
}

/*
|--------------------------------------------------------------------------
| Fetch Next Classes
|--------------------------------------------------------------------------
*/

async function getNextClasses() {
    try {
        const response = await axios.get("/api/academic/classes/dropdown");

        nextClasses.value = response.data.classes;
    } catch (error) {
        console.error(error);
        toast.error("Failed to load classes.");
    }
}

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

function searchAcademicClasses(value) {
    search.value = value;

    getAcademicClasses();
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
        discipline_id: "",
        code: "",
        name: "",
        level: 1,
        next_class_id: "",
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

function editAcademicClass(item) {
    clearErrors();

    editing.value = true;

    form.value = {
        id: item.id,
        discipline_id: item.discipline_id,
        code: item.code,
        name: item.name,
        level: item.level,
        next_class_id: item.next_class_id,
        is_active: item.is_active,
        description: item.description,
    };

    showFormModal.value = true;
}

/*
|--------------------------------------------------------------------------
| Save
|--------------------------------------------------------------------------
*/

async function saveAcademicClass() {
    saving.value = true;

    try {
        errors.value = {};

        if (editing.value) {
            await axios.put(
                `/api/academic/classes/${form.value.id}`,
                form.value
            );
        } else {
            await axios.post("/api/academic/classes", form.value);
        }

        toast.success(
            `Academic Class ${
                editing.value ? "updated" : "created"
            } successfully`
        );

        showFormModal.value = false;

        await getAcademicClasses();
        await getNextClasses();
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;

            toast.error("Please fix the validation errors.");
        } else {
            toast.error("Failed to save academic class.");
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
    selectedClass.value = id;

    showDeleteModal.value = true;
}

async function deleteAcademicClass() {
    deleting.value = true;

    try {
        await axios.delete(`/api/academic/classes/${selectedClass.value}`);

        toast.success("Academic Class deleted successfully.");

        showDeleteModal.value = false;

        await getAcademicClasses();
        await getNextClasses();
    } catch (error) {
        console.error(error);

        toast.error("Failed to delete academic class.");
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
    await Promise.all([
        getAcademicClasses(),
        getDisciplines(),
        getNextClasses(),
    ]);
});
</script>

<template>
    <Head title="Academic Classes" />

    <Master>
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Academic Classes Management
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
                                Academic Classes
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
            <div v-if="loading" class="flex justify-center items-center py-10">
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
                v-else-if="
                    !academicClasses.data || academicClasses.data.length === 0
                "
                class="py-10 text-center text-gray-500"
            >
                No academic classes found.
            </div>

            <!-- Table -->
            <AcademicClassTable
                v-else
                :academic-classes="academicClasses"
                :search-value="search"
                :show-delete-modal="showDeleteModal"
                :deleting="deleting"
                @search="searchAcademicClasses"
                @page-change="getAcademicClasses"
                @edit="editAcademicClass"
                @delete="askDelete"
                @confirm-delete="deleteAcademicClass"
                @close-delete="showDeleteModal = false"
            />
        </div>

        <!-- Create / Update Modal -->
        <Modal :show="showFormModal" @close="showFormModal = false">
            <div class="p-6">
                <h2 class="mb-5 text-xl font-bold">
                    {{
                        editing ? "Update Academic Class" : "Add Academic Class"
                    }}
                </h2>

                <AcademicClassForm
                    :form="form"
                    :errors="errors"
                    :editing="editing"
                    :saving="saving"
                    :disciplines="disciplines"
                    :next-classes="nextClasses"
                    @clear-error="clearError"
                />

                <div class="mt-6 flex justify-end">
                    <SuccessButton @click="saveAcademicClass" :loading="saving">
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
            @confirm="deleteAcademicClass"
        />
    </Master>
</template>
