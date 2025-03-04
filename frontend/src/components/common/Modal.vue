<template>
  <div 
    class="fixed inset-0 flex items-center justify-center overflow-y-auto modal z-99999" 
    v-if="isOpen"
  >
    <div
      class="fixed inset-0 w-full bg-gray-500/50 backdrop-blur-[5px]"
      aria-hidden="true"
    >
    </div>

    <div
      class="no-scrollbar relative w-full max-w-[700px] max-h-[98vh] m-1 md:m-0 overflow-y-auto rounded-3xl bg-white dark:bg-gray-900"
    >
      <!-- Header -->
      <div class="px-6 py-3 border-b-1 border-gray-300 ">
        <slot name="header">
            <h3 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
              {{ header }}
            </h3>
        </slot>
      </div>
      
      <!-- Body Content -->
      <div class="custom-scrollbar llg:max-h-[400px] mmd:max-h-[90vh] overflow-y-auto p-4 lg:p-6">
        <slot name="body"></slot>
      </div>

      <!-- Footer -->
      <div class="flex items-center gap-3 lg:justify-end justify-end px-6 py-3 border-t-1 border-gray-300">
        <slot name="footer">
          <button
            @click="close"
            type="button"
            class="flex flex-column w-16 justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto"
          >
            Close
          </button>

          <slot name="footer-submit-btn"></slot>
        </slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineExpose, defineEmits } from 'vue'

/**
 * Define Props
 */
const props = defineProps({
  header: {
    type: String,
    default: '',
  },

  isActive: {
    type: Boolean,
    default: false,
  }
});

/**
 * Define emits
 */
const emit = defineEmits(['open', 'close', 'toggle']);

const { header, isActive } = props;

const isOpen = ref(isActive);

const open = () => {
  isOpen.value = true;

  emit('open', { isActive: isOpen.value });
};

const close = () => {
  isOpen.value = false;

  emit('close', { isActive: isOpen.value });
};

const toggle = () => {
  isOpen.value = ! isOpen.value;

  emit('toggle', { isActive: isOpen.value });
};

/**
 * Expose functions so the parent can call them
 */
 defineExpose({ open, close, toggle });

</script>
