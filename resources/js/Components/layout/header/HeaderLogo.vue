<template>
  <Link href="/" class="lg:hidden">
    <img class="dark:hidden" :src="'/images/logo/asb-business-logo.PNG'" alt="Logo" />
    <img class="hidden dark:block" :src="'/images/logo/asb-business-logo-dark.png'" alt="Logo" />
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
