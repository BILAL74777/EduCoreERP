<script setup>
import { ref } from "vue";

import ImageCropper from "@/Components/ImageCropper.vue";

const props = defineProps({
    form: Object,

    errors: Object,

    student: Object,
});

const photoPreview = ref(props.student?.photo?.url ?? null);

/*
|--------------------------------------------------------------------------
| Photo
|--------------------------------------------------------------------------
*/

function removePhoto() {
    props.form.photo = null;

    photoPreview.value = null;
}

</script>

<template>
    <div class="space-y-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Documents
            </h2>

            <p class="text-sm text-gray-500">
                Student photo and required documents
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- PHOTO -->

            <div>
                <label
                    class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Student Photo
                </label>
 
                <ImageCropper
    v-model="props.form.photo"
    :preview-image="photoPreview"
    title="Student Photo"
    :aspect-ratio="3 / 4"
    :canvas-width="1200"
    :canvas-height="1600"
    @cropped="photoPreview = URL.createObjectURL($event)"
    @remove="removePhoto"
/>

                <p v-if="errors.photo" class="mt-2 text-sm text-red-600">
                    {{ errors.photo[0] }}
                </p>
            </div>

        </div>
    </div>
</template>
