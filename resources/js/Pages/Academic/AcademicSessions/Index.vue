<script setup>
import Master from "@/Layouts/Master.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";

import Modal from "@/Components/Modal/Modal.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import DeleteConfirmModal from "@/Components/Modal/DeleteConfirmModal.vue";

import AcademicSessionTable from "./AcademicSessionTable.vue";
import AcademicSessionForm from "./AcademicSessionForm.vue";

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);

const sessions = ref({});

const showFormModal = ref(false);
const showDeleteModal = ref(false);

const editing = ref(false);

const selectedSession = ref(null);

const search = ref("");

const errors = ref({});

const form = ref({
    id: null,
    session_name: "",
    start_date: "",
    end_date: "",
    is_active: false,
    remarks: "",
});

/*
|--------------------------------------------------------------------------
| Fetch Sessions
|--------------------------------------------------------------------------
*/

async function getSessions(page = 1) {
    loading.value = true;

    try {
        const response = await axios.get("/api/academic/sessions", {
            params: {
                page,
                search: search.value,
            },
        });

        sessions.value = response.data;
    } catch (error) {
        console.error(error);
        toast.error("Failed to load academic sessions.");
    } finally {
        loading.value = false;
    }
}

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

function searchSessions(value) {
    search.value = value;
    getSessions();
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
        session_name: "",
        start_date: "",
        end_date: "",
        is_active: false,
        remarks: "",
    };

    showFormModal.value = true;
}

/*
|--------------------------------------------------------------------------
| Edit
|--------------------------------------------------------------------------
*/

function editSession(session) {
    clearErrors();

    editing.value = true;

    form.value = {
        id: session.id,
        session_name: session.session_name,
        start_date: session.start_date,
        end_date: session.end_date,
        is_active: session.is_active,
        remarks: session.remarks,
    };

    showFormModal.value = true;
}

/*
|--------------------------------------------------------------------------
| Save
|--------------------------------------------------------------------------
*/

async function saveSession() {
    saving.value = true;

    try {
        errors.value = {};

        if (editing.value) {
            await axios.put(
                `/api/academic/sessions/${form.value.id}`,
                form.value
            );
        } else {
            await axios.post("/api/academic/sessions", form.value);
        }

        toast.success(
            `Academic Session ${
                editing.value ? "updated" : "created"
            } successfully.`
        );

        showFormModal.value = false;

        getSessions();
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
            toast.error("Please correct the validation errors.");
        } else {
            toast.error("Failed to save academic session.");
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
    selectedSession.value = id;
    showDeleteModal.value = true;
}

async function deleteSession() {
    deleting.value = true;

    try {
        await axios.delete(`/api/academic/sessions/${selectedSession.value}`);

        toast.success("Academic Session deleted successfully.");

        showDeleteModal.value = false;

        getSessions();
    } catch (error) {
        console.error(error);
        toast.error("Failed to delete academic session.");
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
| Initialize
|--------------------------------------------------------------------------
*/

onMounted(() => {
    getSessions();
});
</script>

<template>
    <Head title="Academic Sessions" />

    <Master>
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Academic Sessions Management
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
                                Academic Sessions
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

            <div
                v-if="loading"
                class="flex justify-center py-12"
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

            <!-- Table -->

            <AcademicSessionTable
                v-else
                :sessions="sessions"
                :search-value="search"
                :show-delete-modal="showDeleteModal"
                :deleting="deleting"
                @search="searchSessions"
                @page-change="getSessions"
                @edit="editSession"
                @delete="askDelete"
                @confirm-delete="deleteSession"
                @close-delete="showDeleteModal = false"
            />
        </div>

        <!-- Form Modal -->

        <Modal
            :show="showFormModal"
            @close="showFormModal = false"
        >
            <div class="p-6">

                <h2 class="mb-5 text-xl font-bold">
                    {{
                        editing
                            ? "Update Academic Session"
                            : "Create Academic Session"
                    }}
                </h2>

                <AcademicSessionForm
                    :form="form"
                    :errors="errors"
                    :editing="editing"
                    @clear-error="clearError"
                />

                <div class="mt-6 flex justify-end">
                    <SuccessButton
                        @click="saveSession"
                        :loading="saving"
                    >
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

        <!-- Delete -->

        <DeleteConfirmModal
            :show="showDeleteModal"
            :loading="deleting"
            @close="showDeleteModal = false"
            @confirm="deleteSession"
        />

    </Master>
</template>
