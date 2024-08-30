<template>
  <AuthLayout title="Kitchen Login">

    <!-- Kitchen Login Form -->
    <div class="mb-4 flex justify-center">
      <form @submit.prevent="submitLogin" class="w-96">
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <input
            v-model="name"
            type="text"
            id="name"
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
          <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">Login</button>
        </div>
      </form>
    </div>
  </AuthLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

const name = ref('');
const password = ref('');

const submitLogin = () => {
  // Perform login action here
  console.log('Login submitted', name.value, password.value);
  // Example using Inertia.js to post data
  router.post('/login/kitchen', { name: name.value, password: password.value });
};

onMounted(() => {
  const { props } = usePage();
  props.title = 'Kitchen Login';
});
</script>

<style scoped>
.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

button {
  width: 100%;
  padding: 10px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}
</style>