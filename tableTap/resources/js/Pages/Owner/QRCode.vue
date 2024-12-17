<template>
  <MainLayout :title="title">
    <div class="grid 2xl:grid-cols-1 lg:grid-cols-1 md:grid-cols-1 sm:grid-cols-1 mr-6 gap-5">
      <Widget maxWidth="full" align="center">
        <!-- Add Table Button -->
        <div class="flex items-end justify-end mb-4 mr-4 sm:flex">
          <button @click="showModal = true" class="bg-green-500 hover:bg-green-700 text-white font-bold py-0 px-2 rounded">
            <span class="material-icons py-1">add</span>
          </button>
        </div>

        <!-- "Search Table" Header with Icon, Centered with Search Bar -->
        <div class="flex flex-col items-center mb-4">
          <div class="flex items-center space-x-2 mb-2">
            <span class="material-icons text-gray-600 group-hover:text-blue-600">search</span>
            <h3 class="text-lg font-semibold break-words">Search Table</h3>
          </div>
          <div class="flex items-center space-x-2 w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl">
            <input v-model="searchQuery" type="text" placeholder="Search..." class="w-full p-2 border border-gray-300 rounded-lg" />
          </div>
        </div>

        <!-- Table List -->
        <div class="mt-10 mb-5" v-if="paginatedTableItems.length">
          <ul class="space-y-4">
            <li v-for="table in paginatedTableItems" :key="table.id" class="flex justify-center">
              <Widget class="w-full max-w-xs sm:max-w-sm md:max-w-sm lg:max-w-sm xl:max-w-sm mx-auto p-4 border border-gray-200 rounded-lg shadow-md">
                <div class="flex justify-between items-center w-full">
                  <div>
                    <p class="text-gray-700">Table Number: {{ table.table_num }}</p>
                    <div class="mt-2">
                      <img :src="`/${table.qr_code}`" alt="QR Code" class="w-32 h-32 mx-auto" />
                    </div>
                  </div>
                  <div class="flex flex-col space-y-2">
                    <button @click="openEditModal(table)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
                      <span class="material-icons">edit</span>
                    </button>
                    <button @click="deleteItem(table.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                      <span class="material-icons">delete</span>
                    </button>
                    <a :href="`/tables/${table.id}/qrcode`" download class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded text-center">
  <span class="material-icons">download</span>
</a>
                  </div>
                </div>
              </Widget>
            </li>
          </ul>
        </div>
        <div v-else>
          <p class="text-center text-gray-600 text-lg mt-10">No results found.</p>
        </div>

        <!-- Pagination Controls -->
        <div class="flex justify-center items-center mt-6 space-x-4">
          <button 
            @click="prevPage" 
            :disabled="currentPage === 1" 
            class="px-3 py-1 border rounded bg-gray-100 hover:bg-gray-200 disabled:bg-gray-50 disabled:text-gray-400"
          >
            Previous
          </button>
          
          <span class="text-gray-700 font-semibold">
            Page {{ currentPage }} of {{ totalPages }}
          </span>
          
          <button 
            @click="nextPage" 
            :disabled="currentPage === totalPages" 
            class="px-3 py-1 border rounded bg-gray-100 hover:bg-gray-200 disabled:bg-gray-50 disabled:text-gray-400"
          >
            Next
          </button>
        </div>
      </Widget>

      <!-- Create Table Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center">
        <form @submit.prevent="validateForm('create')" class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
          <h3 class="text-lg font-semibold mb-4 text-center">Create New Table</h3>
          
          <div class="mb-4">
            <input v-model="form.table_num" type="text" placeholder="Table Number" class="w-full p-2 border border-gray-300 rounded-lg" />
            <p v-if="errors.table_num" class="text-red-500 text-sm mt-1">{{ errors.table_num }}</p>
          </div>
    
          <div class="flex justify-end space-x-2">
            <button @click="showModal = false" type="button" class="text-gray-500 font-bold py-2 px-4 rounded">Cancel</button>
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Create</button>
          </div>
        </form>
      </div>

      <!-- Edit Table Modal -->
      <div v-if="showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center">
        <form @submit.prevent="validateForm('update')" class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
          <h3 class="text-lg font-semibold mb-4 text-center">Edit Table</h3>
    
          <div class="mb-4">
            <input v-model="form.table_num" type="text" placeholder="Table Number" class="w-full p-2 border border-gray-300 rounded-lg" />
            <p v-if="errors.table_num" class="text-red-500 text-sm mt-1">{{ errors.table_num }}</p>
          </div>
    
          <div class="flex justify-end space-x-2">
            <button @click="closeEditModal" type="button" class="text-gray-500 font-bold py-2 px-4 rounded">Cancel</button>
            <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import toastr from 'toastr';
import MainLayout from '@/Layouts/MainLayout.vue';
import Widget from '@/Widgets/Widget.vue';
import 'toastr/build/toastr.min.css';

toastr.options = {
  closeButton: true,
  progressBar: true,
  positionClass: 'toast-top-right',
  timeOut: 5000,
};
const title = ref('QR-code');
const searchQuery = ref('');
const tableItems = ref(usePage().props.tableItems || []);
const flashMessage = ref(usePage().props.flash?.success || '');

const showModal = ref(false);
const showEditModal = ref(false);
const form = useForm({
  table_num: '',
  qr_code: '',
});
const errors = ref({});
const currentTable = ref(null);

// Validation logic
const validateForm = (action) => {
  errors.value = {};

  if (action === 'create') {
    if (!form.table_num || form.table_num.length === 0) {
      errors.value.table_num = "Table number is required.";
    }
  } else if (action === 'update') {
    if (!form.table_num) {
      errors.value.table_num = "Table number is required.";
    }
  }

  if (Object.keys(errors.value).length === 0) {
    action === 'create' ? createTable() : updateTable();
  }
};

// Filtered and paginated table items
const filteredTableItems = computed(() => {
  if (!searchQuery.value) return tableItems.value;
  return tableItems.value.filter(item =>
    item?.table_num?.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

// Pagination logic
const currentPage = ref(1);
const itemsPerPage = 5;

const totalPages = computed(() => Math.max(1, Math.ceil(filteredTableItems.value.length / itemsPerPage)));

const paginatedTableItems = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredTableItems.value.slice(start, end);
});

const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
};

const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};

// CRUD methods
const createTable = () => {
  form.clearErrors();
  form.post('/tables', {
    onSuccess: (page) => {
      const newTable = page.props.tableItems[page.props.tableItems.length - 1];
      if (newTable && newTable.table_num) {
        tableItems.value.push(newTable);
        toastr.success('Table created successfully.');
      } else {
        toastr.error('Failed to retrieve the created table data.');
      }
      form.reset();
      showModal.value = false;
    },
    onError: () => {
      Object.keys(form.errors).forEach((field) => {
        toastr.error(form.errors[field]);
      });
    },
  });
};

const openEditModal = (table) => {
  currentTable.value = table;
  form.table_num = table.table_num;
  showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
  form.reset();
};

const updateTable = () => {
  if (!form.table_num) {
    toastr.error('Please enter the table number to update.');
    return;
  }

  form.put(`/tables/${currentTable.value.id}`, {
    onSuccess: () => {
      toastr.success('Table updated successfully.');

      // Update table in tableItems
      const index = tableItems.value.findIndex(item => item.id === currentTable.value.id);
      if (index !== -1) {
        tableItems.value[index].table_num = form.table_num;
        tableItems.value[index].qr_code = props.updatedTable.qr_code;
      }

      showEditModal.value = false;
      form.reset();
    },
    onError: () => {
      Object.keys(form.errors).forEach((field) => {
        toastr.error(form.errors[field]);
      });
    },
  });
};

const deleteItem = (id) => {
  if (!confirm("Are you sure you want to delete this table? This action cannot be undone.")) {
    return;
  }

  form.delete(`/tables/${id}`, {
    onSuccess: () => {
      tableItems.value = tableItems.value.filter(item => item.id !== id);
      toastr.success('Table deleted successfully.');
    },
    onError: () => {
      toastr.error('An error occurred while deleting the table.');
    },
  });
};
</script>