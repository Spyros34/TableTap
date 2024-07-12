<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">
      <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">{{ title }}</h2>
        <slot />
      </div>
    </div>
</template>
  
  <script setup>
  import { defineProps } from 'vue';
  
  const props = defineProps({
    title: {
      type: String,
      required: true
    }
  });
  </script>