<template>
    <div class="grid 2xl:grid-cols-1 lg:grid-cols-1 md:grid-cols-1 sm:grid-cols-1 mr-6 gap-5 ">
      <Widget maxWidth="full" align="center" > 
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
      <!-- Staff content goes here -->
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </Widget>
    </div>
</template>

<script setup>
import Widget from '@/Widgets/Widget.vue';
import { ref, onMounted  } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';


onMounted(() => {
  const { props } = usePage();
  props.title = 'Kitchen';
});
</script>

