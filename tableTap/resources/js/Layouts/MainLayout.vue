<template>
  <div class="flex">
    <Sidebar />
    <div class="flex-1 md:ml-64 bg-gray-100 min-h-screen">
      <header class="bg-white shadow p-4 py-10 flex justify-between items-center">
        <h1 class="text-2xl font-semibold">{{ title }}</h1>
        <div class="flex items-center">
          <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/150" alt="Profile">
          <span class="ml-3 text-lg">Jane S.</span>
        </div>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      </header>
      <main class="mt-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';
import Sidebar from './Sidebar.vue';

const props = defineProps({
  title: {
    type: String,
    required: true
  }
});
</script>
