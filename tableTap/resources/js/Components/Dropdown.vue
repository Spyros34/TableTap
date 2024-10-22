<template>
    <div class="relative">
      <div @click="toggleDropdown">
        <slot name="trigger"></slot>
      </div>
      <div v-if="isOpen" class="absolute mt-2 w-48 rounded-md shadow-lg">
        <div class="rounded-md bg-white shadow-xs">
          <slot name="content"></slot>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch, onMounted } from 'vue';
  
  const isOpen = ref(false);
  
  function toggleDropdown() {
    isOpen.value = !isOpen.value;
  }
  
  function closeDropdown() {
    isOpen.value = false;
  }
  
  onMounted(() => {
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.relative')) {
        closeDropdown();
      }
    });
  });
  </script>