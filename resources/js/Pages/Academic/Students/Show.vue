<script setup>
import { Head, router, Link } from "@inertiajs/vue3";

import Master from "@/Layouts/Master.vue";

import ProfileHeader from "./Details/ProfileHeader.vue";
import AdmissionInformationCard from "./Details/AdmissionInformationCard.vue";
import PersonalInformationCard from "./Details/PersonalInformationCard.vue";
import EnrollmentCard from "./Details/EnrollmentCard.vue";
import FeeCard from "./Details/FeeCard.vue";
import DocumentsCard from "./Details/DocumentsCard.vue";
import RemarksCard from "./Details/RemarksCard.vue";
import { ArrowLeftIcon, PencilIcon } from "@heroicons/vue/24/outline";
const props = defineProps({
    student: {
        type: Object,
        required: true,
    },
});

function goBack() {
    router.visit("/academic/students");
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

                <div class="flex items-center gap-3">
                    <!-- Back -->
                    <button
                        type="button"
                        @click="goBack"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                    >
                        <ArrowLeftIcon class="h-5 w-5" />
                    </button>

                    <!-- Edit -->
                    <Link
                        :href="route('academic.student.edit', student.id)"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        <PencilIcon class="h-5 w-5" />
                    </Link>
                </div>
            </div>

            <!-- Profile Header -->

            <ProfileHeader :student="student" />

            <!-- Grid -->
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <AdmissionInformationCard :student="student" />

                <EnrollmentCard :student="student" />

                <PersonalInformationCard :student="student" />

                <FeeCard :student="student" />

                <!-- <DocumentsCard
                    :student="student"
                /> -->
            </div>
            <RemarksCard :student="student" />
        </div>
    </Master>
</template>
