<template>
  <AuthLayout title="Owner Login">
    <form @submit.prevent="submitLogin" class="w-full">
      <div class="mb-4">
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input
          v-model="form.username"
          type="text"
          id="username"
          class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
          autocomplete="username"
          required
        />
      </div>
      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input
          v-model="form.password"
          type="password"
          id="password"
          class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
          :class="{ 'border-red-500': form.errors && form.errors.password }"
          autocomplete="current-password"
          required
        />
        <span v-if="form.errors && form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</span>
      </div>
      <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Login</button>
        <Link href="/register" class="text-blue-600 hover:underline">Sign Up</Link>
      </div>
      <div v-if="form.errors && (form.errors.username || form.errors.password)" class="mt-4">
        <span class="text-red-500 text-sm">Invalid username or password.</span>
      </div>
    </form>
  </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const form = useForm({
  username: '',
  password: '',
});

const submitLogin = () => {
  form.post('/login/owner', {
    onError: (errors) => {
      // Handle form errors here
      console.log(errors);
    },
  });
};
</script>