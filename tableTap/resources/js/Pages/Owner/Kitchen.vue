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
          
          <!-- Responsive Search Bar -->
          <div class="flex items-center space-x-2 w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search..."
              class="w-full p-2 border border-gray-300 rounded-lg"
            />
          </div>
        </div>

        <!-- Kitchen List -->
        <div class="mt-10 mb-5" v-if="filteredKitchenItems.length">
          <ul class="space-y-4">
            <li v-for="item in limitedKitchenItems" :key="item.id" class="flex justify-center">
              <Widget class="w-full max-w-xs sm:max-w-sm md:max-w-sm lg:max-w-sm xl:max-w-sm mx-auto p-4 border border-gray-200 rounded-lg shadow-md">
                <div class="flex justify-between items-center w-full">
                  <p class="text-gray-700">{{ item.name }}</p>
                  <button
                    @click="deleteItem(item.id)"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 pt-2 px-2 rounded">
                    <span class="material-icons">delete</span>
                  </button>
                </div>
              </Widget>
            </li>
          </ul>
        </div>
        <div v-else>
          <p class="text-center">No results found.</p>
        </div>
      </Widget>

      <!-- Create Kitchen Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center">
        <form @submit.prevent="createKitchen" class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
          <h3 class="text-lg font-semibold mb-4 text-center">Create New Kitchen</h3>
          
          <div class="mb-4">
            <input v-model="form.name" type="text" placeholder="Kitchen Name" class="w-full p-2 border border-gray-300 rounded-lg" />
            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
          </div>

          <div class="mb-4">
            <input v-model="form.password" type="password" placeholder="Password" class="w-full p-2 border border-gray-300 rounded-lg" autocomplete="new-password" />
            <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
          </div>

          <div class="mb-4">
            <input v-model="form.password_confirmation" type="password" placeholder="Confirm Password" class="w-full p-2 border border-gray-300 rounded-lg" autocomplete="new-password" />
            <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ form.errors.password_confirmation }}</p>
          </div>

          <div class="flex justify-end space-x-2">
            <button @click="showModal = false" type="button" class="text-gray-500 font-bold py-2 px-4 rounded">Cancel</button>
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Create</button>
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
const form = useForm({
  name: '',
  password: '',
  password_confirmation: ''
});

const filteredKitchenItems = computed(() => {
  if (!searchQuery.value) return kitchenItems.value;
  return kitchenItems.value.filter(item => item?.name?.toLowerCase().includes(searchQuery.value.toLowerCase()));
});
const maxItems = 5;
const limitedKitchenItems = computed(() => filteredKitchenItems.value.slice(0, maxItems));

const createKitchen = () => {
  form.clearErrors(); // Clear previous errors before submission
  
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
      // Display form-specific errors in toastr for visibility
      Object.keys(form.errors).forEach((field) => {
        toastr.error(form.errors[field]);
      });
    },
  });
};

// Delete a kitchen item
const deleteItem = (id) => {
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

onMounted(() => {
  if (flashMessage.value) {
    toastr.success(flashMessage.value);
  }
});
</script>