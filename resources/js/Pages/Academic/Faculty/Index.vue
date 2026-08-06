<script setup>
import Master from "@/Layouts/Master.vue";
import { Head } from "@inertiajs/vue3";
import { ref, reactive, onMounted, watch } from "vue";
import axios from "axios";
import debounce from "lodash/debounce";
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
    name: "",
    description: "",
    is_active: true,
});

const errors = reactive({
    name: "",
    description: "",
});

const pagination = ref({});

const sortField = ref("created_at");
const sortDirection = ref("desc");

const clearErrors = () => {
    errors.name = "";
    errors.description = "";
};

const resetForm = () => {
    form.id = "";
    form.name = "";
    form.description = "";
    form.is_active = true;

    clearErrors();
};

const openCreateModal = () => {
    resetForm();
    editMode.value = false;
    showForm.value = true;
};

const closeModal = () => {
    showForm.value = false;
    resetForm();
};

const editFaculty = (faculty) => {
    resetForm();

    editMode.value = true;

    form.id = faculty.id;
    form.name = faculty.name;
    form.description = faculty.description;
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

watch(
    searchValue,
    debounce(() => {
        fetchFaculties();
    }, 500)
);

onMounted(() => {
    fetchFaculties();
});
</script>
<template>
    <Head title="Faculties" />

    <Master>
        <div class="space-y-6">
            <!-- Header -->
            <div
                class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl font-bold text-gray-900 dark:text-white"
                    >
                        Faculties
                    </h1>

                    <p class="text-gray-500 dark:text-gray-400 mt-1">
                        Manage institution faculties
                    </p>
                </div>

                <button
                    @click="openCreateModal"
                    class="rounded-lg bg-brand-500 hover:bg-brand-600 px-5 py-3 text-white font-medium transition"
                >
                    + Add Faculty
                </button>
            </div>

            <!-- Search -->
            <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-5">
                <input
                    v-model="searchValue"
                    type="text"
                    placeholder="Search Faculty..."
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white px-4 py-3 focus:ring focus:ring-brand-300"
                />
            </div>

            <!-- Table -->

            <FacultyTable
                :faculties="faculties"
                :loading="loading"
                :sort-field="sortField"
                :sort-direction="sortDirection"
                @edit="editFaculty"
                @delete="confirmDelete"
                @status="toggleStatus"
                @sort="changeSorting"
            />

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
