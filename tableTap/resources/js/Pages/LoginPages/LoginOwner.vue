<template>
    <AuthLayout title="Owner Login">
      <form @submit.prevent="submitLogin" class="w-full">
        <div class="mb-4">
          <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
          <input
            v-model="username"
            type="text"
            id="username"
            class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
            required
          />
        </div>
        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input
            v-model="password"
            type="password"
            id="password"
            class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
            required
          />
        </div>
        <div class="flex items-center justify-between">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Login</button>
          <Link href="/register" class="text-blue-600 hover:underline">Sign Up</Link>
        </div>
      </form>
    </AuthLayout>
</template>
  
<script setup>
  import AuthLayout from '@/Layouts/AuthLayout.vue';
  import { ref } from 'vue';
  import { Link, router } from '@inertiajs/vue3';
  
  const username = ref('');
  const password = ref('');
  
  const submitLogin = () => {
    router.post('/login/owner', { username: username.value, password: password.value });
  };
  </script>