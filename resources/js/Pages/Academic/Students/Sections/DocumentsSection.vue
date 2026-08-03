<script setup>
import { ref } from "vue";

import ImageCropper from "@/Components/ImageCropper.vue";

const props = defineProps({
    form: Object,

    errors: Object,

    student: Object,
});

const photoPreview = ref(props.student?.photo?.url ?? null);

const formBName = ref(props.student?.form_b?.file_original_name ?? null);

/*
|--------------------------------------------------------------------------
| Photo
|--------------------------------------------------------------------------
*/

function removePhoto() {
    form.photo = null;

    photoPreview.value = null;
}

/*
|--------------------------------------------------------------------------
| Form B Upload
|--------------------------------------------------------------------------
*/

function selectFormB(event) {
    const file = event.target.files[0];

    if (!file) return;

    form.form_b = file;

    formBName.value = file.name;
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
                    v-model="form.photo"
                    :preview-image="photoPreview"
                    @cropped="photoPreview = URL.createObjectURL($event)"
                    @remove="removePhoto"
                />

                <p v-if="errors.photo" class="mt-2 text-sm text-red-600">
                    {{ errors.photo[0] }}
                </p>
            </div>

            <!-- FORM B -->

            <div>
                <label
                    class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Form B
                </label>

                <div
                    class="rounded-lg border border-dashed p-5 dark:border-gray-700"
                >
                    <input
                        type="file"
                        accept="image/*,.pdf"
                        @change="selectFormB"
                    />

                    <div
                        v-if="formBName"
                        class="mt-3 rounded-lg bg-gray-100 p-3 text-sm dark:bg-gray-800"
                    >
                        {{ formBName }}
                    </div>
                </div>

                <p v-if="errors.form_b" class="mt-2 text-sm text-red-600">
                    {{ errors.form_b[0] }}
                </p>
            </div>
        </div>
    </div>
</template>
