<script setup>
const props = defineProps({
    pagination: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["page-change"]);

function goToPage(url) {
    if (!url) return;

    const page = new URL(url).searchParams.get("page");

    emit("page-change", page);
}
</script>

<template>
    <div
        class="flex flex-col gap-4 border-t border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800"
    >
        <div>
            <span class="text-sm text-gray-500 dark:text-gray-400">
                Showing
                <strong>{{ pagination?.from ?? 0 }}</strong>
                to
                <strong>{{ pagination?.to ?? 0 }}</strong>
                of
                <strong>{{ pagination?.total ?? 0 }}</strong>
                records
            </span>
        </div>

        <div class="flex gap-2 flex-wrap">
            <!-- Previous -->

            <button
                @click="goToPage(pagination?.prev_page_url)"
                :disabled="!pagination?.prev_page_url"
                class="rounded-lg border px-3 py-2 disabled:opacity-50"
            >
                Previous
            </button>

            <!-- Page Numbers -->

            <button
                v-for="link in (pagination?.links || []).slice(1, -1)"
                :key="link.label"
                @click="goToPage(link.url)"
                :disabled="!link.url"
                :class="[
                    'rounded-lg px-4 py-2',
                    link.active
                        ? 'bg-brand-500 text-white'
                        : 'border dark:border-gray-700',
                    !link.url && 'opacity-50 cursor-not-allowed',
                ]"
                v-html="link.label"
            />

            <!-- Next -->

            <button
                @click="goToPage(pagination?.next_page_url)"
                :disabled="!pagination?.next_page_url"
                class="rounded-lg border px-3 py-2 disabled:opacity-50"
            >
                Next
            </button>
        </div>
    </div>
</template>
