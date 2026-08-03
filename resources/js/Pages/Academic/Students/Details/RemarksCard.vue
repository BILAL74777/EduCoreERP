<script setup>
defineProps({
    student: Object,
});
</script>

<template>
    <div
         class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">
            Remarks
        </h3>

        <div
            class="rounded-lg   p-5 dark:border-gray-700 dark:bg-gray-900/30"
        >
            <p
                class="leading-7 text-gray-700 dark:text-gray-300"
            >
                {{ student.remarks || "No remarks available." }}
            </p>
        </div>
    </div>
</template>