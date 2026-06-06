<script setup>
defineProps({
    type: {
        type: String,
        default: "button",
    },
});
</script>

<template>
    <button
        :type="type"
        class="
            inline-flex items-center justify-center
            rounded-lg
            border border-gray-300 dark:border-gray-700
            bg-white dark:bg-gray-800
            px-4 py-2
            text-sm font-medium
            text-gray-700 dark:text-gray-200
            shadow-sm
            transition-all duration-200
            hover:bg-gray-50 dark:hover:bg-gray-700
            focus:outline-none
            focus:ring-2 focus:ring-green-500
            focus:ring-offset-2 dark:focus:ring-offset-gray-900
            disabled:cursor-not-allowed
            disabled:opacity-50
        "
    >
        <slot />
    </button>
</template>