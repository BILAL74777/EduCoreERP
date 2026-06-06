<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="mt-6 space-y-6"
        >
            <!-- Name -->
            <div>
                <InputLabel
                    for="name"
                    value="Name"
                    class="mb-2 block font-medium text-gray-700 dark:text-gray-200"
                />

                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:border-green-500 focus:ring-green-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-green-400 dark:focus:ring-green-400"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.name"
                />
            </div>

            <!-- Email -->
            <div>
                <InputLabel
                    for="email"
                    value="Email Address"
                    class="mb-2 block font-medium text-gray-700 dark:text-gray-200"
                />

                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autocomplete="username"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:border-green-500 focus:ring-green-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-green-400 dark:focus:ring-green-400"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.email"
                />
            </div>

            <!-- Email Verification -->
            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Your email address is unverified.

                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="ml-1 text-indigo-600 underline hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                <SuccessButton :disabled="form.processing">
                    Save Changes
                </SuccessButton>

                <Transition
                    enter-active-class="transition ease-in-out duration-300"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out duration-300"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm font-medium text-green-600 dark:text-green-400"
                    >
                        Saved successfully.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>