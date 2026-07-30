<script setup>
import Master from "@/Layouts/Master.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";

import Modal from "@/Components/Modal.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import DeleteConfirmModal from "@/Components/DeleteConfirmModal.vue";

import UserTable from "./UserTable.vue";
import UserForm from "./UserForm.vue";

const users = ref({});
const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);

const showFormModal = ref(false);
const showDeleteModal = ref(false);

const editing = ref(false);

const selectedUser = ref(null);

const search = ref("");

const errors = ref({});

const form = ref({
    id: null,
    name: "",
    email: "",
    password: "",
    password_confirmation: "", 
    status: 1,
    image: null,
});

async function getUsers(page = 1) {
    loading.value = true;

    try {
        const response = await axios.get("/api/users", {
            params: {
                page,
                search: search.value,
            },
        });

        users.value = response.data.users;
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
}

function searchUsers(value) {
    search.value = value;
    getUsers();
}

function clearErrors() {
    errors.value = {};
}

function clearError(field) {
    delete errors.value[field];
}

function resetForm() {
    form.value = {
        id: null,
        name: "",
        email: "",
        password: "",
        password_confirmation: "", 
        status: 1,
        image: null,
    };
}

function openCreateModal() {
    editing.value = false;
    clearErrors();
    resetForm();

    showFormModal.value = true;
}

function editUser(user) {

    editing.value = true;

    clearErrors();

    form.value = {
        id: user.id,
        name: user.name,
        email: user.email,
        password: "",
        password_confirmation: "", 
        status: user.status,
        image: null,
    };

    showFormModal.value = true;
}

async function saveUser() {

    saving.value = true;

    try {

        errors.value = {};

        const data = new FormData();

        data.append("name", form.value.name);
        data.append("email", form.value.email); 
        data.append("status", form.value.status);

        if (form.value.password) {
            data.append("password", form.value.password);
            data.append(
                "password_confirmation",
                form.value.password_confirmation
            );
        }

        if (form.value.image) {
            data.append("image", form.value.image);
        }

        if (editing.value) {

            await axios.post(
                `/api/users/${form.value.id}`,
                data
            );

        } else {

            await axios.post(
                "/api/users",
                data
            );

        }

        toast.success(
            `User ${editing.value ? "updated" : "created"} successfully`
        );

        showFormModal.value = false;

        getUsers();

    } catch (error) {

        if (error.response?.status === 422) {

            errors.value = error.response.data.errors;

            toast.error("Please fix the validation errors.");

        } else {

            toast.error("Something went wrong.");

        }

    } finally {

        saving.value = false;

    }

}

function askDelete(id) {

    selectedUser.value = id;

    showDeleteModal.value = true;

}

async function deleteUser() {

    deleting.value = true;

    try {

        await axios.delete(
            `/api/users/${selectedUser.value}`
        );

        toast.success("User deleted successfully");

        showDeleteModal.value = false;

        getUsers();

    } catch (error) {

        toast.error("Failed to delete user");

    } finally {

        deleting.value = false;

    }

}

onMounted(() => {

    getUsers();

});
</script>

<template>

    <Head title="Users" />

    <Master>

        <div class="mb-6">

          <div
            class="flex flex-col gap-4 border-b border-gray-200 px-5 py-4 lg:flex-row lg:items-center lg:justify-between dark:border-gray-800"
        >
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    All Users
                </h3>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage all registered Users
                </p>
            </div>
             
        </div>

        </div>

        <div class="mt-10 bg-white rounded-lg shadow p-6">

            <div class="flex justify-end mb-6">

                <button
                    @click="openCreateModal"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg"
                >
                    Add User
                </button>

            </div>

            <div
                v-if="loading"
                class="text-center py-10"
            >
                Loading...
            </div>

            <UserTable
                v-else
                :users="users"
                :search-value="search"
                @search="searchUsers"
                @edit="editUser"
                @delete="askDelete"
                @page-change="getUsers"
            />

        </div>

        <Modal
            :show="showFormModal"
            @close="showFormModal=false"
        >

            <div class="p-6">

                <h2 class="text-xl font-bold mb-5">

                    {{
                        editing
                        ? "Update User"
                        : "Create User"
                    }}

                </h2>

                <UserForm
                    :form="form"
                    :errors="errors"
                    :editing="editing"
                    @clear-error="clearError"
                    @submit="saveUser"
                />

                <div class="flex justify-end mt-6">

                    <SuccessButton
                        @click="saveUser"
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

        <DeleteConfirmModal
            :show="showDeleteModal"
            :loading="deleting"
            @close="showDeleteModal=false"
            @confirm="deleteUser"
        />

    </Master>

</template>