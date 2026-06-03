<script setup>
import Modal from "@/Components/Modal.vue";

defineProps({
    show: Boolean,
});

const emit = defineEmits(["close", "confirm"]);
</script>

<template>
    <Modal :show="show" @close="emit('close')">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800">
                Delete Record
            </h2>

            <p class="mt-3 text-gray-600">
                Are you sure you want to delete this record?
            </p>

            <div class="flex justify-end gap-3 mt-6">
                <button
                    @click="emit('close')"
                    class="px-4 py-2 text-white bg-gray-500 rounded"
                >
                    Cancel
                </button>

                <button
                    @click="emit('confirm')"
                    class="px-4 py-2 text-white bg-red-600 rounded"
                >
                    Delete
                </button>
            </div>
        </div>
    </Modal>
</template>