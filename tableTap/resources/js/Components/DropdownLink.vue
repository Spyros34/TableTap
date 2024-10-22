<template>
    <Link
      :href="href"
      :method="method"
      as="button"
      class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out"
    >
      <slot />
    </Link>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  import { Link } from '@inertiajs/vue3';
  
  const props = defineProps({
    href: String,
    method: String,
  });
  </script>