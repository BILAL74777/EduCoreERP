<script setup>
import { ref } from "vue";

import Modal from "@/Components/Modal/Modal.vue";

import { Cropper } from "vue-advanced-cropper";

import "vue-advanced-cropper/dist/style.css";

const props = defineProps({
    modelValue: {
        type: File,
        default: null,
    },

    previewImage: {
        type: String,
        default: null,
    },

    title: {
        type: String,
        default: "Crop Image",
    },

    aspectRatio: {
        type: Number,
        default: 3 / 4,
    },

    canvasWidth: {
        type: Number,
        default: 120,
    },

    canvasHeight: {
        type: Number,
        default: 160,
    },
});

const emit = defineEmits(["update:modelValue", "cropped", "remove"]);

const showModal = ref(false);

const image = ref(null);

const cropper = ref(null);

const preview = ref(props.previewImage);

/*
|--------------------------------------------------------------------------
| Select Image
|--------------------------------------------------------------------------
*/

function chooseImage(event) {
    const file = event.target.files[0];

    if (!file) return;

    image.value = URL.createObjectURL(file);

    showModal.value = true;
}

/*
|--------------------------------------------------------------------------
| Crop Image
|--------------------------------------------------------------------------
*/

function applyCrop() {
    if (!cropper.value) return;

    const result = cropper.value.getResult();

    if (!result.canvas) return;

    result.canvas.toBlob(
        (blob) => {
            const file = new File([blob], `student-photo-${Date.now()}.jpg`, {
                type: "image/jpeg",
            });

            preview.value = URL.createObjectURL(file);

            emit("update:modelValue", file);

            emit("cropped", file);

            // close modal
            showModal.value = false;

            // clear crop state
            setTimeout(() => {
                image.value = null;
            }, 300);
        },
        "image/jpeg",
        0.9
    );
}

function closeModal() {
    showModal.value = false;

    setTimeout(() => {
        image.value = null;
    }, 300);
}

function removeImage() {
    preview.value = null;

    emit("update:modelValue", null);

    emit("remove");
}
</script>

<template>
    <div class="space-y-4">
        <!-- Current Preview -->

        <div>
            <img
                v-if="preview"
                :src="preview"
                class="h-40 w-30 rounded-lg object-cover border"
            />

            <div
                v-else
                class="flex h-32 w-32 items-center justify-center rounded-lg bg-gray-100 text-gray-500"
            >
                No Image
            </div>
        </div>

        <div class="flex gap-3">
            <label
                class="cursor-pointer rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
            >
                Choose Image

                <input
                    type="file"
                    accept="image/*"
                    class="hidden"
                    @change="chooseImage"
                />
            </label>

            <button
                v-if="preview"
                type="button"
                @click="removeImage"
                class="rounded-lg bg-red-100 px-4 py-2 text-red-600"
            >
                Remove
            </button>
        </div>

        <!-- Crop Modal -->

        <Modal :show="showModal" max-width="xl" @close="closeModal">
            <div class="p-6 space-y-5">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                   {{ title }}
                </h2>

                <div class="h-[400px]">
                    <Cropper
                        ref="cropper"
                        :src="image"
                        :stencil-props="{
                            aspectRatio: aspectRatio,
                        }"
                        :canvas="{
                            width: canvasWidth,
                            height: canvasHeight,
                        }"
                    />
                </div>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        @click="closeModal"
                        class="rounded-lg bg-gray-200 px-5 py-2 dark:bg-gray-700"
                    >
                        Cancel
                    </button>

                    <button
                        type="button"
                        @click="applyCrop"
                        class="rounded-lg bg-blue-600 px-5 py-2 text-white"
                    >
                        Crop & Apply
                    </button>
                </div>
            </div>
        </Modal>
    </div>
</template>
