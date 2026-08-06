<script setup>
import { ref, onMounted, watch } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import axios from "axios";
import { toast } from "vue3-toastify";

import Master from "@/Layouts/Master.vue";
import Form from "./Form.vue";
import { ArrowLeftIcon } from "@heroicons/vue/24/outline";

const sessions = ref([]);
const disciplines = ref([]);
const classes = ref([]);
const faculties = ref([]);

const loading = ref(false);

const form = useForm({
    // Admission
    admission_no: "",
    registration_no: "",
    section: "",
    date_of_admission: "",

    // Personal
    name: "",
    father_name: "",
    father_cnic: "",
    father_occupation: "",

    gender: "Male",

    date_of_birth: "",

    religion: "",
    tribe_cast: "",

    // Contact
    contact_number: "",
    guardian_contact_number: "",
    address: "",

    // Enrollment
    faculty_id: "",

    discipline_id: "",

    class_id: "",

    session_id: "",

    roll_no: "",

    // Fee
    annual_fee: 0,
    paid_fee: 0,
    payment_date: "",

    // Documents
    photo: null,
    form_b: null,

    // Flags
    is_orphan: 0,
    is_father_shaheed: 0,

    // Remarks
    remarks: "",
});

async function loadDropdowns() {
    try {
        const [facultyResponse, sessionResponse] = await Promise.all([
            axios.get("/api/academic/faculties/dropdown"),
            axios.get("/api/academic/sessions/dropdown"),
        ]);

        faculties.value = facultyResponse.data.faculties;

        sessions.value = sessionResponse.data;
    } catch (error) {
        console.error(error);

        toast.error("Failed to load dropdowns 123.");
    }
}

async function getDisciplines(facultyId) {
    if (!facultyId) {
        disciplines.value = [];
        classes.value = [];
        return;
    }

    try {
        const response = await axios.get(
            `/api/academic/students/disciplines/${facultyId}`
        );

        console.log("Facult Disciplines" + response.data);
        disciplines.value = response.data.disciplines;
    } catch (error) {
        console.error(error);

        toast.error("Failed to load disciplines.");
    }
}

async function getClasses(disciplineId) {
    if (!disciplineId) {
        classes.value = [];
        return;
    }

    try {
        const response = await axios.get(
            `/api/academic/students/classes/${disciplineId}`
        );

        classes.value = response.data.classes;
    } catch (error) {
        console.error(error);

        toast.error("Failed to load classes.");
    }
}

watch(
    () => form.faculty_id,
    async (value) => {
        form.discipline_id = "";
        form.class_id = "";

        disciplines.value = [];
        classes.value = [];

        if (value) {
            await getDisciplines(value);
        }
    }
);

watch(
    () => form.discipline_id,
    async (value) => {
        form.class_id = "";

        classes.value = [];

        if (value) {
            await getClasses(value);
        }
    }
);

const submit = async () => {
    loading.value = true;

    try {
        const data = new FormData();

        const payload = form.data ? form.data() : form;

        Object.keys(payload).forEach((key) => {
            if (payload[key] !== null && payload[key] !== undefined) {
                data.append(key, payload[key]);
            }
        });

        await axios.post("/api/academic/students", data, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        toast.success("Student created successfully.");

        // Wait 2 seconds before redirecting
        setTimeout(() => {
            router.visit("/academic/students");
        }, 1000);
    } catch (error) {
        if (error.response?.status === 422) {
            form.errors = error.response.data.errors || {};
        }

        toast.error("Please check the form.");
        console.error(error);
    } finally {
        loading.value = false;
    }
};

function cancel() {
    router.visit("/academic/students");
}
function goBack() {
    router.visit("/academic/students");
}

onMounted(() => {
    loadDropdowns();
});
</script>

<template>
    <Master>
        <div class="space-y-6">
            <div
                class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
            >
                <div>
                    <h1
                        class="text-xl font-semibold text-gray-800 dark:text-white"
                    >
                        Add Student
                    </h1>

                    <p class="text-sm text-gray-500">
                        Register new student admission
                    </p>
                </div>
                <div class="flex gap-3">
                    <button
                        type="button"
                        @click="goBack"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                    >
                        <ArrowLeftIcon class="h-5 w-5" />
                    </button>
                </div>
            </div>

            <Form
                :form="form"
                :errors="form.errors"
                :sessions="sessions"
                :faculties="faculties"
                :disciplines="disciplines"
                :classes="classes"
                :loading="loading"
                @submit="submit"
                @cancel="cancel"
            />
        </div>
    </Master>
</template>
