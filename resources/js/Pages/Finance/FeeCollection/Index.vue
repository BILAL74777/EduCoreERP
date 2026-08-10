<script setup>
import Master from "@/Layouts/Master.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";

import FeeCollectionForm from "./FeeCollectionForm.vue";
import FeeCollectionTable from "./FeeCollectionTable.vue";
import FeeCard from "./FeeCard.vue";
import PaymentVoucher from "./PaymentVoucher.vue";

const selectedStudent = ref(null);
const feeProfile = ref(null);

const studentFee = ref([]);
const paymentHistory = ref([]);

const payments = ref({
    data: [],
    current_page: 1,
    last_page: 1,
    total: 0,
    per_page: 10,
});

const searchValue = ref("");
const loading = ref(false);
const profileLoading = ref(false);

const showVoucher = ref(false);
const lastPayment = ref(null);

/*
|--------------------------------------------------------------------------
| Load payment table
|--------------------------------------------------------------------------
*/

const loadPayments = async (page = 1) => {
    loading.value = true;

    try {
        const response = await axios.get(
            "/api/finance/fee-collection/payments",
            {
                params: {
                    search: searchValue.value,
                    page,
                    per_page: 10,
                },
            }
        );

        if (response.data.success) {
            payments.value = response.data.data;
        }
    } catch (error) {
        console.error(error);

        toast.error(
            "Unable to load payment history."
        );
    } finally {
        loading.value = false;
    }
};

/*
|--------------------------------------------------------------------------
| Search payment table
|--------------------------------------------------------------------------
*/

const searchPayments = (value) => {
    searchValue.value = value;

    loadPayments(1);
};

/*
|--------------------------------------------------------------------------
| Payment table pagination
|--------------------------------------------------------------------------
*/

const pageChange = (page) => {
    loadPayments(page);
};

/*
|--------------------------------------------------------------------------
| Load complete student fee profile
|--------------------------------------------------------------------------
*/

const loadStudentFee = async (studentId) => {
    profileLoading.value = true;

    try {
        const response = await axios.get(
            `/api/finance/fee-collection/students/${studentId}/fee`
        );

        if (response.data.success) {

            feeProfile.value = response.data.data;

            selectedStudent.value =
                response.data.data.student;

            studentFee.value =
                response.data.data.fee_structures;

            paymentHistory.value =
                response.data.data.payments;
        }
    } catch (error) {
        console.error(error);

        toast.error(
            error.response?.data?.message ||
            "Unable to load student fee information."
        );
    } finally {
        profileLoading.value = false;
    }
};

/*
|--------------------------------------------------------------------------
| Student selected
|--------------------------------------------------------------------------
*/

const selectStudent = async (student) => {
    selectedStudent.value = student;

    await loadStudentFee(student.id);
};

/*
|--------------------------------------------------------------------------
| Clear selected student
|--------------------------------------------------------------------------
*/

const clearStudent = () => {
    selectedStudent.value = null;
    feeProfile.value = null;
    studentFee.value = [];
    paymentHistory.value = [];
};

/*
|--------------------------------------------------------------------------
| Payment collected
|--------------------------------------------------------------------------
*/

const paymentCollected = async (payment) => {
    lastPayment.value = payment;

    showVoucher.value = true;

    if (selectedStudent.value) {
        await loadStudentFee(
            selectedStudent.value.id
        );
    }

    await loadPayments(
        payments.value.current_page || 1
    );
};

/*
|--------------------------------------------------------------------------
| Close voucher
|--------------------------------------------------------------------------
*/

const closeVoucher = () => {
    showVoucher.value = false;
    lastPayment.value = null;
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

            <!-- {{  studentFee }} -->
            <!-- Collection Form -->
            <FeeCollectionForm
                :selected-student="selectedStudent"
                :fee-structure="studentFee"
                :payment-history="paymentHistory"
                @student-selected="selectStudent"
                @clear-student="clearStudent"
                @payment-collected="paymentCollected"
            />

            <!-- Loading Profile -->
            <div
                v-if="profileLoading"
                class="rounded-xl border border-gray-200 bg-white p-8 text-center shadow-sm dark:border-gray-800 dark:bg-gray-900"
            >
                <div
                    class="mx-auto h-8 w-8 animate-spin rounded-full border-4 border-gray-200 border-t-blue-600"
                ></div>

                <p
                    class="mt-3 text-sm text-gray-500 dark:text-gray-400"
                >
                    Loading student fee profile...
                </p>
            </div>

            <!-- Fee Card -->
            <FeeCard
                v-if="feeProfile && !profileLoading"
                :profile="feeProfile"
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

        <!-- Payment Voucher -->
        <PaymentVoucher
            v-if="showVoucher && lastPayment"
            :payment="lastPayment"
            @close="closeVoucher"
        />
    </Master>
</template>