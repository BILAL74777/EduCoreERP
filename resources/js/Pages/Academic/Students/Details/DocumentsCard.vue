<script setup>
import { ref } from "vue";

const props = defineProps({
    student: Object,
});

const selectedImage = ref(null);

function openImage(url) {
    selectedImage.value = url;
}

function closeImage() {
    selectedImage.value = null;
}
</script>

<template>
    <div
        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">
            Documents
        </h3>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="mb-2 text-sm text-gray-500">
                    Student Photo
                </p>

                <img
                    v-if="student.photo"
                    :src="student.photo.url"
                    class="h-40 w-32 cursor-pointer rounded-lg border object-cover transition hover:scale-105"
                    @click="openImage(student.photo.url)"
                />

                <div
                    v-else
                    class="flex h-40 w-32 items-center justify-center rounded-lg border bg-gray-100 text-gray-500"
                >
                    No Image
                </div>
            </div>

           
        </div>

        <div
            v-if="selectedImage"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-6"
            @click="closeImage"
        >
            <img
                :src="selectedImage"
                class="max-h-full max-w-full rounded-lg shadow-2xl"
            />
        </div>
    </div>
</template>