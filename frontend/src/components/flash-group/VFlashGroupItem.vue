<script setup>
import { defineProps, defineEmits, onMounted } from 'vue';
import { SuccessIcon, ErrorIcon, WarningIcon, InfoCircleIcon } from '@/icons'

// Define props
const props = defineProps({
  flash: {
    type: Object,
    required: true,
    default: 'success',
  }
});

const emit = defineEmits(['remove']);

const remove = () => {
  emit('remove', props.flash);
};

const variants = {
  success: {
    class: 'border-success-500 bg-success-50 dark:border-success-500/30 dark:bg-success-500/15',
    icon: 'text-success-500',
  },
  error: {
    class: 'border-error-500 bg-error-50 dark:border-error-500/30 dark:bg-error-500/15',
    icon: 'text-error-500',
  },
  warning: {
    class: 'border-warning-500 bg-warning-50 dark:border-warning-500/30 dark:bg-warning-500/15',
    icon: 'text-warning-500',
  },
  info: {
    class:
      'border-blue-light-500 bg-blue-light-50 dark:border-blue-light-500/30 dark:bg-blue-light-500/15',
    icon: 'text-blue-light-500',
  },
}

const icons = {
  success: SuccessIcon,
  error: ErrorIcon,
  warning: WarningIcon,
  info: InfoCircleIcon,
}

onMounted(() => {
  setTimeout(() => {
    remove();
  }, 5000);
});
</script>

<template>
  <div :class="['rounded-xl border p-4', variants[flash.type].class]">
    <div class="flex items-start gap-3">
      <div :class="['-mt-0.5',  variants[flash.type].icon]">
        <component :is="icons[flash.type]" />
      </div>

      <div>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ flash.message }}</p>
      </div>
    </div>
  </div>
</template>
