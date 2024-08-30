<template>
    <AuthLayout title="Create Shop">
      <form @submit.prevent="submitShop" class="w-full">
        <div class="mb-4">
          <label for="storeName" class="block text-sm font-medium text-gray-700">Store Name</label>
          <input
            v-model="form.storeName"
            type="text"
            id="storeName"
            class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
            required
          />
          <span v-if="form.errors.storeName" class="text-red-500 text-sm">{{ form.errors.storeName }}</span>
        </div>
        <div class="mb-4">
          <label for="storeType" class="block text-sm font-medium text-gray-700">Store Type</label>
          <select
            v-model="form.storeType"
            id="storeType"
            class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
            required
          >
            <option value="">Select Store Type</option>
            <option value="Restaurant">Restaurant</option>
            <option value="Cafe">Cafe</option>
            <option value="Tavern">Tavern</option>
            <option value="Grill">Grill</option>
            <option value="Kebab">Kebab</option>
            <option value="Bar">Bar</option>
            <option value="Other">Other</option>
          </select>
          <span v-if="form.errors.storeType" class="text-red-500 text-sm">{{ form.errors.storeType }}</span>
        </div>
        <div class="mb-4">
          <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
          <input
            v-model="form.address"
            type="text"
            id="address"
            class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
            required
          />
          <span v-if="form.errors.address" class="text-red-500 text-sm">{{ form.errors.address }}</span>
        </div>
        <div class="mb-4">
          <label for="region" class="block text-sm font-medium text-gray-700">Region</label>
          <input
            v-model="form.region"
            type="text"
            id="region"
            class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
            required
          />
          <span v-if="form.errors.region" class="text-red-500 text-sm">{{ form.errors.region }}</span>
        </div>
        <div class="mb-4">
          <label for="city" class="block text-sm font-medium text-gray-700">City</label>
          <input
            v-model="form.city"
            type="text"
            id="city"
            class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
            required
          />
          <span v-if="form.errors.city" class="text-red-500 text-sm">{{ form.errors.city }}</span>
      </div>
      <div class="mb-4">
        <label for="postalCode" class="block text-sm font-medium text-gray-700">Postal Code</label>
        <input
          v-model="form.postalCode"
          type="text"
          id="postalCode"
          class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
          required
        />
        <span v-if="form.errors.postalCode" class="text-red-500 text-sm">{{ form.errors.postalCode }}</span>
      </div>
      <div class="mb-4">
        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
        <input
          v-model="form.phone"
          type="text"
          id="phone"
          class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
          required
        />
        <span v-if="form.errors.phone" class="text-red-500 text-sm">{{ form.errors.phone }}</span>
      </div>
      <div class="flex items-center justify-between">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">Create Shop</button>
      </div>
    </form>
  </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  storeName: '',
  storeType: '',
  address: '',
  region: '',
  city: '',
  postalCode: '',
  phone: '',
});

const submitShop = () => {
  form.post('/create-shop', {
    onError: (errors) => {
      console.log(errors);
    },
  });
};
</script>