<template>
  <div class="min-h-screen">
    <main>
      <slot></slot>
    </main>
    
    <VFlashGroup />
  </div>
</template>

<script setup lang="ts">

import VFlashGroup from '../flash-group/VFlashGroup.vue';

</script>
