<template>
  <ThemeProvider>
    <div class="min-h-screen xl:flex">
      <app-sidebar />
      <Backdrop />
      <div
        class="flex-1 transition-all duration-300 ease-in-out"
        :class="[isExpanded || isHovered ? 'lg:ml-[290px]' : 'lg:ml-[90px]']"
      >
      <app-header />
      <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Page Heading -->
            <header
                class="bg-white shadow"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
      </div>
    </div>
  </div>
  </ThemeProvider>
</template>

<script setup>
import AppSidebar from '@/Components/layout/AppSidebar.vue'
import AppHeader from '@/Components/layout/AppHeader.vue'
import Backdrop from '@/Components/layout/Backdrop.vue'
import ThemeProvider from '@/Components/layout/ThemeProvider.vue'
import { useSidebarProvider } from '@/composables/useSidebar'

const { isExpanded, isHovered } = useSidebarProvider()
</script>
