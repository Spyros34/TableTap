<template>
    <div class="grid 2xl:grid-cols-1 lg:grid-cols-1 md:grid-cols-1 sm:grid-cols-1 mr-6 gap-5">
      <Widget maxWidth="full" align="center">
        <!-- Waiter Login Form -->
        <div class="flex items-center justify-center mb-4">
          <h3 class="text-lg font-semibold break-words mr-2">Waiter Login</h3>
        </div>
        <div class="mb-4 flex justify-center">
          <form @submit.prevent="submitLogin" class="w-96">
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
              <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg">Login</button>
            </div>
          </form>
        </div>
      </Widget>
    </div>
</template>
  
  <script setup>
  import { ref } from 'vue';
  import { Link, usePage, router } from '@inertiajs/vue3';
  import Widget from '@/Widgets/Widget.vue';
  
  const username = ref('');
  const password = ref('');
  
  const submitLogin = () => {
    // Perform login action here
    console.log('Login submitted', username.value, password.value);
    // Example using Inertia.js to post data
    router.post('/login/waiter', { username: username.value, password: password.value });
  };
  
  onMounted(() => {
    const { props } = usePage();
    props.title = 'Waiter Login';
  });
  </script>