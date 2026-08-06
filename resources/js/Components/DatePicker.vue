<script setup>
import { VueDatePicker } from "@vuepic/vue-datepicker";

const model = defineModel();
</script>

<template>
    <VueDatePicker
        v-model="model"
        model-type="yyyy-MM-dd"
        :enable-time-picker="false"
        :time-picker="false"
        :formats="{
            input: 'dd/MM/yyyy'
        }"
        auto-apply
    />
</template>