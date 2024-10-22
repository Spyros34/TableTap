<template>
    <form @submit.prevent="updateProfile">
      <!-- Form Fields -->
      <button type="submit">Save</button>
    </form>
  </template>
  
  <script setup>
  import { useForm } from '@inertiajs/vue3';
  
  const form = useForm({
    name: '',
    email: '',
    // Other fields
  });
  
  function updateProfile() {
    form.put(route('profile.update'));
  }
  </script>