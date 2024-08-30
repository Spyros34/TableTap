<template>
  <AuthLayout title="Owner Registration">
    <form @submit.prevent="submitRegistration" class="w-full">
      <div class="mb-4">
        <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
        <input
          v-model="form.firstName"
          type="text"
          id="firstName"
          class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
          required
        />
        <span v-if="form.errors.firstName" class="text-red-500 text-sm">{{ form.errors.firstName }}</span>
      </div>
      <div class="mb-4">
        <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
        <input
          v-model="form.lastName"
          type="text"
          id="lastName"
          class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
          required
        />
        <span v-if="form.errors.lastName" class="text-red-500 text-sm">{{ form.errors.lastName }}</span>
      </div>
      <div class="mb-4">
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input
          v-model="form.username"
          type="text"
          id="username"
          class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
          required
        />
        <span v-if="form.errors.username" class="text-red-500 text-sm">{{ form.errors.username }}</span>
      </div>
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input
          v-model="form.email"
          type="email"
          id="email"
          class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
          required
        />
        <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</span>
      </div>
      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input
          v-model="form.password"
          type="password"
          id="password"
          class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
          required
        />
        <span v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</span>
      </div>
      <div class="mb-4">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input
          v-model="form.password_confirmation"
          type="password"
          id="password_confirmation"
          class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
          required
        />
        <span v-if="form.errors.password_confirmation" class="text-red-500 text-sm">{{ form.errors.password_confirmation }}</span>
      </div>
      <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Register</button>
      </div>
    </form>
  </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  firstName: '',
  lastName: '',
  username: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const submitRegistration = () => {
  form.post('/register', {
    onError: (errors) => {
      console.log(errors);
    },
  });
};
</script>