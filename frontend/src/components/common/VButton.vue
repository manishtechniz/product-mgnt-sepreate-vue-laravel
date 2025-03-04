<script setup>
/**
 * Define component props.
 */
const props = defineProps({
  loading: Boolean,

  type: {
    type: String,
    default: "button",
  },

  title: {
    type: String,
    default: "Save",
  },
});
</script>

<template>
  <button v-if="! loading"
    :type="type"
    class="flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 disabled:opacity-60"
    >
    <slot>{{ title }}</slot>
  </button>

  <button v-else
    class="flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 disabled:opacity-60 cursor-not-allowed"
  >
    <!-- Spinner -->
    <svg
      class="absolute h-5 w-5 animate-spin"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      aria-hidden="true"
      viewBox="0 0 24 24"
    >
      <circle
        class="opacity-25"
        cx="12"
        cy="12"
        r="10"
        stroke="currentColor"
        stroke-width="4"
      />
      <path
        class="opacity-75"
        fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
      />
    </svg>

    <span class="relative h-full opacity-0">
      <slot>{{ title }}</slot>
    </span>
  </button>
</template>