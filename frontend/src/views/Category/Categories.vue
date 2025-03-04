<template>
    <AdminLayout>
      <PageBreadcrumb :pageTitle="currentPageTitle" />
      <div class="space-y-5 sm:space-y-6">
        <VButton 
            type="button"
            class="max-w-36"
            title="Create Category"
            @click=""
        />
        <CategoryGrid />
      </div>
    </AdminLayout>
  </template>
  
  <script setup>
  import { ref } from "vue";

  import ComponentCard from "@/components/common/ComponentCard.vue";
  import CategoryGrid from "@/views/Category/CategoryGrid.vue";
  
  const currentPageTitle = ref("Basic Tables");
  </script>