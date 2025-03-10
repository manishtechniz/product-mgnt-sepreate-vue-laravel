<template>
  <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] py-2 px-2">
    <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3 ">
      <div></div>
      <div class="ml-3">
        <div class="w-full max-w-sm min-w-[200px] relative dark:bg-gray-800">
          <div class="relative">
            <input
              @input="filterAction($event.target.value)"
              class="w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
              placeholder="Search..." 
            />

            <button
              class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center rounded"
              type="button"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="relative flex flex-col w-full h-full overflow-auto text-gray-700 dark:bg-white/[0.03] dark:text-white/70 bg-white shadow-md rounded-lg bg-clip-border">
      <table 
        class="w-full text-left table-auto min-w-max "
      >
        <slot name="header">
          <thead class="bg-gray-100 dark:bg-gray-900 dark:text-white/70 text-sm">
            <tr>
              <th
                v-for="(column, index) in columns" 
                :key="index"
                @click="sortAction(column)"
                class="capitalize p-4 border-b"
                :class="[column.sortable ? 'cursor-pointer' : 'cursor-default']"
              >
                {{ column.label }}
              </th>

              <th>Action</th>
            </tr>
          </thead>
        </slot>

        <slot name="body" 
          :records="records" 
          :columns="columns"
        >
        <!-- Table Body -->
          <tbody class="text-sm">
            <tr v-for="(record, index) in records" :key="index" class="hover:bg-slate-50 dark:hover:bg-gray-900 border-b dark:border-gray-900">
              <td v-for="(column, colIndex) in columns" :key="colIndex" class="p-4">
                <span v-if="column.closure" v-html="column.closure(record)"></span>
                <span v-else> {{ record[column.key] }}</span>
              </td>

              <!-- Action -->
              <td>
                <!-- Action Slots -->
                <slot
                  name="actions"
                  :actions="actions"
                  :record="record"
                  :deleteAction="deleteAction"
                >
                  <div>
                    <template
                      v-for="(action, index) in actions"
                      :key="index"
                    >
                      <button
                        @click="[action.type == 'delete' ? deleteAction(record, action) : editAction(record, action)]"
                        class="text-brand-500 hover:bg-brand-100"
                      >
                        <span
                          class="cursor-pointer rounded-full p-2 text-xl transition-all dark:hover:bg-gray-800 max-sm:place-self-center bx"
                          :class="[action.type == 'delete' ? 'bx-trash' : 'bxs-edit-alt']"
                          >
                        </span>
                      </button>
                    </template>
                  </div>
                </slot>
              </td>
            </tr>
          </tbody>
        </slot>
      </table>

      <div 
        class="flex justify-between items-center px-4 py-3 flex-col-reverse gap-2 md:flex-row md:gap-0"
        v-if="paginationDetails?.total"
      >
        <div class="text-sm text-slate-500">
          Showing {{ paginationDetails?.from ?? 0 }}-{{ paginationDetails?.to ?? 0 }} of {{ paginationDetails?.total }}
        </div>

        <div class="flex space-x-1">
          <template 
            v-for="(page, index) in paginationDetails?.links"
            :key="index"
          >
            <button
              v-if="index == 0 || paginationDetails?.links.length == (index + 1)"
              @click="paginateLink(page.url)"
              :class="{'bg-gray-800 text-white': page.active}"
              :disabled="!!! page.url ? true : false"
              class="px-3 py-1 border rounded disabled:opacity-70 disabled:cursor-not-allowed"
            >
              <span v-if="index == 0"> Prev </span>
              <span v-else-if="paginationDetails?.links.length == (index + 1)"> Next </span>
              <span v-else="index == 0"> {{ page.label}} </span>
            </button>
          </template>
        </div>
      </div>
    </div>
</div>
</template>

<script setup>
import { ref, computed, onMounted, watch, reactive } from 'vue';
import emitter from '@/plugins/emitter'
import axois from '@/plugins/axios'
import { useRouter } from 'vue-router'

import { defineProps } from 'vue';

const props = defineProps({
  url: {
    type: String,
    required: true
  },

  columns: {
    type: Array,
    required: true
  },

  actions: {
    type: Array,
    required: true
  }
});

const router = useRouter();

const records = ref([]);
const paginationDetails = ref([]);
const loading = ref(false);
const currentUrl = ref(props.url);

const query = reactive({ 
    itemsPerPage: 10,
    sort: 'desc',
    columnSort: '',
    filter: '',
    status: null,
    columnFilters: "[]",
});

/**
 * Find sortable columns
 */
const columnFilters = computed(() => {
  return props.columns.filter(column => column.filterable).map(column => column.key);
});

/**
 * Fetch Records
 */
const fetchRecords = async (query) => {
  loading.value = true;
  
  await axois.get(`${currentUrl.value}`, {
    params: query
  })
  .then(response => {
    console.log(response);
    paginationDetails.value = response.data.data;

    records.value = paginationDetails.value.data;
  })
  .catch(error => {
    console.log(error);
      if (error.status == 422) {
          setErrors(error.response.data.errors);

          return;
      }

      emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message ?? "Something went wrong!" });
  })
  .then(() => {
    loading.value = false;
  })
};

/**
 * Create query watcher
 */
watch(query, async (newQuery) => {
  await fetchRecords(newQuery);
});

/**
 * Create url watcher
 */
watch(currentUrl, async (newUrl) => {
  await fetchRecords(query);
});

/**
 * Update url.
 */
const paginateLink = (link) => {
  currentUrl.value = link;
}

/**
 * Apply filter
 */
const filterAction = (search) => {
  Object.assign(query, { filter: search, columnFilters: JSON.stringify(columnFilters.value) });
}

/**
 * Apply sorting
 */
const sortAction = (column) => {
  if (! column.sortable) {
    return;
  }

  Object.assign(query, { sort: (query.sort == "asc" ? "desc" : "asc") , columnSort: column.key });
}

/**
 * Delete record
 */
 const deleteAction = async (record, action) => {
  emitter.emit('open-confirm-modal', {
    agree: async () => {
      await axois({
        method : action.method,
        url: `${action.url}/${record.id}`
      })
      .then(response => {
        refresh();

        emitter.emit('add-flash', { type: 'success', message: response.data.message });
      })
      .catch(error => { 
          if (error.status == 422) {
              setErrors(error.response.data.errors);

              return;
          }

          emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message ?? "Something went wrong!" });
      })
    }
  });
}

/**
 * Redirect to edit page.
 */
const editAction = (record, action) => {
  router.push(`${action.url}/${record.id}`);
}

/**
 * Refresh records
 */
const refresh = async () => {
  await axois.get(`${currentUrl.value}`, {
    params: query
  })
  .then(response => {
    paginationDetails.value = response.data.data;

    records.value = paginationDetails.value.data;
  })
  .catch(error => {
      if (error.status == 422) {
          setErrors(error.response.data.errors);

          return;
      }

      emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message ?? "Something went wrong!" });
  })
}

/**
 * Fetch record after mount
 */
onMounted(() => {
  fetchRecords(query)
});

/**
 * Expose functions so the parent can call them
 */
 defineExpose({ refresh });
</script>
