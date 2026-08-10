<script setup>
import { ref, computed, watch } from "vue";
import axios from "axios";
import debounce from "lodash/debounce";
import { toast } from "vue3-toastify";

const props = defineProps({
    selectedStudent: {
        type: Object,
        default: null,
    },

    feeStructure: {
        type: Array,
        default: () => [],
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

const search = ref("");
const students = ref([]);
const searching = ref(false);
const collecting = ref(false);

const amount = ref("");
const paymentDate = ref(
    new Date().toISOString().split("T")[0]
);
const note = ref("");

const errors = ref({});

/*
|--------------------------------------------------------------------------
| Student search
|--------------------------------------------------------------------------
*/

const searchStudents = debounce(
    async () => {

        if (!search.value.trim()) {
            students.value = [];
            return;
        }

        searching.value = true;

        try {

            const response = await axios.get(
                "/api/finance/fee-collection/students",
                {
                    params: {
                        search: search.value,
                    },
                }
            );

            if (response.data.success) {
                students.value =
                    response.data.data;
            }

        } catch (error) {

            console.error(error);

            toast.error(
                "Unable to search students."
            );

        } finally {
            searching.value = false;
        }
    },
    300
);

watch(search, () => {
    searchStudents();
});

/*
|--------------------------------------------------------------------------
| Select student
|--------------------------------------------------------------------------
*/

const selectStudent = (student) => {
    search.value = "";

    students.value = [];

    amount.value = "";
    note.value = "";

    errors.value = {};

    emit(
        "student-selected",
        student
    );
};

/*
|--------------------------------------------------------------------------
| Clear student
|--------------------------------------------------------------------------
*/

const clearStudent = () => {

    search.value = "";

    students.value = [];

    amount.value = "";

    note.value = "";

    errors.value = {};

    emit("clear-student");
};

/*
|--------------------------------------------------------------------------
| Current fee structures with balance
|--------------------------------------------------------------------------
*/

const availableStructures = computed(() => {
    return props.feeStructure.filter(
        (structure) =>
            Number(
                structure.calculated_due
            ) > 0
    );
});

/*
|--------------------------------------------------------------------------
| Selected fee structure
|--------------------------------------------------------------------------
*/

const selectedStructure = ref(null);

/*
|--------------------------------------------------------------------------
| Automatically select first outstanding fee
|--------------------------------------------------------------------------
*/

watch(
    () => props.feeStructure,
    (structures) => {

        if (
            structures?.length &&
            !selectedStructure.value
        ) {

            selectedStructure.value =
                structures.find(
                    (structure) =>
                        Number(
                            structure.calculated_due
                        ) > 0
                ) || structures[0];
        }
    },
    {
        immediate: true,
    }
);

/*
|--------------------------------------------------------------------------
| Remaining amount
|--------------------------------------------------------------------------
*/

const remainingAfterPayment = computed(() => {

    if (!selectedStructure.value) {
        return 0;
    }

    const due = Number(
        selectedStructure.value.calculated_due
    );

    const payment = Number(
        amount.value || 0
    );

    return Math.max(
        0,
        due - payment
    );
});

/*
|--------------------------------------------------------------------------
| Collect payment
|--------------------------------------------------------------------------
*/

const collectPayment = async () => {

    errors.value = {};

    if (!selectedStructure.value) {

        toast.error(
            "Please select a fee structure."
        );

        return;
    }

    collecting.value = true;

    try {

        const response = await axios.post(
            "/api/finance/fee-collection/payments",
            {
                student_id:
                    props.selectedStudent.id,

                enrollment_id:
                    selectedStructure.value.enrollment_id,

                fee_structure_id:
                    selectedStructure.value.id,

                amount: amount.value,

                payment_date:
                    paymentDate.value,

                note:
                    note.value || null,
            }
        );

        if (response.data.success) {

            toast.success(
                response.data.message
            );

            const payment =
                response.data.data;

            amount.value = "";
            note.value = "";

            emit(
                "payment-collected",
                payment
            );
        }

    } catch (error) {

        console.error(error);

        if (
            error.response?.status === 422
        ) {

            errors.value =
                error.response.data.errors ||
                {};

            toast.error(
                "Please correct the payment information."
            );

        } else {

            toast.error(
                error.response?.data?.message ||
                "Unable to collect payment."
            );
        }

    } finally {

        collecting.value = false;
    }
};

/*
|--------------------------------------------------------------------------
| Format money
|--------------------------------------------------------------------------
*/

const money = (value) => {

    return Number(
        value || 0
    ).toLocaleString(
        "en-PK",
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }
    );
};
</script>

<template>
    <div
        class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900"
    >

        <!-- Header -->
        <div
            class="border-b border-gray-200 px-6 py-4 dark:border-gray-800"
        >
            <h2
                class="text-lg font-semibold text-gray-900 dark:text-white"
            >
                Collect Fee
            </h2>

            <p
                class="mt-1 text-sm text-gray-500 dark:text-gray-400"
            >
                Search for a student and collect a payment.
            </p>
        </div>

        <div class="p-6">

            <!-- Student Search -->
            <div v-if="!selectedStudent">

                <label
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Search Student
                </label>

                <div class="relative">

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search by name, admission no or registration no..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    />

                    <div
                        v-if="searching"
                        class="absolute right-3 top-1/2 -translate-y-1/2"
                    >
                        <div
                            class="h-5 w-5 animate-spin rounded-full border-2 border-gray-300 border-t-blue-600"
                        ></div>
                    </div>

                    <!-- Search results -->
                    <div
                        v-if="students.length"
                        class="absolute z-50 mt-2 max-h-80 w-full overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800"
                    >

                        <button
                            v-for="student in students"
                            :key="student.id"
                            type="button"
                            class="flex w-full items-center gap-3 border-b border-gray-100 p-3 text-left transition last:border-b-0 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700"
                            @click="selectStudent(student)"
                        >

                            <img
                                v-if="student.photo?.url"
                                :src="student.photo.url"
                                class="h-10 w-10 rounded-full object-cover"
                                alt="Student"
                            />

                            <div
                                v-else
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300"
                            >
                                N/A
                            </div>

                            <div class="min-w-0 flex-1">

                                <p
                                    class="truncate text-sm font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ student.name }}
                                </p>

                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    Admission:
                                    {{ student.admission_no }}
                                </p>

                            </div>

                        </button>
                    </div>
                </div>
            </div>

            <!-- Selected Student -->
            <div v-else>

                <div
                    class="flex flex-col gap-5 rounded-xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-800/50 md:flex-row md:items-center"
                >

                    <img
                        v-if="selectedStudent.photo?.url"
                        :src="selectedStudent.photo.url"
                        class="h-20 w-20 rounded-xl object-cover"
                        alt="Student"
                    />

                    <div
                        v-else
                        class="flex h-20 w-20 shrink-0 items-center justify-center rounded-xl bg-gray-200 text-sm font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300"
                    >
                        N/A
                    </div>

                    <div class="flex-1">

                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            {{ selectedStudent.name }}
                        </h3>

                        <div
                            class="mt-2 grid grid-cols-1 gap-1 text-sm text-gray-600 dark:text-gray-400 md:grid-cols-3"
                        >

                            <div>
                                <span class="font-medium">
                                    Admission:
                                </span>

                                {{ selectedStudent.admission_no }}
                            </div>

                            <div>
                                <span class="font-medium">
                                    Registration:
                                </span>

                                {{ selectedStudent.registration_no || "-" }}
                            </div>

                            <div>
                                <span class="font-medium">
                                    Class:
                                </span>

                                {{
                                    selectedStudent.current_enrollment?.academic_class?.name ||
                                    "-"
                                }}
                            </div>

                        </div>

                    </div>

                    <button
                        type="button"
                        class="rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:border-red-900/50 dark:hover:bg-red-950/30"
                        @click="clearStudent"
                    >
                        Change Student
                    </button>

                </div>

                <!-- Payment Form -->
                <div
                    v-if="availableStructures.length"
                    class="mt-6"
                >

                    <div
                        class="grid grid-cols-1 gap-5 md:grid-cols-2"
                    >

                        <!-- Fee Structure -->
                        <div>

                            <label
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Fee Structure
                            </label>

                            <select
                                v-model="selectedStructure"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            >
                                <option
                                    v-for="structure in availableStructures"
                                    :key="structure.id"
                                    :value="structure"
                                >
                                    {{
                                        structure.academic_class?.name ||
                                        "Class"
                                    }}
                                    -
                                    Due Rs.
                                    {{
                                        money(
                                            structure.calculated_due
                                        )
                                    }}
                                </option>
                            </select>

                        </div>

                        <!-- Amount -->
                        <div>

                            <label
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Payment Amount
                            </label>

                            <input
                                v-model="amount"
                                type="number"
                                min="0"
                                step="0.01"
                                placeholder="Enter amount"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            />

                            <p
                                v-if="errors.amount"
                                class="mt-1 text-xs text-red-600"
                            >
                                {{ errors.amount[0] }}
                            </p>

                        </div>

                        <!-- Date -->
                        <div>

                            <label
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Payment Date
                            </label>

                            <input
                                v-model="paymentDate"
                                type="date"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            />

                        </div>

                        <!-- Note -->
                        <div>

                            <label
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Note
                            </label>

                            <input
                                v-model="note"
                                type="text"
                                placeholder="Optional note"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            />

                        </div>

                    </div>

                    <!-- Payment Summary -->
                    <div
                        v-if="selectedStructure"
                        class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-3"
                    >

                        <div
                            class="rounded-lg bg-blue-50 p-4 dark:bg-blue-950/30"
                        >
                            <p class="text-xs text-blue-600 dark:text-blue-400">
                                Annual Fee
                            </p>

                            <p
                                class="mt-1 text-lg font-semibold text-blue-700 dark:text-blue-300"
                            >
                                Rs.
                                {{
                                    money(
                                        selectedStructure.annual_fee
                                    )
                                }}
                            </p>
                        </div>

                        <div
                            class="rounded-lg bg-green-50 p-4 dark:bg-green-950/30"
                        >
                            <p class="text-xs text-green-600 dark:text-green-400">
                                Already Paid
                            </p>

                            <p
                                class="mt-1 text-lg font-semibold text-green-700 dark:text-green-300"
                            >
                                Rs.
                                {{
                                    money(
                                        selectedStructure.calculated_paid
                                    )
                                }}
                            </p>
                        </div>

                        <div
                            class="rounded-lg bg-red-50 p-4 dark:bg-red-950/30"
                        >
                            <p class="text-xs text-red-600 dark:text-red-400">
                                Remaining
                            </p>

                            <p
                                class="mt-1 text-lg font-semibold text-red-700 dark:text-red-300"
                            >
                                Rs.
                                {{
                                    money(
                                        selectedStructure.calculated_due
                                    )
                                }}
                            </p>
                        </div>

                    </div>

                    <!-- After Payment -->
                    <div
                        v-if="amount && selectedStructure"
                        class="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800"
                    >
                        <div
                            class="flex items-center justify-between"
                        >
                            <span
                                class="text-sm text-gray-500 dark:text-gray-400"
                            >
                                Remaining after payment
                            </span>

                            <span
                                class="text-lg font-bold text-gray-900 dark:text-white"
                            >
                                Rs.
                                {{
                                    money(
                                        remainingAfterPayment
                                    )
                                }}
                            </span>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-6 flex justify-end">

                        <button
                            type="button"
                            :disabled="collecting"
                            class="inline-flex items-center rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
                            @click="collectPayment"
                        >
                            <span
                                v-if="collecting"
                                class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"
                            ></span>

                            {{
                                collecting
                                    ? "Collecting..."
                                    : "Collect Payment"
                            }}
                        </button>

                    </div>

                </div>

                <!-- No fee -->
                <div
                    v-else
                    class="mt-6 rounded-lg border border-yellow-200 bg-yellow-50 p-5 text-sm text-yellow-800 dark:border-yellow-900/50 dark:bg-yellow-950/30 dark:text-yellow-300"
                >
                    No outstanding fee structure was found for this student.
                </div>

            </div>

        </div>
    </div>
</template>