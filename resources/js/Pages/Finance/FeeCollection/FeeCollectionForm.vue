<script setup>
import { computed, ref, watch } from "vue";
import axios from "axios";
import { toast } from "vue3-toastify";

const props = defineProps({
    selectedStudent: {
        type: Object,
        default: null,
    },

    feeStructure: {
        type: Object,
        default: null,
    },

    paymentHistory: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits([
    "student-selected",
    "clear-student",
    "payment-collected",
]);

const students = ref([]);
const studentSearch = ref("");
const searchingStudents = ref(false);
const submitting = ref(false);

const form = ref({
    amount: "",
    payment_date: new Date().toISOString().split("T")[0],
    note: "",
});

const errors = ref({});

let searchTimeout = null;

const searchStudents = () => {
    clearTimeout(searchTimeout);

    if (!studentSearch.value.trim()) {
        students.value = [];
        return;
    }

    searchTimeout = setTimeout(async () => {
        searchingStudents.value = true;

        try {
            const response = await axios.get(
                "/api/finance/fee-collection/students",
                {
                    params: {
                        search: studentSearch.value,
                    },
                }
            );

            if (response.data.success) {
                students.value = response.data.data;
            }
        } catch (error) {
            toast.error(
                error.response?.data?.message ||
                    "Failed to search students."
            );
        } finally {
            searchingStudents.value = false;
        }
    }, 300);
};

const selectStudent = (student) => {
    emit("student-selected", student);

    students.value = [];
    studentSearch.value = "";
};

const clearStudent = () => {
    form.value.amount = "";
    form.value.note = "";
    errors.value = {};

    emit("clear-student");
};

const annualFee = computed(() => {
    return Number(props.feeStructure?.annual_fee || 0);
});

const paidFee = computed(() => {
    return Number(props.feeStructure?.paid_fee || 0);
});

const outstanding = computed(() => {
    return Math.max(0, annualFee.value - paidFee.value);
});

const isPaid = computed(() => {
    return outstanding.value <= 0;
});

const submit = async () => {
    errors.value = {};

    if (!props.selectedStudent) {
        toast.error("Please select a student.");
        return;
    }

    if (!props.feeStructure) {
        toast.error("No fee structure found for this student.");
        return;
    }

    if (Number(form.value.amount) <= 0) {
        errors.value.amount = "Payment amount must be greater than zero.";
        return;
    }

    if (Number(form.value.amount) > outstanding.value) {
        errors.value.amount =
            "Payment amount cannot exceed outstanding fee.";
        return;
    }

    submitting.value = true;

    try {
        const response = await axios.post(
            "/api/finance/fee-collection",
            {
                student_id: props.selectedStudent.id,
                enrollment_id: props.feeStructure.enrollment_id,
                fee_structure_id: props.feeStructure.id,

                amount: Number(form.value.amount),
                payment_date: form.value.payment_date,
                note: form.value.note || null,
            }
        );

        if (response.data.success) {
            toast.success(response.data.message);

            form.value.amount = "";
            form.value.note = "";
            errors.value = {};

            emit("payment-collected");
        }
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value =
                error.response.data.errors || {};
        } else {
            toast.error(
                error.response?.data?.message ||
                    "Failed to collect fee."
            );
        }
    } finally {
        submitting.value = false;
    }
};

watch(
    () => props.selectedStudent,
    () => {
        form.value.amount = "";
        form.value.note = "";
        errors.value = {};
    }
);
</script>

<template>
    <div
        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <h2
            class="mb-6 text-lg font-semibold text-gray-900 dark:text-white"
        >
            Collect Student Fee
        </h2>

        <!-- Student Search -->
        <div v-if="!selectedStudent" class="relative">
            <label
                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
            >
                Search Student
            </label>

            <input
                v-model="studentSearch"
                @input="searchStudents"
                type="text"
                placeholder="Search by name, admission no..."
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            />

            <!-- Search Results -->
            <div
                v-if="students.length"
                class="absolute z-20 mt-1 max-h-64 w-full overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900"
            >
                <button
                    v-for="student in students"
                    :key="student.id"
                    type="button"
                    @click="selectStudent(student)"
                    class="block w-full border-b border-gray-100 px-4 py-3 text-left hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800"
                >
                   <div
    class="font-medium text-gray-900 dark:text-white"
>
    {{ student.name }}
    {{ student.gender === "Male" ? "S/o" : "D/o" }}
    {{ student.father_name }}
</div>
<p
    v-if="student.current_enrollment?.academic_class"
    class="text-sm text-gray-500 dark:text-gray-400"
>
    Class:
    {{ student.current_enrollment.academic_class.name }}
</p>

<p
    v-if="
        student.current_enrollment?.academic_class?.discipline
    "
    class="text-sm text-gray-500 dark:text-gray-400"
>
    Discipline:
    {{
        student.current_enrollment.academic_class.discipline.discipline
    }}
</p>

                    <div
                        class="text-xs text-gray-500 dark:text-gray-400"
                    >
                        Admission:
                        {{ student.admission_no }}
                    </div>
                </button>
            </div>
        </div>

        <!-- Selected Student -->
        <div v-else>
            <div
                class="mb-6 flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900"
            >
                <div>
                    <h3
                        class="font-semibold text-gray-900 dark:text-white"
                    >
                         
                        <div
    class="font-medium text-gray-900 dark:text-white"
>
    {{ selectedStudent.name }}
    {{ selectedStudent.gender === "Male" ? "S/o" : "D/o" }}
    {{ selectedStudent.father_name }}
</div>
                     
<p
    v-if="selectedStudent.current_enrollment?.academic_class"
    class="text-sm text-gray-500 dark:text-gray-400"
>
    Class:
    {{ selectedStudent.current_enrollment.academic_class.name }}
</p>

<p
    v-if="
        selectedStudent.current_enrollment?.academic_class?.discipline
    "
    class="text-sm text-gray-500 dark:text-gray-400"
>
    Discipline:
    {{
        selectedStudent.current_enrollment.academic_class.discipline.discipline
    }}
</p>
                      
                    </h3>

                    <p
                        class="text-sm text-gray-500 dark:text-gray-400"
                    >
                        Admission No:
                        {{ selectedStudent.admission_no }}
                    </p>

                    <p
                        v-if="
                            selectedStudent.current_enrollment
                                ?.academic_class
                        "
                        class="text-sm text-gray-500 dark:text-gray-400"
                    >
                        Class:
                        {{
                            selectedStudent.current_enrollment
                                .academic_class.name
                        }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="clearStudent"
                    class="rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-950"
                >
                    Change Student
                </button>
            </div>

            <!-- Fee Summary -->
            <div
                v-if="feeStructure"
                class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3"
            >
                <div
                    class="rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                >
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Annual Fee
                    </p>

                    <p
                        class="mt-1 text-xl font-semibold text-gray-900 dark:text-white"
                    >
                        {{ annualFee.toLocaleString() }}
                    </p>
                </div>

                <div
                    class="rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                >
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Paid Fee
                    </p>

                    <p
                        class="mt-1 text-xl font-semibold text-green-600"
                    >
                        {{ paidFee.toLocaleString() }}
                    </p>
                </div>

                <div
                    class="rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                >
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Outstanding
                    </p>

                    <p
                        class="mt-1 text-xl font-semibold"
                        :class="
                            outstanding > 0
                                ? 'text-red-600'
                                : 'text-green-600'
                        "
                    >
                        {{ outstanding.toLocaleString() }}
                    </p>
                </div>
            </div>

            <!-- Payment Form -->
            <div
                v-if="feeStructure && !isPaid"
                class="grid grid-cols-1 gap-4 md:grid-cols-2"
            >
                <!-- Amount -->
                <div>
                    <label
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Payment Amount
                    </label>

                    <input
                        v-model="form.amount"
                        type="number"
                        min="0"
                        :max="outstanding"
                        step="0.01"
                        placeholder="Enter amount"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                        :class="{
                            'border-red-500':
                                errors.amount,
                        }"
                    />

                    <p
                        v-if="errors.amount"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.amount }}
                    </p>
                </div>

                <!-- Payment Date -->
                <div>
                    <label
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Payment Date
                    </label>

                    <input
                        v-model="form.payment_date"
                        type="date"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>

                <!-- Note -->
                <div class="md:col-span-2">
                    <label
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Note
                    </label>

                    <textarea
                        v-model="form.note"
                        rows="2"
                        placeholder="Optional note"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    ></textarea>
                </div>

                <!-- Submit -->
                <div class="md:col-span-2 flex justify-end">
                    <button
                        type="button"
                        @click="submit"
                        :disabled="submitting"
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        {{
                            submitting
                                ? "Collecting..."
                                : "Collect Fee"
                        }}
                    </button>
                </div>
            </div>

            <!-- Fully Paid -->
            <div
                v-else-if="feeStructure && isPaid"
                class="rounded-lg border border-green-200 bg-green-50 p-4 text-center dark:border-green-900 dark:bg-green-950"
            >
                <p
                    class="font-medium text-green-700 dark:text-green-400"
                >
                    This student's fee has been fully paid.
                </p>
            </div>
        </div>
    </div>
</template>