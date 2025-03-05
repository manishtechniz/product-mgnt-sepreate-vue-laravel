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

        <!-- Create and Update -->
        <VDatagrid
          :url="'/category'"
          :columns="header"
          :actions="actions"
        >
          <template #actions ="{ record, deleteAction }">
            <!-- Delete Action -->
              <button
                @click="deleteAction(record, actions[0])"
                class="text-brand-500 hover:bg-brand-100"
              >
                  <span
                    class="cursor-pointer rounded-full p-2 text-xl transition-all dark:hover:bg-gray-800 max-sm:place-self-center bx bx-trash"
                  >
                </span>
              </button>

              <!-- Edit Action -->
              <button
                @click=""
                class="text-brand-500 hover:bg-brand-100"
              >
                  <span
                    class="cursor-pointer rounded-full p-2 text-xl transition-all dark:hover:bg-gray-800 max-sm:place-self-center bx bxs-edit-alt"
                  >
                </span>
              </button>
          </template>
        </VDatagrid>
      </div>
    </AdminLayout>
  </template>
  
  <script setup>  
  import { ref } from "vue";
  
  const currentPageTitle = ref("Basic Tables");

  const header = [
    {
      key: 'name',
      label: 'Name',
      filterable: true,
      sortable: true,
    },
    {
      key: 'id',
      label: 'Id',
      filterable: true,
      sortable: true,
    },
    {
      key: 'description',
      label: 'Description',
      filterable: false,
      sortable: false,
    },
    {
      key: 'status',
      label: 'Status',
      filterable: true,
      sortable: true,
      closure: (value) => {
        if (value) {
          return `<span class="text-success-500">Active</span>`;
        }

        return `<span class="text-error-500">InActive</span>`;
      }
    },
  ];

  /**
   * Declare actions
   */
  const actions = [
    {
      type: 'delete',
      label: 'Delete',
      url: '/category',
      method: 'delete',
      closure: () => {}
    },
    {
      type: 'edit',
      label:'Edit',
      url: '/category/edit',
      method: 'get',
      closure: () => {}
    }
  ]
</script>