<script setup>
import { ref, computed } from "vue";

const props = defineProps({
    profile: {
        type: Object,
        required: true,
    },
});

const selectedStructure = ref(null);

const printMode = ref("all");

const student = computed(
    () => props.profile?.student || {}
);

const structures = computed(
    () => props.profile?.fee_structures || []
);

const payments = computed(
    () => props.profile?.payments || []
);

const totals = computed(
    () =>
        props.profile?.totals || {
            total_fee: 0,
            total_paid: 0,
            total_due: 0,
        }
);

/*
|--------------------------------------------------------------------------
| Classes
|--------------------------------------------------------------------------
*/

const selectedClass = computed(() => {

    if (!selectedStructure.value) {
        return null;
    }

    return structures.value.find(
        (structure) =>
            structure.id ===
            selectedStructure.value
    );
});

/*
|--------------------------------------------------------------------------
| Print
|--------------------------------------------------------------------------
*/

const printAll = () => {
    printMode.value = "all";

    setTimeout(() => {
        window.print();

        setTimeout(() => {
            printMode.value = "all";
        }, 500);
    }, 100);
};

const printSelected = () => {
    if (!selectedStructure.value) {
        alert("Please select a class first.");
        return;
    }

    printMode.value = "selected";

    setTimeout(() => {
        window.print();

        setTimeout(() => {
            printMode.value = "all";
        }, 500);
    }, 100);
};

/*
|--------------------------------------------------------------------------
| Money
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

/*
|--------------------------------------------------------------------------
| Date
|--------------------------------------------------------------------------
*/

const formatDate = (date) => {

    if (!date) {
        return "-";
    }

    return new Date(
        date
    ).toLocaleDateString(
        "en-GB"
    );
};
</script>

<template>
    <div
        id="fee-card-print-area"
        class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900"
    >

        <!-- Screen Header -->
        <div
            class="no-print flex flex-col gap-4 border-b border-gray-200 px-6 py-4 md:flex-row md:items-center md:justify-between dark:border-gray-800"
        >

            <div>
                <h2
                    class="text-lg font-semibold text-gray-900 dark:text-white"
                >
                    Student Fee Card
                </h2>

                <p
                    class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                >
                    Complete fee profile and payment history.
                </p>
            </div>

            <div
                class="flex flex-wrap gap-2"
            >

                <select
                    v-model="selectedStructure"
                    class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                >
                    <option :value="null">
                        Select Class
                    </option>

                    <option
                        v-for="structure in structures"
                        :key="structure.id"
                        :value="structure.id"
                    >
                        {{
                            structure.academic_class?.name ||
                            "Class"
                        }}
                    </option>
                </select>

                <button
                    type="button"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                    @click="printSelected"
                >
                    Print Selected
                </button>

                <button
                    type="button"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                    @click="printAll"
                >
                    Print Complete Card
                </button>

            </div>

        </div>

        <!-- Printable Card -->
        <div class="fee-card-print p-6">

            <!-- Institute Header -->
            <div
                class="border-b-2 border-gray-900 pb-4 text-center"
            >

                <img
                    :src="'/images/logo/edcore-erp-logo.PNG'"
                    alt="EduCore ERP"
                    class="mx-auto h-16 object-contain"
                />

                <h1
                    class="mt-2 text-xl font-bold text-gray-900"
                >
                    Modern Era Institute of Sciences
                </h1>

                <p
                    class="mt-1 text-sm font-semibold uppercase tracking-wider text-gray-600"
                >
                    Student Fee Card
                </p>

            </div>

            <!-- Student Information -->
            <div
                class="mt-5 flex flex-col gap-5 sm:flex-row"
            >

                <div class="shrink-0">

                    <img
                        v-if="student.photo?.url"
                        :src="student.photo.url"
                        alt="Student Photo"
                        class="h-28 w-24 rounded-lg border border-gray-300 object-cover"
                    />

                    <div
                        v-else
                        class="flex h-28 w-24 items-center justify-center rounded-lg border border-gray-300 bg-gray-100 text-xs text-gray-500"
                    >
                        No Photo
                    </div>

                </div>

                <div
                    class="grid flex-1 grid-cols-1 gap-x-8 gap-y-3 text-sm sm:grid-cols-2"
                >

                    <div>
                        <span class="font-semibold">
                            Student Name:
                        </span>

                        {{ student.name }}
                    </div>

                    <div>
                        <span class="font-semibold">
                            Admission No:
                        </span>

                        {{ student.admission_no || "-" }}
                    </div>

                    <div>
                        <span class="font-semibold">
                            Registration No:
                        </span>

                        {{ student.registration_no || "-" }}
                    </div>

                    <div>
                        <span class="font-semibold">
                            Father Name:
                        </span>

                        {{ student.father_name || "-" }}
                    </div>

                    <div>
                        <span class="font-semibold">
                            Faculty:
                        </span>

                        {{
        student.current_enrollment?.academic_class?.discipline?.faculty?.faculty ||
        "-"
    }}
                    </div>

                    <div>
                        <span class="font-semibold">
                            Discipline:
                        </span>

                        {{
        student.current_enrollment?.academic_class?.discipline?.discipline ||
        "-"
    }}
                    </div>

                    <div>
                        <span class="font-semibold">
                            Current Class:
                        </span>

                        {{
                            student.current_enrollment?.academic_class?.name ||
                            "-"
                        }}
                    </div>

                    <div>
                        <span class="font-semibold">
                            Section:
                        </span>

                        {{
                            student.current_enrollment?.section ||
                            student.section ||
                            "-"
                        }}
                    </div>

                </div>

            </div>

            <!-- Overall Summary -->
            <div
                class="mt-6 grid grid-cols-1 border border-gray-300 sm:grid-cols-3"
            >

                <div
                    class="border-b border-gray-300 p-4 text-center sm:border-b-0 sm:border-r"
                >
                    <p
                        class="text-xs font-medium uppercase text-gray-500"
                    >
                        Overall Fee
                    </p>

                    <p
                        class="mt-1 text-xl font-bold text-gray-900"
                    >
                        Rs.
                        {{ money(totals.total_fee) }}
                    </p>
                </div>

                <div
                    class="border-b border-gray-300 p-4 text-center sm:border-b-0 sm:border-r"
                >
                    <p
                        class="text-xs font-medium uppercase text-gray-500"
                    >
                        Overall Paid
                    </p>

                    <p
                        class="mt-1 text-xl font-bold text-gray-900"
                    >
                        Rs.
                        {{ money(totals.total_paid) }}
                    </p>
                </div>

                <div
                    class="p-4 text-center"
                >
                    <p
                        class="text-xs font-medium uppercase text-gray-500"
                    >
                        Overall Due
                    </p>

                    <p
                        class="mt-1 text-xl font-bold text-gray-900"
                    >
                        Rs.
                        {{ money(totals.total_due) }}
                    </p>
                </div>

            </div>

            <!-- Fee Structure -->
            <div
                class="mt-6"
            >

                <h2
                    class="mb-3 text-base font-bold uppercase text-gray-900"
                >
                    Fee Structure
                </h2>

                <div
                    class="overflow-hidden border border-gray-300"
                >

                    <table
                        class="w-full border-collapse text-sm"
                    >

                        <thead>
                            <tr
                                class="bg-gray-100"
                            >
                                <th
                                    class="border border-gray-300 px-3 py-2 text-left"
                                >
                                    #
                                </th>

                                <th
                                    class="border border-gray-300 px-3 py-2 text-left"
                                >
                                    Session
                                </th>

                                <th
                                    class="border border-gray-300 px-3 py-2 text-left"
                                >
                                    Class
                                </th>

                                <th
                                    class="border border-gray-300 px-3 py-2 text-right"
                                >
                                    Annual Fee
                                </th>

                                <th
                                    class="border border-gray-300 px-3 py-2 text-right"
                                >
                                    Paid
                                </th>

                                <th
                                    class="border border-gray-300 px-3 py-2 text-right"
                                >
                                    Remaining
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            <template
                                v-if="printMode === 'all'"
                            >
                                <tr
                                    v-for="(
                                        structure,
                                        index
                                    ) in structures"
                                    :key="structure.id"
                                >

                                    <td
    class="border border-gray-300 px-3 py-2"
>
    {{ index + 1 }}
</td>

<td
    class="border border-gray-300 px-3 py-2"
>
    {{ structure.session?.session_name || "-" }}
</td>

                                    <td
                                        class="border border-gray-300 px-3 py-2 font-medium"
                                    >
                                        {{
                                            structure.academic_class?.name ||
                                            "-"
                                        }}
                                    </td>

                                    <td
                                        class="border border-gray-300 px-3 py-2 text-right"
                                    >
                                        Rs.
                                        {{
                                            money(
                                                structure.annual_fee
                                            )
                                        }}
                                    </td>

                                    <td
                                        class="border border-gray-300 px-3 py-2 text-right"
                                    >
                                        Rs.
                                        {{
                                            money(
                                                structure.calculated_paid
                                            )
                                        }}
                                    </td>

                                    <td
                                        class="border border-gray-300 px-3 py-2 text-right font-semibold"
                                    >
                                        Rs.
                                        {{
                                            money(
                                                structure.calculated_due
                                            )
                                        }}
                                    </td>

                                </tr>
                            </template>

                            <tr
                                v-if="
                                    printMode === 'selected' &&
                                    selectedClass
                                "
                            >

                                <td
                                    class="border border-gray-300 px-3 py-2"
                                >
                                    1
                                </td>

                                <td
                                    class="border border-gray-300 px-3 py-2"
                                >
                                    {{
                                        selectedClass.session?.name ||
                                        "-"
                                    }}
                                </td>

                                <td
                                    class="border border-gray-300 px-3 py-2 font-medium"
                                >
                                    {{
                                        selectedClass.academic_class?.name ||
                                        "-"
                                    }}
                                </td>

                                <td
                                    class="border border-gray-300 px-3 py-2 text-right"
                                >
                                    Rs.
                                    {{
                                        money(
                                            selectedClass.annual_fee
                                        )
                                    }}
                                </td>

                                <td
                                    class="border border-gray-300 px-3 py-2 text-right"
                                >
                                    Rs.
                                    {{
                                        money(
                                            selectedClass.calculated_paid
                                        )
                                    }}
                                </td>

                                <td
                                    class="border border-gray-300 px-3 py-2 text-right font-semibold"
                                >
                                    Rs.
                                    {{
                                        money(
                                            selectedClass.calculated_due
                                        )
                                    }}
                                </td>

                            </tr>

                            <tr
                                v-if="
                                    printMode === 'all' &&
                                    !structures.length
                                "
                            >
                                <td
                                    colspan="6"
                                    class="border border-gray-300 px-3 py-5 text-center text-gray-500"
                                >
                                    No fee structures found.
                                </td>
                            </tr>

                        </tbody>

                        <tfoot
                            v-if="printMode === 'all'"
                        >

                            <tr
                                class="bg-gray-100 font-bold"
                            >

                                <td
                                    colspan="3"
                                    class="border border-gray-300 px-3 py-2 text-right"
                                >
                                    TOTAL
                                </td>

                                <td
                                    class="border border-gray-300 px-3 py-2 text-right"
                                >
                                    Rs.
                                    {{
                                        money(
                                            totals.total_fee
                                        )
                                    }}
                                </td>

                                <td
                                    class="border border-gray-300 px-3 py-2 text-right"
                                >
                                    Rs.
                                    {{
                                        money(
                                            totals.total_paid
                                        )
                                    }}
                                </td>

                                <td
                                    class="border border-gray-300 px-3 py-2 text-right"
                                >
                                    Rs.
                                    {{
                                        money(
                                            totals.total_due
                                        )
                                    }}
                                </td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

            <!-- Payment History -->
            <div
                v-if="printMode === 'all'"
                class="mt-6"
            >

                <h2
                    class="mb-3 text-base font-bold uppercase text-gray-900"
                >
                    Payment History
                </h2>

                <table
                    class="w-full border-collapse text-sm"
                >

                    <thead>
                        <tr
                            class="bg-gray-100"
                        >

                            <th
                                class="border border-gray-300 px-3 py-2 text-left"
                            >
                                #
                            </th>

                            <th
                                class="border border-gray-300 px-3 py-2 text-left"
                            >
                                Date
                            </th>

                            <th
                                class="border border-gray-300 px-3 py-2 text-left"
                            >
                                Class
                            </th>

                            <th
                                class="border border-gray-300 px-3 py-2 text-left"
                            >
                                Note
                            </th>

                            <th
                                class="border border-gray-300 px-3 py-2 text-right"
                            >
                                Amount
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        <tr
                            v-for="(
                                payment,
                                index
                            ) in payments"
                            :key="payment.id"
                        >

                            <td
                                class="border border-gray-300 px-3 py-2"
                            >
                                {{ index + 1 }}
                            </td>

                            <td
                                class="border border-gray-300 px-3 py-2"
                            >
                                {{
                                    formatDate(
                                        payment.payment_date
                                    )
                                }}
                            </td>

                            <td
                                class="border border-gray-300 px-3 py-2"
                            >
                                {{
                                    payment.fee_structure?.academic_class?.name ||
                                    "-"
                                }}
                            </td>

                            <td
                                class="border border-gray-300 px-3 py-2"
                            >
                                {{
                                    payment.note ||
                                    "-"
                                }}
                            </td>

                            <td
                                class="border border-gray-300 px-3 py-2 text-right font-semibold"
                            >
                                Rs.
                                {{
                                    money(
                                        payment.amount
                                    )
                                }}
                            </td>

                        </tr>

                        <tr
                            v-if="!payments.length"
                        >
                            <td
                                colspan="5"
                                class="border border-gray-300 px-3 py-5 text-center text-gray-500"
                            >
                                No payments recorded.
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

            <!-- Signature -->
            <div
                class="mt-10 grid grid-cols-2 gap-10 text-center text-sm"
            >

                <div>
                    <div
                        class="mx-auto mb-2 w-40 border-t border-gray-500"
                    ></div>

                    Student / Parent Signature
                </div>

                <div>
                    <div
                        class="mx-auto mb-2 w-40 border-t border-gray-500"
                    ></div>

                    Authorized Signature
                </div>

            </div>

        </div>
    </div>
</template>

<style>
@media print {
    body {
        background: white !important;
    }

    body * {
        visibility: hidden;
    }

    #fee-card-print-area,
    #fee-card-print-area * {
        visibility: visible;
    }

    #fee-card-print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none !important;
        box-shadow: none !important;
    }

    .no-print {
        display: none !important;
    }

    .fee-card-print {
        padding: 20px !important;
    }

    @page {
        size: A4;
        margin: 10mm;
    }
}
</style>