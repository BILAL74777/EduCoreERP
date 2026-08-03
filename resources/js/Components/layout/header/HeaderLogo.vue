<template>
  <Link href="/" class="lg:hidden">
    <img class="dark:hidden" :src="'/images/logo/narrow-logo.jpg'" alt="Logo" />
    <img class="hidden dark:block" :src="'/images/logo/narrow-logo.jpg'" alt="Logo" />
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
