<script setup>
import Modal from "@/Components/Modal/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";

defineProps({
    show: Boolean,
    loading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["close", "confirm"]);
</script>

<template>
    <Modal :show="show" @close="!loading && emit('close')">
        <div class="relative p-6 overflow-hidden">
            <!-- Background Warning Icon -->
            <div
                class="absolute inset-0 flex items-center justify-center pointer-events-none"
            >
                <svg
                    class="w-40 h-40 text-red-500 opacity-10 blur-[2px]"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M12 2L1 21h22L12 2zm0 4.8l7.53 12.2H4.47L12 6.8zM11 10h2v5h-2zm0 6h2v2h-2z"
                    />
                </svg>
            </div>

            <!-- Content -->
            <div class="relative z-10 text-center">
                <div
                    class="mx-auto flex items-center justify-center w-16 h-16 rounded-full bg-red-100"
                >
                    <svg
                        class="w-8 h-8 text-red-600"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M12 2L1 21h22L12 2zm0 4.8l7.53 12.2H4.47L12 6.8zM11 10h2v5h-2zm0 6h2v2h-2z"
                        />
                    </svg>
                </div>

                <h2
                    class="mt-4 text-xl font-bold text-gray-900 dark:text-white"
                >
                    Delete Record
                </h2>

                <p class="mt-3 text-gray-600 dark:text-gray-400">
                    Are you sure you want to delete this record? This action
                    cannot be undone.
                </p>

                <div class="flex justify-center gap-3 mt-8">
                    <button
                        @click="emit('close')"
                        :disabled="loading"
                        class="px-4 py-2 text-white bg-gray-500 rounded-lg disabled:opacity-50"
                    >
                        Cancel
                    </button>

                    <DangerButton
                        @click="emit('confirm')"
                        :disabled="loading"
                        class="px-4 py-2 flex items-center gap-2"
                    >
                        <svg
                            v-if="loading"
                            class="animate-spin h-4 w-4"
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
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                            />
                        </svg>

                        {{ loading ? "Deleting..." : "Delete" }}
                    </DangerButton>
                </div>
            </div>
        </div>
    </Modal>
</template>
