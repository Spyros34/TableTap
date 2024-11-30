<template>
  <MainLayout :title="title">
    <div class="grid 2xl:grid-cols-1 lg:grid-cols-1 md:grid-cols-1 sm:grid-cols-1 mr-6 gap-5">
      <Widget maxWidth="full" align="center">
        <!-- Add Product Button -->
        <div class="flex items-end justify-end mb-4 mr-4 sm:flex">
          <button @click="showModal = true" class="bg-green-500 hover:bg-green-700 text-white font-bold py-0 px-2 rounded">
            <span class="material-icons py-1">add</span>
          </button>
        </div>

        <!-- "Search Product" Header with Icon, Centered with Search Bar -->
        <div class="flex flex-col items-center mb-4">
          <div class="flex items-center space-x-2 mb-2">
            <span class="material-icons text-gray-600 group-hover:text-blue-600">search</span>
            <h3 class="text-lg font-semibold break-words">Search Product</h3>
          </div>
          <div class="flex items-center space-x-2 w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl">
            <input v-model="searchQuery" type="text" placeholder="Search..." class="w-full p-2 border border-gray-300 rounded-lg" />
          </div>
        </div>

        <!-- Product List -->
        <div class="mt-10 mb-5" v-if="paginatedProductItems.length">
          <ul class="space-y-4">
            <li v-for="item in paginatedProductItems" :key="item.id" class="flex justify-center">
              <Widget class="w-full max-w-xs sm:max-w-sm md:max-w-sm lg:max-w-sm xl:max-w-sm mx-auto p-4 border border-gray-200 rounded-lg shadow-md">
                <div class="flex justify-between items-center w-full cursor-pointer" @click="openEditModal(item)">
                  <div>
                    <p class="text-gray-700">{{ item.name }}</p>
                    <p class="text-gray-500 text-sm">Price: {{ formatCurrency(item.price) }}</p>
                    <p class="text-gray-500 text-sm">Quantity: {{ item.quantity }}</p>
                    <p class="text-gray-500 text-sm">Availability: {{ item.availability ? 'Available' : 'Out of Stock' }}</p>
                  </div>
                  <button @click.stop="deleteItem(item.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 pt-2 px-2 rounded">
                    <span class="material-icons">delete</span>
                  </button>
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

      <!-- Create Product Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center">
        <form @submit.prevent="validateForm('create')" class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
          <h3 class="text-lg font-semibold mb-4 text-center">Create New Product</h3>
          
          <div class="mb-4">
            <input v-model="form.name" type="text" placeholder="Product Name" class="w-full p-2 border border-gray-300 rounded-lg" />
            <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
          </div>

          <div class="mb-4">
            <input v-model="form.price" type="number" step="0.01" placeholder="Price (€)" class="w-full p-2 border border-gray-300 rounded-lg" />
            <p v-if="errors.price" class="text-red-500 text-sm mt-1">{{ errors.price }}</p>
          </div>

          <div class="mb-4">
            <input v-model="form.quantity" type="number" placeholder="Quantity" class="w-full p-2 border border-gray-300 rounded-lg" />
            <p v-if="errors.quantity" class="text-red-500 text-sm mt-1">{{ errors.quantity }}</p>
          </div>

          <div class="mb-4">
            <select v-model.number="form.availability" class="w-full p-2 border border-gray-300 rounded-lg">
              <option :value="1">Available</option>
              <option :value="0">Out of Stock</option>
            </select>
            <p v-if="errors.availability" class="text-red-500 text-sm mt-1">{{ errors.availability }}</p>
          </div>

          <div class="flex justify-end space-x-2">
            <button @click="showModal = false" type="button" class="text-gray-500 font-bold py-2 px-4 rounded">Cancel</button>
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Create</button>
          </div>
        </form>
      </div>

      <!-- Edit Product Modal -->
      <div v-if="showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center">
        <form @submit.prevent="validateForm('update')" class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
          <h3 class="text-lg font-semibold mb-4 text-center">Edit Product</h3>

          <div class="mb-4">
            <input v-model="form.name" type="text" placeholder="Product Name" class="w-full p-2 border border-gray-300 rounded-lg" />
            <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
          </div>

          <div class="mb-4">
            <input v-model="form.price" type="number" step="0.01" placeholder="Price (€)" class="w-full p-2 border border-gray-300 rounded-lg" />
            <p v-if="errors.price" class="text-red-500 text-sm mt-1">{{ errors.price }}</p>
          </div>

          <div class="mb-4">
            <input v-model="form.quantity" type="number" placeholder="Quantity" class="w-full p-2 border border-gray-300 rounded-lg" />
            <p v-if="errors.quantity" class="text-red-500 text-sm mt-1">{{ errors.quantity }}</p>
          </div>

          <div class="mb-4">
            <select v-model.number="form.availability" class="w-full p-2 border border-gray-300 rounded-lg">
              <option :value="1">Available</option>
              <option :value="0">Out of Stock</option>
            </select>
            <p v-if="errors.availability" class="text-red-500 text-sm mt-1">{{ errors.availability }}</p>
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

const searchQuery = ref('');
const title = ref('Products');
const productItems = ref(usePage().props.productItems || []);
const flashMessage = ref(usePage().props.flash?.success || '');

const showModal = ref(false);
const showEditModal = ref(false);
const form = useForm({
  name: '',
  price: '',
  quantity: '',
  availability: 1
});
const errors = ref({});
const currentProduct = ref(null);

const validateForm = (action) => {
  errors.value = {};

  if (action === 'create') {
    // For creating, all fields are required
    if (!form.name || form.name.length === 0) {
      errors.value.name = "Product name is required.";
    }

    if (form.price === null || form.price === undefined || form.price === '') {
      errors.value.price = "Product price is required.";
    } else if (form.price <= 0) {
      errors.value.price = "Product price must be greater than zero.";
    }

    if (form.quantity === null || form.quantity === undefined || form.quantity === '') {
      errors.value.quantity = "Product quantity is required.";
    } else if (form.quantity < 0) {
      errors.value.quantity = "Product quantity cannot be negative.";
    }

    if (form.availability === null || form.availability === undefined || form.availability === '') {
      errors.value.availability = "Product availability status is required.";
    }
  } else if (action === 'update') {
    // For updating, check if at least one field is provided
    if (!form.name && !form.price && !form.quantity && form.availability === '') {
      errors.value.general = "Please fill in at least one field to update.";
    }
  }

  // Only call the action if no validation errors are present
  if (Object.keys(errors.value).length === 0) {
    action === 'create' ? createProduct() : updateProduct();
  }
};

const filteredProductItems = computed(() => {
  if (!searchQuery.value) return productItems.value;
  return productItems.value.filter(item => item?.name?.toLowerCase().includes(searchQuery.value.toLowerCase()));
});

// Pagination logic
const currentPage = ref(1);
const itemsPerPage = 5;

const totalPages = computed(() => Math.max(1, Math.ceil(filteredProductItems.value.length / itemsPerPage)));

const paginatedProductItems = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredProductItems.value.slice(start, end);
});

const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
};

const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat('el-GR', {
    style: 'currency',
    currency: 'EUR',
  }).format(value);
};

const createProduct = () => {
  form.clearErrors();
  form.post('/products', {
    onSuccess: (page) => {
      const newProduct = page.props.productItems[page.props.productItems.length - 1];
      if (newProduct && newProduct.name) {
        productItems.value.push(newProduct);
        toastr.success('Product created successfully.');
      } else {
        toastr.error('Failed to retrieve the created product data.');
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

const openEditModal = (product) => {
  currentProduct.value = product;
  form.name = product.name;
  form.price = product.price;
  form.quantity = product.quantity;
  form.availability = product.availability;
  showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
  form.reset();
};

const updateProduct = () => {
  if (!form.name && !form.price && !form.quantity && form.availability === '') {
    toastr.error('Please enter at least one field to update.');
    return;
  }

  form.put(`/products/${currentProduct.value.id}`, {
    onSuccess: () => {
      toastr.success('Product updated successfully.');

      // Update product in productItems
      const index = productItems.value.findIndex(item => item.id === currentProduct.value.id);
      if (index !== -1) {
        if (form.name) productItems.value[index].name = form.name;
        if (form.price) productItems.value[index].price = form.price;
        if (form.quantity) productItems.value[index].quantity = form.quantity;
        if (form.availability !== undefined) productItems.value[index].availability = form.availability;
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
  if (!confirm("Are you sure you want to delete this product? This action cannot be undone.")) {
    return; // Exit if the user cancels the confirmation
  }

  form.delete(`/products/${id}`, {
    onSuccess: () => {
      productItems.value = productItems.value.filter(item => item.id !== id);
      toastr.success('Product deleted successfully.');
    },
    onError: () => {
      toastr.error('An error occurred while deleting the product.');
    },
  });
};
</script>