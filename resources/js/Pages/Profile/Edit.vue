<script setup>
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";

import Master from "@/Layouts/Master.vue";

import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const activeTab = ref("profile");
</script>

<template>
    <Head title="Profile Settings" />

    <Master>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Profile Settings
            </h1>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a
                            href="#"
                            class="text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
                            >Home</a
                        >
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg
                                class="w-4 h-4 text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
                                >Profile</span
                            >
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="py-6">
            <div class="mx-auto">
                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800"
                >
                    <!-- Header -->
                    <div
                        class="border-b border-gray-200 px-6 py-5 dark:border-gray-700"
                    >
                        <h1
                            class="text-2xl font-semibold text-gray-900 dark:text-white"
                        >
                            Profile Settings
                        </h1>

                        <p
                            class="mt-1 text-sm text-gray-600 dark:text-gray-400"
                        >
                            Manage your profile, password and account settings.
                        </p>
                    </div>

                    <!-- Tabs -->
                    <div
                        class="flex flex-wrap border-b border-gray-200 dark:border-gray-700"
                    >
                        <button
                            @click="activeTab = 'profile'"
                            class="px-6 py-4 text-sm font-medium transition"
                            :class="
                                activeTab === 'profile'
                                    ? 'border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400'
                                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'
                            "
                        >
                            Profile Information
                        </button>

                        <button
                            @click="activeTab = 'password'"
                            class="px-6 py-4 text-sm font-medium transition"
                            :class="
                                activeTab === 'password'
                                    ? 'border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400'
                                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'
                            "
                        >
                            Change Password
                        </button>

                        <button
                            @click="activeTab = 'delete'"
                            class="px-6 py-4 text-sm font-medium transition"
                            :class="
                                activeTab === 'delete'
                                    ? 'border-b-2 border-red-600 text-red-600 dark:text-red-400'
                                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'
                            "
                        >
                            Delete Account
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Profile Information -->
                        <div v-show="activeTab === 'profile'">
                            <div
                                class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800"
                            >
                                <UpdateProfileInformationForm
                                    :must-verify-email="mustVerifyEmail"
                                    :status="status"
                                />
                            </div>
                        </div>

                        <!-- Change Password -->
                        <div v-show="activeTab === 'password'">
                            <div
                                class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800"
                            >
                                <UpdatePasswordForm />
                            </div>
                        </div>

                        <!-- Delete Account -->
                        <div v-show="activeTab === 'delete'">
                            <div
                                class="rounded-lg border border-red-200 bg-white p-6 dark:border-red-900/50 dark:bg-gray-800"
                            >
                                <DeleteUserForm />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Master>
</template>
