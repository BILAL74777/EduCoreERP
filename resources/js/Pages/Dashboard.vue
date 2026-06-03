<script setup>
import Master from "@/Layouts/Master.vue";
import { Head } from "@inertiajs/vue3";

import EcommerceMetrics from "@/Components/ecommerce/EcommerceMetrics.vue";
import MonthlyTarget from "@/Components/ecommerce/MonthlyTarget.vue";
import MonthlySale from "@/Components/ecommerce/MonthlySale.vue";
import CustomerDemographic from "@/Components/ecommerce/CustomerDemographic.vue";
import StatisticsChart from "@/Components/ecommerce/StatisticsChart.vue";
import RecentOrders from "@/Components/ecommerce/RecentOrders.vue";
</script>

<template>
    <Head title="Dashboard" />

    <Master>
        <div class="grid grid-cols-12 gap-4 md:gap-6">
            <div class="col-span-12 space-y-6 xl:col-span-7">
                <EcommerceMetrics />
                <MonthlySale />
            </div>
            
            <div class="col-span-12 xl:col-span-5">
                <MonthlyTarget />
            </div>
            
            <div class="col-span-12">
                <StatisticsChart />
            </div>

            <!-- <div class="col-span-12 xl:col-span-5">
                <CustomerDemographic />
            </div> -->

            <div class="col-span-12 xl:col-span-12">
                <RecentOrders />
            </div>
        </div>
    </Master>
</template>