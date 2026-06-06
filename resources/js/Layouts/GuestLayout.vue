<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div
        class="relative flex min-h-screen items-center justify-center bg-cover bg-center bg-no-repeat"
        style="background-image: url('https://img.magnific.com/free-vector/abstract-geometric-wireframe-background_52683-59421.jpg?semt=ais_hybrid&w=740&q=80')"
    >
        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black/50"></div>

        <!-- Content -->
        <div class="relative z-10 w-full max-w-md px-6">
            <!-- Logo -->
            <div class="mb-6 flex justify-center">
                <Link href="/">
                    <ApplicationLogo class="h-auto max-w-xs" />
                </Link>
            </div>

            <!-- Login Card -->
            <div
                class="overflow-hidden rounded-2xl border border-white/20 bg-white/95 px-8 py-8 shadow-2xl backdrop-blur-md dark:bg-gray-900/95"
            >
                <slot />
            </div>
        </div>
    </div>
</template>