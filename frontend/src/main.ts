import './assets/main.css'

/**
 * Import Swiper styles
 */
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'
import 'jsvectormap/dist/jsvectormap.css'
import 'flatpickr/dist/flatpickr.css'

/**
 * Import box icon
 */
import 'boxicons'
import 'boxicons/css/boxicons.min.css'
import 'boxicons/dist/boxicons.js';

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'

import AdminLayout from '@/components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import Modal from '@/components/common/Modal.vue'
import VButton from '@/components/common/VButton.vue'
import VLabel from '@/components/common/VLabel.vue'
import VDatagrid from '@/components/common/VDatagrid.vue'
import VConfirm from '@/components/common/VConfirm.vue'

/**
 * We are defining all the global rules here and configuring
 * all the `vee-validate` settings.
 */
import { configure, defineRule, Field, Form, ErrorMessage } from "vee-validate";
import { localize } from "@vee-validate/i18n";
import en from "@vee-validate/i18n/dist/locale/en.json";
import * as AllRules from '@vee-validate/rules';

/**
 * Create a custom rule for phone number validation.
 */
defineRule("phone", (value: string) => {
    if (! value || ! value.length) {
        return true;
    }

    if (!/^\+?\d+$/.test(value)) {
        return false;
    }

    return true;
});

defineRule("", () => true);

configure({
    /**
     * Built-in error messages and custom error messages are available. Multiple
     * locales can be added in the same way.
     */
    generateMessage: localize({
        en: {
            ...en,
            messages: {
                ...en.messages,
                phone: "This {field} must be a valid phone number",
            },
        },
    }),

    validateOnBlur: true,
    validateOnInput: true,
    validateOnChange: true,
});

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
app.component('Modal', Modal)
app.component('VButton', VButton)
app.component('VLabel', VLabel)
app.component('VDatagrid', VDatagrid)
app.component('VConfirm', VConfirm)

window.addEventListener("load", function (event) {
    app.mount("#app");
});
