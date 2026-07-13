<script setup>
import Master from "@/Layouts/Master.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";
import Modal from "@/Components/Modal.vue";
import DeleteConfirmModal from "@/Components/DeleteConfirmModal.vue";
import SupplierTable from "./SupplierTable.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
const showFormModal = ref(false);
const showDeleteModal = ref(false);
const deleting = ref(false);
const selectedSupplier = ref(null);

const editing = ref(false);
const suppliers = ref({});
const loading = ref(false);
const saving = ref(false);
const errors = ref({});
const search = ref("");

const form = ref({
    id: null,
    name: "",
    phone: "",
    cnic: "",
    address: "",
    opening_balance: 0,
    credit_limit: 0,
    status: "Active",
});

async function getSuppliers(page = 1) {
    loading.value = true;

    try {
        const response = await axios.get("/api/suppliers", {
            params: {
                page,
                search: search.value,
            },
        });

        suppliers.value = response.data.suppliers;
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
}
function searchSuppliers(value) {
    search.value = value;

    getSuppliers();
}

function openCreateModal() {
    clearErrors();
    editing.value = false;

    form.value = {
        id: null,
        name: "",
        phone: "",
        cnic: "",
        address: "",
        opening_balance: 0,
        credit_limit: 0,
        status: "Active",
    };

    showFormModal.value = true;
}

function editSupplier(supplier) {
    clearErrors();
    editing.value = true;

    form.value = { ...supplier };

    showFormModal.value = true;
}

async function saveSupplier() {
    saving.value = true;
    try {
        errors.value = {};

        if (editing.value) {
            await axios.post(`/api/suppliers/${form.value.id}`, form.value);
        } else {
            await axios.post("/api/suppliers", form.value);
            // toast.success("Supplier created successfully");
        }

        showFormModal.value = false;
        toast.success(
            `Supplier ${editing.value ? "updated" : "created"} successfully`
        );
        await getSuppliers();
    } catch (error) {
        if (error.response?.status === 422) {
            toast.error("Please fix the errors below.");
            errors.value = error.response.data.errors;
        } else {
            toast.error("An error occurred while saving the supplier.");
        }

        console.error(error);
    } finally {
        saving.value = false;
    }
}
// This function is used to clear error for a specific field when user starts typing
function clearError(field) {
    delete errors.value[field];
}
// This function is used to clear all errors when opening the form modal
function clearErrors() {
    errors.value = {};
}
const askDelete = (supplier_id) => {
    selectedSupplier.value = supplier_id;
    showDeleteModal.value = true;
};

const deleteSupplier = async () => {
    try {
        deleting.value = true;

        await axios.delete(`/api/suppliers/${selectedSupplier.value}`);

        showDeleteModal.value = false;

        await getSuppliers();
    } catch (error) {
        console.error(error);
        toast.error("Failed to delete supplier");
    } finally {
        toast.success("Supplier deleted successfully");
        deleting.value = false;
    }
};

onMounted(() => {
    getSuppliers();
});
</script>

<template>
    <Head title="Suppliers" />

    <Master>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Suppliers Management
            </h1>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a
                            href="#"
                            class="text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
                            >Home</a
                        >
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg
                                class="w-4 h-4 text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
                                >Suppliers</span
                            >
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- add margin top to each alert -->

        <div class="mt-10 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex justify-end items-center mb-6">
                <!-- <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    All Suppliers
                </h2> -->

                <button
                    @click="openCreateModal()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                    Add New
                </button>
            </div>

            <div v-if="loading" class="flex justify-center items-center py-10">
                <svg
                    class="animate-spin h-10 w-10 text-blue-600"
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
                    ></circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                    ></path>
                </svg>
            </div>
            <div
                v-else-if="suppliers.length === 0"
                class="text-center py-10 text-gray-500"
            >
                No suppliers found.
            </div>

            <SupplierTable
                v-else
                :suppliers="suppliers"
                :search-value="search"
                :show-delete-modal="showDeleteModal"
                :deleting="deleting"
                @edit="editSupplier"
                @delete="askDelete"
                @confirm-delete="deleteSupplier"
                @close-delete="showDeleteModal = false"
            />
        </div>

        <!-- Create / Update Modal -->
        <Modal :show="showFormModal" @close="showFormModal = false">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-5">
                    {{ editing ? "Update Supplier" : "Add Supplier" }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2">Supplier Name</label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full border rounded-lg px-3 py-2"
                            :class="{
                                'border-red-500 bg-red-50 dark:bg-red-900/20':
                                    errors.name,
                            }"
                            @input="clearError('name')"
                        />
                        <p v-if="errors.name" class="mt-1 text-sm text-red-600">
                            {{ errors.name[0] }}
                        </p>
                    </div>

                    <div>
                        <label class="block mb-2">Phone</label>
                        <input
                            v-model="form.phone"
                            type="text"
                            class="w-full border rounded-lg px-3 py-2"
                            :class="{
                                'border-red-500 bg-red-50 dark:bg-red-900/20':
                                    errors.phone,
                            }"
                            @input="clearError('phone')"
                        />
                        <p
                            v-if="errors.phone"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ errors.phone[0] }}
                        </p>
                    </div>

                    <div>
                        <label class="block mb-2">CNIC</label>
                        <input
                            v-model="form.cnic"
                            type="text"
                            class="w-full border rounded-lg px-3 py-2"
                            :class="{
                                'border-red-500 bg-red-50 dark:bg-red-900/20':
                                    errors.cnic,
                            }"
                            @input="clearError('cnic')"
                        />
                        <p v-if="errors.cnic" class="mt-1 text-sm text-red-600">
                            {{ errors.cnic[0] }}
                        </p>
                    </div>

                    <div>
                        <label class="block mb-2">Opening Balance</label>
                        <input
                            v-model="form.opening_balance"
                            type="number"
                            class="w-full border rounded-lg px-3 py-2"
                            :class="{
                                'border-red-500 bg-red-50 dark:bg-red-900/20':
                                    errors.opening_balance,
                            }"
                            @input="clearError('opening_balance')"
                        />
                        <p
                            v-if="errors.opening_balance"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ errors.opening_balance[0] }}
                        </p>
                    </div>

                    <div>
                        <label class="block mb-2">Status</label>
                        <select
                            v-model="form.status"
                            class="w-full border rounded-lg px-3 py-2"
                        >
                            <option :value="1">Active</option>
                            <option :value="0">Inactive</option>
                        </select>
                        <p
                            v-if="errors.status"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ errors.status[0] }}
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-2">Address</label>
                        <textarea
                            v-model="form.address"
                            rows="3"
                            class="w-full border rounded-lg px-3 py-2"
                            :class="{
                                'border-red-500 bg-red-50 dark:bg-red-900/20':
                                    errors.address,
                            }"
                            @input="clearError('address')"
                        ></textarea>
                        <p
                            v-if="errors.address"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ errors.address[0] }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <SuccessButton @click="saveSupplier" :loading="saving">
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
            @confirm="deleteSupplier"
        />
    </Master>
</template>
