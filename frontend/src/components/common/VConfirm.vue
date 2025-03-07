<template>
    <div>
        <!-- Modal Overlay -->
        <transition
            name="modal-overlay"
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                class="fixed inset-0 bg-gray-500/50 backdrop-blur-[5px]  transition-opacity z-[100022]"
                v-show="isOpen"
            ></div>
        </transition>

        <!-- Modal Content -->
        <transition
            name="modal-content"
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
            enter-to-class="opacity-100 translate-y-0 md:scale-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100 translate-y-0 md:scale-100"
            leave-to-class="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
        >
            <div
                class="fixed inset-0 z-[100022] transform transition overflow-y-auto"
                v-if="isOpen"
            >
                <div class="flex min-h-full items-end justify-center p-[20px] sm:items-center sm:p-0">
                    <div class="w-full max-w-[400px] z-[999] absolute left-[50%] top-[50%] rounded-lg bg-white dark:bg-gray-900 box-shadow max-md:w-[90%] -translate-x-[50%] -translate-y-[50%]">
                        <div class="flex justify-between items-center gap-[10px] px-[16px] py-[11px] border-b-[1px] dark:border-gray-800 text-[18px] text-gray-800 dark:text-white font-bold">
                            {{ title }}
                        </div>

                        <div class="px-[16px] py-[11px] text-gray-600 dark:text-gray-300 text-left">
                            {{ message }}
                        </div>
                        
                        <div class="flex gap-[10px] justify-end px-[16px] py-[10px]">
                            <!-- Disagree Button -->
                            <button
                                type="button" 
                                class="bg-transparent form-control w-auto" 
                                @click="disagree()"
                            >
                                {{ options.btnDisagree }}
                            </button>

                            <!-- Agree Button -->
                            <button 
                                type="button" 
                                class="bg-brand-500 form-control max-w-18 text-white" 
                                @click="agree"
                            >
                                {{ options.btnAgree }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import emitter from '@/plugins/emitter'

const isOpen = ref(false);
const title = ref('');
const message = ref('');

/**
 * Callback refference
 */
const agreeCallback = ref(null);
const disagreeCallback = ref(null);

/**
 * Confirm Modal Buttons
 */
const options = ref({
    btnDisagree: '',
    btnAgree: '',
});

const open = ({
    title: newTitle = "Are you sure",
    message: newMessage = "Are you sure you want to perform this action?",
    options: newOptions = {
        btnDisagree: "Disagree",
        btnAgree: "Agree",
    },
    agree = () => {},
    disagree = () => {},
}) => {
    isOpen.value = true;
    
    title.value = newTitle;

    message.value = newMessage;

    options.value = newOptions;

    agreeCallback.value = agree;

    disagreeCallback.value = disagree;
};

const disagree = () => {
    isOpen.value = false;

    if (disagreeCallback.value) {
        disagreeCallback.value();
    }
};

const agree = () => {
    isOpen.value = false;

    if (agreeCallback.value) {
        agreeCallback.value();
    }
};

onMounted(() => {
    /**
     * Register modal global event.
     */
    emitter.on('open-confirm-modal', open);
});
</script>
