<template>
    <div class="grid 2xl:grid-cols-1 lg:grid-cols-1 md:grid-cols-1 sm:grid-cols-1 mr-6 gap-5">
      <Widget maxWidth="full" align="center">
        <!-- Owner Registration Form -->
        <div class="flex items-center justify-center mb-4">
          <h3 class="text-lg font-semibold break-words mr-2">Owner Registration</h3>
        </div>
        <div class="mb-4 flex justify-center">
          <form @submit.prevent="submitRegister" class="w-96">
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
              <label for="surname" class="block text-sm font-medium text-gray-700">Surname</label>
              <input
                v-model="surname"
                type="text"
                id="surname"
                class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
                required
              />
            </div>
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
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input
                v-model="email"
                type="email"
                id="email"
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
            <div class="mb-4">
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
              <input
                v-model="password_confirmation"
                type="password"
                id="password_confirmation"
                class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
                required
              />
            </div>
            <div class="flex items-center justify-between">
              <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Register</button>
              <Link href="/login/owner" class="text-blue-600 hover:underline">Login</Link>
            </div>
          </form>
        </div>
      </Widget>
    </div>
</template>
  
  <script setup>
  import { ref } from 'vue';
  import { Link, router } from '@inertiajs/vue3';
  import Widget from '@/Widgets/Widget.vue';
  
  const name = ref('');
  const surname = ref('');
  const username = ref('');
  const email = ref('');
  const password = ref('');
  const password_confirmation = ref('');
  
  const submitRegister = () => {
    // Perform registration action here
    console.log('Registration submitted', name.value, surname.value, username.value, email.value, password.value, password_confirmation.value);
    // Example using Inertia.js to post data
    router.post('/register', {
      name: name.value,
      surname: surname.value,
      username: username.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value
    });
  };
  </script>