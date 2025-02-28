import './assets/main.css'

// Import Swiper styles
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'
import 'jsvectormap/dist/jsvectormap.css'
import 'flatpickr/dist/flatpickr.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'

import AdminLayout from '@/components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'

/**
 * We are defining all the global rules here and configuring
 * all the `vee-validate` settings.
 */
import { configure, defineRule, Field, Form, ErrorMessage } from "vee-validate";
import * as AllRules from '@vee-validate/rules';

/**
 * Registration of all global validators.
 */
Object.entries(AllRules).forEach(([ruleName, ruleFunction]) => {
    if (typeof ruleFunction === 'function') {
        defineRule(ruleName, ruleFunction as (value: any) => boolean | string | Promise<boolean | string>);
    }
});

const app = createApp(App)

app.use(createPinia())
app.use(router)

/**
 * Global components registration;
 */
app.component("VForm", Form);
app.component("VField", Field);
app.component("VErrorMessage", ErrorMessage);
app.component('AdminLayout', AdminLayout)
app.component('PageBreadcrumb', PageBreadcrumb)

window.addEventListener("load", function (event) {
    app.mount("#app");
});
