<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const updatePassword = () => {
    form.put(route("password.update"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset("password", "password_confirmation");
                passwordInput.value?.focus();
            }

            if (form.errors.current_password) {
                form.reset("current_password");
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Update Password
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Ensure your account is using a long, random password to stay
                secure.
            </p>
        </header>

        <form
            @submit.prevent="updatePassword"
            class="mt-6 space-y-6"
        >
            <!-- Current Password -->
            <div>
                <InputLabel
                    for="current_password"
                    value="Current Password"
                    class="mb-2 block font-medium text-gray-700 dark:text-gray-200"
                />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    autocomplete="current-password"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:border-green-500 focus:ring-green-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-green-400 dark:focus:ring-green-400"
                />

                <InputError
                    :message="form.errors.current_password"
                    class="mt-2"
                />
            </div>

            <!-- New Password -->
            <div>
                <InputLabel
                    for="password"
                    value="New Password"
                    class="mb-2 block font-medium text-gray-700 dark:text-gray-200"
                />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    autocomplete="new-password"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:border-green-500 focus:ring-green-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-green-400 dark:focus:ring-green-400"
                />

                <InputError
                    :message="form.errors.password"
                    class="mt-2"
                />
            </div>

            <!-- Confirm Password -->
            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                    class="mb-2 block font-medium text-gray-700 dark:text-gray-200"
                />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:border-green-500 focus:ring-green-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-green-400 dark:focus:ring-green-400"
                />

                <InputError
                    :message="form.errors.password_confirmation"
                    class="mt-2"
                />
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                <SuccessButton :disabled="form.processing">
                    Update Password
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
                        Password updated successfully.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>