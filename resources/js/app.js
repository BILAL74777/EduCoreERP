import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

import Toast from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import "@vuepic/vue-datepicker/dist/main.css";
import { formatDate } from "@/Helpers/date";
import { formatAmount } from "@/Helpers/amout";

import { useNavigation } from "@/composables/useNavigation";
const { goBack } = useNavigation();
const appName = import.meta.env.VITE_APP_NAME || "EduCoreERP";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });
        vueApp.use(plugin).use(ZiggyVue).use(Toast, {
            autoClose: 3000,
            position: "bottom-right",
            theme: "light",
        });
        // Global helper
        vueApp.config.globalProperties.$formatDate = formatDate;
        vueApp.config.globalProperties.$formatAmount = formatAmount;
        vueApp.config.globalProperties.$goBack = goBack;
        vueApp.mount(el);
        return vueApp;
    },
    progress: {
        color: "#4B5563",
    },
});
