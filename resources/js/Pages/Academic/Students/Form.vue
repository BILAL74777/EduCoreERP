<script setup>
import AdmissionSection from "./Sections/AdmissionSection.vue";
import PersonalSection from "./Sections/PersonalSection.vue";
import EnrollmentSection from "./Sections/EnrollmentSection.vue";
import FeeSection from "./Sections/FeeSection.vue";
import DocumentsSection from "./Sections/DocumentsSection.vue";
import RemarksSection from "./Sections/RemarksSection.vue";

const props = defineProps({
    form: Object,
    errors: Object,

    faculties: Array,
    disciplines: Array,
    classes: Array,
    sessions: Array,

    loading: Boolean,

    student: Object,
});

const emit = defineEmits(["submit", "cancel"]);
</script>

<template>
    <div
        class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <form @submit.prevent="emit('submit')" class="space-y-8">
            <!-- Admission -->

            <AdmissionSection :form="form" :errors="errors" />
            <hr />
            <!-- Personal -->

            <PersonalSection :form="form" :errors="errors" />
            <hr />
            <!-- Enrollment -->

            <EnrollmentSection
                :form="form"
                :errors="errors"
                :faculties="faculties"
                :disciplines="disciplines"
                :classes="classes"
                :sessions="sessions"
            />

            <!-- Fee -->
            <hr />
            <FeeSection :form="form" :errors="errors" />
            <hr />
            <!-- Documents -->

            <DocumentsSection
                :form="form"
                :errors="errors"
                :student="student"
            />
            <hr />
            <!-- Remarks -->

            <RemarksSection :form="form" :errors="errors" />

            <!-- Footer -->

            <div
                class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-800"
            >
                <button
                    type="button"
                    @click="emit('cancel')"
                    class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    :disabled="loading"
                    class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <span v-if="loading"> Saving... </span>

                    <span v-else> Save Student </span>
                </button>
            </div>
        </form>
    </div>
</template>
