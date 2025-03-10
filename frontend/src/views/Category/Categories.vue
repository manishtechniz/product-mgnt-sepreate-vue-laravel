<template>
    <AdminLayout>
      <PageBreadcrumb :pageTitle="currentPageTitle" />
      <div class="space-y-5 sm:space-y-6">
        <VButton 
            type="button"
            class="max-w-18"
            title="Create"
            @click="$refs.createAndUpdateModal.open();"
        />

        <!-- Create and Update Modal-->
        <v-form
          v-slot="{errors, isSubmitting, setValues, handleSubmit }"
          ref="modelForm"
        >
          <form
            @submit="handleSubmit($event, createAndUpdateHandle)"
          >
            <Modal
              header="Edit Profile"
              ref="createAndUpdateModal"
            >
              <template #body >
                <div>
                  <h5 class="mb-5 text-lg font-medium text-gray-800 dark:text-white/90 lg:mb-6">
                    Personal Information
                  </h5>

                  <div class="grid grid-cols-1 gap-x-6 gap-y-5 llg:grid-cols-2">
                    <!-- Id  -->
                    <v-field 
                      type="hidden"
                      name="id"
                    />

                    <!-- Name -->
                    <div class="col-span-2 lg:col-span-1">
                      <v-label
                        label="Name"
                        :required=true
                      />

                      <v-field
                        type="text"
                        name="name"
                        rules="required|min:3"
                        class="form-control"
                        :class="[errors.name ? 'border-error-300 focus' : '']"
                        label="Name"
                      />

                      <v-error-message name="name" class="mt-1.5 text-theme-xs text-error-500" />
                    </div>

                    <!-- Description -->
                    <div class="col-span-2 lg:col-span-1">
                      <v-label
                        label="Description"
                        :required=true
                      />

                      <v-field
                        name="description"
                        type="textarea"
                        v-slot="{ field }"
                        rules="required|min:5"
                        label="Description"
                      >
                        <textarea
                          name="description"
                          placeholder="Write description ..."
                          v-bind="field"
                          class="form-control h-20"
                          :class="[errors.description ? 'border-error-300 focus' : '']"
                        >
                        </textarea>
                      </v-field>

                      <v-error-message name="description" class="mt-1.5 text-theme-xs text-error-500" />
                    </div>

                    <!-- Status -->
                    <div class="col-span-2 lg:col-span-1">
                      <v-label
                        label="Status"
                        :required=true
                      />

                      <v-field
                        name="status"
                        type="select"
                        v-slot="{ field }"
                        rules="required"
                        label="Status"
                      >
                        <select
                          name="status"
                          v-bind="field"
                          class="form-control"
                          :class="[errors.status ? 'border-error-300 focus' : '']"
                        > 
                          <option value="1"> Active </option>
                          <option value="0"> InActive </option>
                        </select>
                      </v-field>

                      <v-error-message name="status" class="mt-1.5 text-theme-xs text-error-500" />
                    </div>

                    <!-- Image -->
                    <div class="col-span-2 lg:col-span-1">
                      <v-label
                        label="Image"
                      />

                      <v-field
                        name="image"
                        label="Image"
                      >
                        <input
                          type="file"
                          name="image"
                          class="form-control"
                          :class="[errors.image ? 'border-error-300 focus' : '']"
                          @change="handleFileChange"
                        />
                      </v-field>

                      <v-error-message name="image" class="mt-1.5 text-theme-xs text-error-500" />
                    </div>
                  </div>
                </div>
              </template>
              
              <template #footer-submit-btn>
                <VButton
                  type="submit"
                  :loading="isSubmitting"
                  :disabled="isSubmitting"
                  class="max-w-14"
                />
              </template>
            </Modal>
          </form>
        </v-form>

        <!-- Datagrid -->
        <VDatagrid
          :url="'/category'"
          :columns="header"
          :actions="actions"
          ref="dataGrid"
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
                @click="editModal(record)"
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
  import { ref, useTemplateRef, computed } from "vue";
  import axois from '@/plugins/axios'
  import emitter from '@/plugins/emitter'

  const currentPageTitle = ref("Categories");
  const createAndUpdateModal = useTemplateRef('createAndUpdateModal')
  const modelForm = useTemplateRef('modelForm')
  const dataGrid = useTemplateRef('dataGrid')

  const selectedFile = ref(null);

  const header = [
  {
      key: 'id',
      label: 'Id',
      filterable: true,
      sortable: true,
    },
    {
      key: 'name',
      label: 'Name',
      filterable: true,
      sortable: true,
    },
    {
      key: 'image_path',
      label: 'Image',
      filterable: false,
      sortable: false,
      closure: (record) => {
        return `
          <img src="${record.image_url}" onerror="this.src='./images/prev.png';" style="height:100px; width: 100px; border-radius:8px"/>
        `;
      }
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
      closure: (record) => {
        if (record.status) {
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
  
  /**
   * Create and Update handle operations
   */
  const createAndUpdateHandle = async (params, { resetForm, setErrors  }) => {
    let isCreateOperation = true;

    let formData = new FormData();

    formData.append("name", params.name);
    formData.append("description", params.description);
    formData.append("status", params.status);

    if (!! params?.id) {
      isCreateOperation = false;
    }

    /**
     * Check file selected or not.
     */
    if (!! selectedFile.value) {
      formData.append("image", selectedFile.value);
    }

    /**
     * Use post method for updating resources because files/images do'nt work with put method.
     */
    await axois({
      url: `/category/${isCreateOperation ? '' : params.id}`,
      method: 'post',
      data: formData,
    })
    .then(async (response) => {
      resetForm();

      emitter.emit('add-flash', { type: 'success', message: response.data.message });

      /**
       * Close modal
       */
       createAndUpdateModal.value.close();

      /**
       * Refresh Datagrid
       */
       dataGrid.value.refresh();
    })
    .catch(error => {
      console.log(error);
        if (error.status == 422) {
            setErrors(error.response.data.errors);

            return;
        }

        emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message ?? "Something went wrong!" });
    });
  }

  const editModal = (record) => {
    /**
     * Set vlaues to modal form
     */
    modelForm.value.setValues(record);

    createAndUpdateModal.value.open();
  }

  const handleFileChange = (event) => {
    selectedFile.value = event.target.files[0];
  }
</script>