<template>
  <AuthLayout title="Kitchen Login">
    <form @submit.prevent="submitLogin" class="w-full">
      <!-- Username Field -->
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

      <!-- Password Field -->
      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input
          v-model="form.password"
          type="password"
          id="password"
          class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
          :class="{ 'border-red-500': form.errors.password }"
          autocomplete="current-password"
          required
        />
        <span v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</span>
      </div>

      <!-- Shop Selection Field -->
      <div class="mb-4">
        <label for="shop" class="block text-sm font-medium text-gray-700">Shop</label>
        <select
          v-model="form.shop_id"
          id="shop"
          class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
          required
        >
          <option v-for="shop in shops" :key="shop.id" :value="shop.id">{{ shop.brand }}</option>
        </select>
      </div>

      <!-- Submit Button -->
      <div class="flex items-center justify-between">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">Login</button>
      </div>

      <!-- General Errors -->
      <div v-if="form.errors.username || form.errors.password || form.errors.shop_id" class="mt-4">
        <span class="text-red-500 text-sm">Invalid credentials or shop.</span>
      </div>
    </form>
  </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { useForm, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

// Reactive state for shops
const shops = ref([]);

// Form data
const form = useForm({
  username: '',
  password: '',
  shop_id: '', // Include shop_id
});

const fetchShops = async () => {
  try {
    const response = await fetch('/shops');
    if (!response.ok) {
      throw new Error(`Failed to fetch shops: ${response.statusText}`);
    }
    const data = await response.json();
    if (data.shops && Array.isArray(data.shops)) {
      shops.value = data.shops;
    } else {
      console.error('Unexpected response format:', data);
      shops.value = [];
    }
  } catch (error) {
    console.error('Failed to load shops:', error);
    shops.value = [];
  }
};

// Call fetchShops on component mount
onMounted(() => {
  fetchShops();
});

// Submit the login form
const submitLogin = () => {
  form.post('/login/kitchen', {
    onError: (errors) => {
      console.error('Login failed:', errors);
    },
  });
};
</script>