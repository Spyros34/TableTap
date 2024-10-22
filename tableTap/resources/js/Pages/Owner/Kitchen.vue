<template>
  <MainLayout :title="title">
    <!-- Display success message -->
    <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
      {{ successMessage }}
    </div>
    
    <div class="grid 2xl:grid-cols-1 lg:grid-cols-1 md:grid-cols-1 sm:grid-cols-1 mr-6 gap-5">
      <Widget maxWidth="full" align="center">
        <div class="flex items-center justify-center mb-4">
          <h3 class="text-lg font-semibold break-words mr-2">Search Kitchen</h3>
          <span class="material-icons text-gray-600 group-hover:text-blue-600">kitchen</span>
        </div>
        
        <!-- Search bar -->
        <div class="mb-4 flex justify-center">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search..."
            class="w-96 p-2 border border-gray-300 rounded-lg"
          />
        </div>
        
        <!-- Display filtered results -->
        <div class="mt-10" v-if="filteredKitchenItems.length">
          <ul>
            <li v-for="item in limitedKitchenItems" :key="item.id" class="mb-2">
              <Widget class="w-full max-w-44 mx-auto mb-4">
                <!-- Flex container for name and delete button -->
                <div class="flex justify-between items-center w-full">
                  <p class="text-gray-700">{{ item.name }}</p>
                  <!-- Delete Button -->
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
import { usePage, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import Widget from '@/Widgets/Widget.vue';

// Search query and title
const searchQuery = ref('');
const title = ref('Kitchen');

// Get flash messages and kitchen items from the server response
const { props } = usePage();

const kitchenItems = ref(props.kitchenItems);
const flashMessage = ref(props.flash?.success || '');



// Computed property to filter the kitchen items based on the search query
const filteredKitchenItems = computed(() => {
  if (!searchQuery.value) return kitchenItems.value;
  return kitchenItems.value.filter(item =>
    item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

// Limit the number of displayed items (e.g., show 5 at max)
const maxItems = 5;
const limitedKitchenItems = computed(() => {
  return filteredKitchenItems.value.slice(0, maxItems);
});

// Function to handle deleting the item using Axios
const deleteItem = async (id) => {
  if (confirm('Are you sure you want to delete this item?')) {
    try {
      // Send delete request using Axios
      const response = await axios.delete(route('kitchen.destroy', id));
      
      // Update the kitchen items and set the success message
      kitchenItems.value = kitchenItems.value.filter(item => item.id !== id);
      flashMessage.value = response.data.success;
      
      // Trigger Toastr success message
      toastr.success(flashMessage.value);
    } catch (error) {
      toastr.error('An error occurred while deleting the item.');
      console.error(error);
    }
  }
};

// Handle showing the flash message on page mount
onMounted(() => {
  if (usePage().props.flash?.success) {
    flashMessage.value = usePage().props.flash.success;
    toastr.success(flashMessage.value);
  }
});
</script>
