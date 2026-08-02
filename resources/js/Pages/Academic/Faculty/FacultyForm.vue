<script setup>
import Modal from "@/Components/Modal/Modal.vue";

const props = defineProps({
    show: Boolean,

    editMode: Boolean,

    loading: {
        type: Boolean,
        default: false,
    },

    form: {
        type: Object,
        required: true,
    },

    errors: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["close", "save"]);
</script>

<template>
    <Modal :show="show" maxWidth="2xl" @close="emit('close')">
        <div class="p-6">
            <!-- Header -->

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ editMode ? "Edit Faculty" : "Add Faculty" }}
                </h2>

                <p class="mt-1 text-gray-500 dark:text-gray-400">
                    Fill all required information.
                </p>
            </div>

            <!-- Form -->

            <div class="space-y-5">
                <!-- Code -->

                <div>
                    <label
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Code
                    </label>

                    <input
                        v-model="form.code"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring focus:ring-brand-400"
                        placeholder="SCI"
                    />

                    <p v-if="errors.code" class="mt-1 text-sm text-red-500">
                        {{ errors.code[0] }}
                    </p>
                </div>

                <!-- Name -->

                <div>
                    <label
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Faculty Name
                    </label>

                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring focus:ring-brand-400"
                        placeholder="Faculty of Science"
                    />

                    <p v-if="errors.name" class="mt-1 text-sm text-red-500">
                        {{ errors.name[0] }}
                    </p>
                </div>

                <!-- Description -->

                <div>
                    <label
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Description
                    </label>

                    <textarea
                        rows="4"
                        v-model="form.description"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring focus:ring-brand-400"
                        placeholder="Write description..."
                    ></textarea>

                    <p
                        v-if="errors.description"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ errors.description[0] }}
                    </p>
                </div>

                <!-- Status -->

                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            v-model="form.is_active"
                            class="rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                        />

                        <span
                            class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Active
                        </span>
                    </label>
                </div>
            </div>

            <!-- Footer -->

            <div class="mt-8 flex justify-end gap-3">
                <button
                    type="button"
                    @click="emit('close')"
                    :disabled="loading"
                    class="rounded-lg bg-gray-500 px-5 py-2.5 text-white hover:bg-gray-600 disabled:opacity-50"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    @click="emit('save')"
                    :disabled="loading"
                    class="rounded-lg bg-brand-500 px-5 py-2.5 text-white hover:bg-brand-600 disabled:opacity-50 flex items-center gap-2"
                >
                    <svg
                        v-if="loading"
                        class="h-4 w-4 animate-spin"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        />

                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.372 0 0 5.373 0 12h4z"
                        />
                    </svg>

                    {{
                        loading
                            ? editMode
                                ? "Updating..."
                                : "Saving..."
                            : editMode
                            ? "Update Faculty"
                            : "Save Faculty"
                    }}
                </button>
            </div>
        </div>
    </Modal>
</template>
