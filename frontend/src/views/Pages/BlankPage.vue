<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />
    <div
      class="min-h-screen rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03]  xl:px-10 xl:py-12"
    >
      <div class="mx-auto w-full max-w-[630px] text-center">
        <h3
          class="mb-4 font-semibold text-gray-800 text-theme-xl dark:text-white/90 sm:text-2xl"
        >
          Empty
        </h3>

        <p class="text-sm text-gray-500 dark:text-gray-400 sm:text-base">
          Welcome to your dashboard! Here you can manage your projects, view analytics, and customize your settings. Start by selecting an option from the menu.
        </p>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import AdminLayout from "@/components/layout/AdminLayout.vue";
import PageBreadcrumb from "@/components/common/PageBreadcrumb.vue";

const currentPageTitle = ref("Dashboard");
</script>

<style></style>
