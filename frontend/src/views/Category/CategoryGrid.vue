<template>
  <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] py-2 px-2">
    <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3 ">
      <div></div>
      <div class="ml-3">
        <div class="w-full max-w-sm min-w-[200px] relative">
          <div class="relative">
            <input v-model="search" class="bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md" placeholder="Search for invoice..." />
            <button class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center bg-white rounded" type="button">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="relative flex flex-col w-full h-full overflow-auto text-gray-700 bg-white shadow-md rounded-lg bg-clip-border">
      <table class="w-full text-left table-auto min-w-max">
        <thead class="bg-gray-100 text-sm">
          <tr>
            <th @click="sort('invoiceNumber')" class="p-4 border-b cursor-pointer">Invoice Number</th>
            <th @click="sort('customer')" class="p-4 border-b cursor-pointer">Customer</th>
            <th @click="sort('amount')" class="p-4 border-b cursor-pointer">Amount</th>
            <th @click="sort('issued')" class="p-4 border-b cursor-pointer">Issued</th>
            <th @click="sort('dueDate')" class="p-4 border-b cursor-pointer">Due Date</th>
          </tr>
        </thead>
        <tbody class="text-sm">
          <tr v-for="invoice in paginatedInvoices" :key="invoice.invoiceNumber" class="hover:bg-slate-50 border-b">
            <td class="p-4">{{ invoice.invoiceNumber }}</td>
            <td class="p-4">{{ invoice.customer }}</td>
            <td class="p-4">${{ invoice.amount }}</td>
            <td class="p-4">{{ invoice.issued }}</td>
            <td class="p-4">{{ invoice.dueDate }}</td>
          </tr>
        </tbody>
      </table>

      <div class="flex justify-between items-center px-4 py-3">
        <div class="text-sm text-slate-500">Showing {{ startItem }}-{{ endItem }} of {{ filteredInvoices.length }}</div>
        <div class="flex space-x-1">
          <button @click="prevPage" :disabled="currentPage === 1" class="px-3 py-1 border rounded">Prev</button>
          <button v-for="page in totalPages" :key="page" @click="currentPage = page" :class="{'bg-gray-800 text-white': currentPage === page}" class="px-3 py-1 border rounded">{{ page }}</button>
          <button @click="nextPage" :disabled="currentPage === totalPages" class="px-3 py-1 border rounded">Next</button>
        </div>
      </div>
    </div>
</div>
</template>

<script setup>
import { ref, computed } from 'vue';

const invoices = ref([
  { invoiceNumber: 'PROJ1001', customer: 'John Doe', amount: 1200, issued: '2024-08-01', dueDate: '2024-08-15' },
  { invoiceNumber: 'PROJ1002', customer: 'Jane Smith', amount: 850, issued: '2024-08-05', dueDate: '2024-08-20' },
  { invoiceNumber: 'PROJ1003', customer: 'Acme Corp', amount: 2500, issued: '2024-08-07', dueDate: '2024-08-21' },
  { invoiceNumber: 'PROJ1004', customer: 'Global Inc', amount: 4750, issued: '2024-08-10', dueDate: '2024-08-25' },
]);

const search = ref('');
const currentPage = ref(1);
const itemsPerPage = 2;
const sortKey = ref('');
const sortOrder = ref(1);

// Fetch invoices from API
const fetchInvoices = async () => {
  loading.value = true;
  try {
    const response = await axios.get('https://api.example.com/invoices'); // Replace with your API URL
    invoices.value = response.data;
  } catch (err) {
    error.value = 'Failed to load invoices';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const filteredInvoices = computed(() => {
  return invoices.value.filter(invoice =>
    invoice.customer.toLowerCase().includes(search.value.toLowerCase())
  );
});

const sortedInvoices = computed(() => {
  return [...filteredInvoices.value].sort((a, b) => {
    if (!sortKey.value) return 0;
    return (a[sortKey.value] > b[sortKey.value] ? 1 : -1) * sortOrder.value;
  });
});

const paginatedInvoices = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return sortedInvoices.value.slice(start, start + itemsPerPage);
});

const totalPages = computed(() => Math.ceil(sortedInvoices.value.length / itemsPerPage));
const startItem = computed(() => (currentPage.value - 1) * itemsPerPage + 1);
const endItem = computed(() => Math.min(currentPage.value * itemsPerPage, filteredInvoices.value.length));

const sort = (key) => {
  if (sortKey.value === key) {
    sortOrder.value *= -1;
  } else {
    sortKey.value = key;
    sortOrder.value = 1;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
};

const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};
</script>
