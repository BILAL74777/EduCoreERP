<script setup>
import { ref, onMounted, watch } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import axios from "axios";
import { toast } from "vue3-toastify";

import Master from "@/Layouts/Master.vue";
import Form from "./Form.vue";

const props = defineProps({
    student: Object,
});

const loading = ref(false);

const faculties = ref([]);
const disciplines = ref([]);
const classes = ref([]);
const sessions = ref([]);

const form = useForm({
    /*
    |--------------------------------------------------------------------------
    | Admission
    |--------------------------------------------------------------------------
    */

    admission_no: "",
    registration_no: "",
    section: "",
    date_of_admission: "",

    /*
    |--------------------------------------------------------------------------
    | Personal
    |--------------------------------------------------------------------------
    */

    name: "",
    father_name: "",
    father_cnic: "",
    father_occupation: "",

    gender: "Male",

    date_of_birth: "",

    religion: "",
    tribe_cast: "",

    contact_number: "",
    guardian_contact_number: "",
    address: "",

    /*
    |--------------------------------------------------------------------------
    | Enrollment
    |--------------------------------------------------------------------------
    */

    faculty_id: "",
    discipline_id: "",

    class_id: "",
    session_id: "",
    roll_no: "",

    /*
    |--------------------------------------------------------------------------
    | Fee
    |--------------------------------------------------------------------------
    */

    annual_fee: 0,
    paid_fee: 0,
    payment_date: "",
 

    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    photo: null,
    form_b: null,

    /*
    |--------------------------------------------------------------------------
    | Flags
    |--------------------------------------------------------------------------
    */

    is_orphan: false,
    is_father_shaheed: false,

    /*
    |--------------------------------------------------------------------------
    | Remarks
    |--------------------------------------------------------------------------
    */

    remarks: "",
});

/*
|--------------------------------------------------------------------------
| Dropdowns
|--------------------------------------------------------------------------
*/

async function loadDropdowns() {
    try {
        const response = await axios.get("/api/academic/students/dropdowns");

        faculties.value = response.data.faculties;

        sessions.value = response.data.sessions;
    } catch (error) {
        toast.error("Failed to load dropdowns.");
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

        disciplines.value = response.data.disciplines;
    } catch (error) {
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
        toast.error("Failed to load classes.");
    }
}

/*
|--------------------------------------------------------------------------
| Watchers
|--------------------------------------------------------------------------
*/

watch(
    () => form.faculty_id,
    async (value, oldValue) => {
        if (value === oldValue) return;

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
    async (value, oldValue) => {
        if (value === oldValue) return;

        form.class_id = "";

        classes.value = [];

        if (value) {
            await getClasses(value);
        }
    }
);

/*
|--------------------------------------------------------------------------
| Existing Student
|--------------------------------------------------------------------------
*/

async function setStudent() {
    const student = props.student;

    form.admission_no = student.admission_no;
    form.registration_no = student.registration_no;
    form.section = student.section;
    form.date_of_admission = student.date_of_admission;

    form.name = student.name;
    form.father_name = student.father_name;
    form.father_cnic = student.father_cnic;
    form.father_occupation = student.father_occupation;
    form.form_b = student.form_b;

    form.gender = student.gender;
    form.date_of_birth = student.date_of_birth;

    form.religion = student.religion;
    form.tribe_cast = student.tribe_cast;

    form.contact_number = student.contact_number;
    form.guardian_contact_number = student.guardian_contact_number;
    form.address = student.address;

    form.is_orphan = student.is_orphan;
    form.is_father_shaheed = student.is_father_shaheed;

    form.remarks = student.remarks;

    /*
    |--------------------------------------------------------------------------
    | Fee
    |--------------------------------------------------------------------------
    */

    if (student.fee_structure) {
        form.annual_fee = student.fee_structure.annual_fee;
        form.paid_fee = student.fee_structure.paid_fee;
    }

    /*
    |--------------------------------------------------------------------------
    | Enrollment
    |--------------------------------------------------------------------------
    */

    if (student.current_enrollment) {
        form.faculty_id =
            student.current_enrollment.academic_class?.discipline?.faculty
                ?.id ?? "";

        await getDisciplines(form.faculty_id);

        form.discipline_id =
            student.current_enrollment.academic_class?.discipline?.id ?? "";

        await getClasses(form.discipline_id);

        form.class_id = student.current_enrollment.class_id;

        form.session_id = student.current_enrollment.session_id;

        form.roll_no = student.current_enrollment.roll_no;
    }
}

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

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

        data.append("_method", "PUT");

        await axios.post(
            `/api/academic/students/${props.student.id}`,
            data,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );

        toast.success("Student updated successfully.");

        setTimeout(() => {
            router.visit("/academic/students");
        }, 2000);
    } catch (error) {
        console.log(error.response);

        if (error.response?.status === 422) {
            form.errors = error.response.data.errors;
        }

        toast.error("Please check the form.");
    } finally {
        loading.value = false;
    }
};

function cancel() {
    router.visit("/academic/students");
}

onMounted(async () => {
    await loadDropdowns();

    await setStudent();
});
</script>

<template>
    <Master>
        <div class="space-y-6">
            <div>
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Edit Student
                </h1>

                <p class="text-sm text-gray-500">Update student information</p>
            </div>

            <Form
                :form="form"
                :errors="form.errors"
                :faculties="faculties"
                :disciplines="disciplines"
                :classes="classes"
                :sessions="sessions"
                :student="student"
                :loading="loading"
                @submit="submit"
                @cancel="cancel"
            />
        </div>
    </Master>
</template>
