<template>
  <MainLayout :title="title">
    <div class="grid 2xl:grid-cols-1 lg:grid-cols-1 md:grid-cols-1 sm:grid-cols-1 mr-6 gap-5">
      <Widget maxWidth="full" align="center">
        <!-- Floating Plus Button for Larger Screens -->
        <div class="flex items-end justify-end mb-4 mr-4 sm:flex">
          <button @click="showModal = true" class="bg-green-500 hover:bg-green-700 text-white font-bold py-0 px-2 rounded">
            <span class="material-icons py-1">add</span>
          </button>
        </div>

        <!-- "Search Kitchen" Header with Icon, Centered with Search Bar -->
        <div class="flex flex-col items-center mb-4">
          <div class="flex items-center space-x-2 mb-2">
            <span class="material-icons text-gray-600 group-hover:text-blue-600">kitchen</span>
            <h3 class="text-lg font-semibold break-words">Search Kitchen</h3>
          </div>
          <div class="flex items-center space-x-2 w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl">
            <input v-model="searchQuery" type="text" placeholder="Search..." class="w-full p-2 border border-gray-300 rounded-lg" />
          </div>
        </div>

        <!-- Kitchen List -->
        <div class="mt-10 mb-5" v-if="filteredKitchenItems.length">
          <ul class="space-y-4">
            <li v-for="item in limitedKitchenItems" :key="item.id" class="flex justify-center">
              <Widget class="w-full max-w-xs sm:max-w-sm md:max-w-sm lg:max-w-sm xl:max-w-sm mx-auto p-4 border border-gray-200 rounded-lg shadow-md">
                <div class="flex justify-between items-center w-full cursor-pointer" @click="openEditModal(item)">
                  <p class="text-gray-700">{{ item.name }}</p>
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
      </Widget>

      <!-- Create Kitchen Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center">
        <form @submit.prevent="validateForm('create')" class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
          <h3 class="text-lg font-semibold mb-4 text-center">Create New Kitchen</h3>
          
          <div class="mb-4">
            <input v-model="form.name" type="text" placeholder="Kitchen Name" class="w-full p-2 border border-gray-300 rounded-lg" />
            <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
          </div>

          <div class="mb-4">
            <input v-model="form.password" type="password" placeholder="Password" class="w-full p-2 border border-gray-300 rounded-lg" autocomplete="new-password" />
            <p v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password }}</p>
          </div>

          <div class="mb-4">
            <input v-model="form.password_confirmation" type="password" placeholder="Confirm Password" class="w-full p-2 border border-gray-300 rounded-lg" autocomplete="new-password" />
            <p v-if="errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ errors.password_confirmation }}</p>
          </div>

          <div class="flex justify-end space-x-2">
            <button @click="showModal = false" type="button" class="text-gray-500 font-bold py-2 px-4 rounded">Cancel</button>
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Create</button>
          </div>
        </form>
      </div>

      <!-- Edit Kitchen Modal -->
      <div v-if="showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center">
        <form @submit.prevent="validateForm('update')" class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
          <h3 class="text-lg font-semibold mb-4 text-center">Edit Kitchen</h3>

          <div class="mb-4">
            <input v-model="form.name" type="text" placeholder="Kitchen Name" class="w-full p-2 border border-gray-300 rounded-lg" />
            <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
          </div>

          <div class="mb-4">
            <input v-model="form.password" type="password" placeholder="New Password" class="w-full p-2 border border-gray-300 rounded-lg" autocomplete="new-password" />
            <p v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password }}</p>
          </div>

          <div class="mb-4">
            <input v-model="form.password_confirmation" type="password" placeholder="Confirm New Password" class="w-full p-2 border border-gray-300 rounded-lg" autocomplete="new-password" />
            <p v-if="errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ errors.password_confirmation }}</p>
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
import { ref, computed, onMounted } from 'vue';
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
const title = ref('Kitchen');
const kitchenItems = ref(usePage().props.kitchenItems || []);
const flashMessage = ref(usePage().props.flash?.success || '');

const showModal = ref(false);
const showEditModal = ref(false);
const form = useForm({
  name: '',
  password: '',
  password_confirmation: ''
});
const errors = ref({});
const currentKitchen = ref(null);

const validateForm = (action) => {
  errors.value = {};

  // Check if at least one field is provided
  if (!form.name && !form.password) {
    errors.value.general = "Please fill in at least one field to update.";
  }

  if (form.name && form.name.length === 0) {
    errors.value.name = "Kitchen name is required.";
  }

  if (form.password && form.password.length < 8) {
    errors.value.password = "Password must be at least 8 characters long.";
  }

  if (form.password && form.password !== form.password_confirmation) {
    errors.value.password_confirmation = "Passwords do not match.";
  }

  // Only call the action if no validation errors are present
  if (Object.keys(errors.value).length === 0) {
    action === 'create' ? createKitchen() : updateKitchen();
  }
};

const filteredKitchenItems = computed(() => {
  if (!searchQuery.value) return kitchenItems.value;
  return kitchenItems.value.filter(item => item?.name?.toLowerCase().includes(searchQuery.value.toLowerCase()));
});
const maxItems = 5;
const limitedKitchenItems = computed(() => filteredKitchenItems.value.slice(0, maxItems));

const createKitchen = () => {
  form.clearErrors();
  form.post('/kitchens', {
    onSuccess: (page) => {
      const newKitchen = page.props.kitchenItems[page.props.kitchenItems.length - 1];
      if (newKitchen && newKitchen.name) {
        kitchenItems.value.push(newKitchen);
        toastr.success('Kitchen created successfully.');
      } else {
        toastr.error('Failed to retrieve the created kitchen data.');
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

const openEditModal = (kitchen) => {
    currentKitchen.value = kitchen;
    form.name = kitchen.name;
    form.password = '';
    form.password_confirmation = ''; // Reset confirmation field
    showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
  form.reset();
};

const updateKitchen = () => {
  if (!form.name && !form.password) {
    toastr.error('Please enter at least one field to update.');
    return;
  }

  form.put(`/kitchens/${currentKitchen.value.id}`, {
    onSuccess: () => {
      toastr.success('Kitchen updated successfully.');
      
      // Update kitchen in kitchenItems
      const index = kitchenItems.value.findIndex(item => item.id === currentKitchen.value.id);
      if (index !== -1) {
        if (form.name) kitchenItems.value[index].name = form.name;
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
  if (!confirm("Are you sure you want to delete this kitchen? This action cannot be undone.")) {
    return; // Exit if the user cancels the confirmation
  }

  form.delete(`/kitchen/${id}`, {
    onSuccess: () => {
      kitchenItems.value = kitchenItems.value.filter(item => item.id !== id);
      toastr.success('Kitchen deleted successfully.');
    },
    onError: () => {
      toastr.error('An error occurred while deleting the kitchen.');
    },
  });
};


</script>