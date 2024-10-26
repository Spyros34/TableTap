<template>
  <MainLayout :title="title">
    <div class="grid 2xl:grid-cols-1 lg:grid-cols-1 md:grid-cols-1 sm:grid-cols-1 mr-6 gap-5">
      <Widget maxWidth="full" align="center">
        <div class="flex items-center justify-center mb-4">
          <h3 class="text-lg font-semibold break-words mr-2">Search Kitchen</h3>
          <span class="material-icons text-gray-600 group-hover:text-blue-600">kitchen</span>
        </div>
        
        <div class="mb-4 flex justify-center">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search..."
            class="w-96 p-2 border border-gray-300 rounded-lg"
          />
        </div>

        <div class="mt-10" v-if="filteredKitchenItems.length">
          <ul>
            <li v-for="item in limitedKitchenItems" :key="item.id" class="mb-2">
              <Widget class="w-full max-w-44 mx-auto mb-4">
                <div class="flex justify-between items-center w-full">
                  <p class="text-gray-700">{{ item.name }}</p>
                  <button
                    @click="deleteItem(item.id)"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                    <span class="material-icons mt-1">delete</span>
                  </button>
                </div>
              </Widget>
            </li>
          </ul>
        </div>
        <div v-else>
          <p>No results found.</p>
        </div>
      </Widget>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import Widget from '@/Widgets/Widget.vue';
import axios from 'axios';
import toastr from 'toastr'; // Import toastr
import 'toastr/build/toastr.min.css'; // Import toastr CSS

// Configure Toastr options
toastr.options = {
  closeButton: true,
  progressBar: true,
  positionClass: 'toast-top-right',
  timeOut: 5000,
};

// Define the search query, title, kitchen items, and flash message
const searchQuery = ref('');
const title = ref('Kitchen');
const kitchenItems = ref(usePage().props.kitchenItems);
const flashMessage = ref(usePage().props.flash?.success || '');

// Computed properties for filtered and limited items
const filteredKitchenItems = computed(() => {
  if (!searchQuery.value) return kitchenItems.value;
  return kitchenItems.value.filter(item =>
    item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const maxItems = 5;
const limitedKitchenItems = computed(() => {
  return filteredKitchenItems.value.slice(0, maxItems);
});

// Delete item function using Axios
const deleteItem = async (id) => {
  if (confirm('Are you sure you want to delete this item?')) {
    try {
      const response = await axios.delete(route('kitchen.destroy', id));
      
      // Update kitchen items and display success message
      kitchenItems.value = kitchenItems.value.filter(item => item.id !== id);
      flashMessage.value = response.data.success;
      toastr.success(flashMessage.value);
    } catch (error) {
      toastr.error('An error occurred while deleting the item.');
      console.error(error);
    }
  }
};

// Display flash message on mount if it exists
onMounted(() => {
  if (flashMessage.value) {
    toastr.success(flashMessage.value);
  }
});
</script>
