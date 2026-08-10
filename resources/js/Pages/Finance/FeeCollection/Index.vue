<script setup>
import Master from "@/Layouts/Master.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";

import FeeCollectionTable from "./FeeCollectionTable.vue";
import FeeCollectionForm from "./FeeCollectionForm.vue";

const payments = ref({
    data: [],
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0,
});

const loading = ref(false);
const searchValue = ref("");

const selectedStudent = ref(null);
const studentFee = ref(null);
const paymentHistory = ref([]);

const loadPayments = async (page = 1) => {
    loading.value = true;

    try {
        const response = await axios.get(
            "/api/finance/fee-collection",
            {
                params: {
                    page,
                    search: searchValue.value,
                    per_page: 10,
                },
            }
        );

        if (response.data.success) {
            payments.value = response.data.data;
        }
    } catch (error) {
        toast.error(
            error.response?.data?.message ||
                "Failed to load fee payments."
        );
    } finally {
        loading.value = false;
    }
};

const searchPayments = (value) => {
    searchValue.value = value;
    loadPayments(1);
};

const selectStudent = async (student) => {
    selectedStudent.value = student;
    studentFee.value = null;
    paymentHistory.value = [];

    try {
        const response = await axios.get(
            `/api/finance/fee-collection/students/${student.id}/fee`
        );

        if (response.data.success) {
            studentFee.value = response.data.data.fee_structure;
            paymentHistory.value = response.data.data.payments;
        }
    } catch (error) {
        toast.error(
            error.response?.data?.message ||
                "Failed to load student fee."
        );
    }
};

const clearStudent = () => {
    selectedStudent.value = null;
    studentFee.value = null;
    paymentHistory.value = [];
};

const paymentCollected = async () => {
    if (!selectedStudent.value) {
        return;
    }

    await selectStudent(selectedStudent.value);

    loadPayments(payments.value.current_page);
};

const pageChange = (page) => {
    loadPayments(page);
};

onMounted(() => {
    loadPayments();
});
</script>

<template>
    <Head title="Fee Collection" />

    <Master>
        <div class="space-y-6">

            <!-- Header -->
            <div>
                <h1
                    class="text-2xl font-semibold text-gray-900 dark:text-white"
                >
                    Fee Collection
                </h1>

                <p
                    class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                >
                    Collect and manage student fee payments.
                </p>
            </div>

            <!-- Collection Form -->
            <FeeCollectionForm
                :selected-student="selectedStudent"
                :fee-structure="studentFee"
                :payment-history="paymentHistory"
                @student-selected="selectStudent"
                @clear-student="clearStudent"
                @payment-collected="paymentCollected"
            />

            <!-- Payment Table -->
            <FeeCollectionTable
                :payments="payments"
                :search-value="searchValue"
                :loading="loading"
                @search="searchPayments"
                @page-change="pageChange"
            />
        </div>
    </Master>
</template>