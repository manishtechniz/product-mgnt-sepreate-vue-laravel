<template>
    <label
        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
    >
        <slot>
            {{ label }}
            
            <span 
            v-if="required"
            class="text-error-500">*</span>
        </slot>
    </label>
</template>

<script setup>
/**
 * Define component props.
 */
const props = defineProps({
    label: String,
    required: {
        type: Boolean,
        default: false,
    },
});
</script>