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
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Dashboard
            </h1>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a
                            href="#"
                            class="text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
                            >Dashboard</a
                        >
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg
                                class="w-4 h-4 text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
                                >Dashboard</span
                            >
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
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

            <div class="col-span-12 xl:col-span-5">
                <CustomerDemographic />
            </div>

            <div class="col-span-12 xl:col-span-7">
                <RecentOrders />
            </div>
        </div>
    </Master>
</template>
