<template>
  <AuthLayout title="Waiter Login">
    <form @submit.prevent="submitLogin" class="w-full">
      <div class="mb-4">
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input
          v-model="form.username"
          type="text"
          id="username"
          class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
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
          required
        />
        <span v-if="form.errors && form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</span>
      </div>
      <div class="flex flex-col space-y-4">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Login</button>
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

const form = useForm({
  username: '',
  password: '',
});

const submitLogin = () => {
  form.post('/login/waiter', {
    onError: (errors) => {
      console.log(errors);
    },
  });
};
</script>