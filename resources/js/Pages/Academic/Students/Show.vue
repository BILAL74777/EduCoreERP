<script setup>
import { Head, router } from "@inertiajs/vue3";

import Master from "@/Layouts/Master.vue";

import ProfileHeader from "./Details/ProfileHeader.vue";
import AdmissionInformationCard from "./Details/AdmissionInformationCard.vue";
import PersonalInformationCard from "./Details/PersonalInformationCard.vue";
import EnrollmentCard from "./Details/EnrollmentCard.vue";
import FeeCard from "./Details/FeeCard.vue";
import DocumentsCard from "./Details/DocumentsCard.vue";
import RemarksCard from "./Details/RemarksCard.vue";

const props = defineProps({
    student: {
        type: Object,
        required: true,
    },
});

function goBack() {
    router.visit("/academic/students");
}

function editStudent() {
    router.visit(`/academic/students/${props.student.id}/edit`);
}
</script>

<template>
    <Master>
        <Head :title="student.name" />

        <div class="space-y-6">
            <!-- Page Header -->

            <div
                class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl font-bold text-gray-900 dark:text-white"
                    >
                        Student Profile
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        View complete student information.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button
                        type="button"
                        @click="goBack"
                        class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                    >
                        Back
                    </button>

                    <button
                        type="button"
                        @click="editStudent"
                        class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700"
                    >
                        Edit Student
                    </button>
                </div>
            </div>

            <!-- Profile Header -->

            <ProfileHeader :student="student" />
            

            <!-- Grid --> 
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <AdmissionInformationCard
                    :student="student"
                />

                <EnrollmentCard
                    :student="student"
                />

                <PersonalInformationCard
                    :student="student"
                />

                <FeeCard
                    :student="student"
                />

                <!-- <DocumentsCard
                    :student="student"
                /> -->

            </div>
            <RemarksCard
                :student="student"
            />
        </div>
    </Master>
</template>