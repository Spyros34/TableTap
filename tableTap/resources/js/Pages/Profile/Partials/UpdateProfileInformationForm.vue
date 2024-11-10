<template >
  <div>
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl font-bold">Profile Information <button v-if="!isEditing" @click="isEditing = true" class="text-gray-500 hover:text-blue-500 ml-3">
        <span class="material-icons">edit</span>
      </button></h2>
      
    </div>
    
    <form v-if="isEditing" @submit.prevent="submitProfile" class="w-full">
      <!-- Owner Information Fields -->
      <div v-for="field in ownerFields" :key="field.id" class="mb-4">
        <label :for="field.id" class="block text-sm font-medium text-gray-700">{{ field.label }}</label>
        <input
          v-model="form[field.id]"
          :type="field.type"
          :id="field.id"
          :placeholder="placeholders[field.id]"
          @blur="validateField(field.id)"
          class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
        />
        <span v-if="form.errors[field.id]" class="text-red-500 text-sm">{{ form.errors[field.id] }}</span>
      </div>

      <!-- Shop Information Fields -->
      <div v-for="field in shopFields" :key="field.id" class="mb-4">
        <label :for="field.id" class="block text-sm font-medium text-gray-700">{{ field.label }}</label>

        <!-- Dropdown for Store Type -->
        <div v-if="field.id === 'storeType'">
          <select
            v-model="form[field.id]"
            :id="field.id"
            @blur="validateField(field.id)"
            class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
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
          <span v-if="form.errors[field.id]" class="text-red-500 text-sm">{{ form.errors[field.id] }}</span>
        </div>

        <!-- Input for Other Fields -->
        <div v-else>
          <input
            v-model="form[field.id]"
            :type="field.type"
            :id="field.id"
            :placeholder="placeholders[field.id]"
            @blur="validateField(field.id)"
            class="mt-1 p-2 w-full border border-gray-300 rounded-lg"
          />
          <span v-if="form.errors[field.id]" class="text-red-500 text-sm">{{ form.errors[field.id] }}</span>
        </div>
      </div>

      <div class="flex items-center justify-between mt-4">
        <button @click="cancelEdit" type="button" class="text-gray-500 px-4 py-2 rounded-lg">Cancel</button>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">Save Changes</button>
      </div>
    </form>

    <div v-else>
      <!-- Display Profile Information in View Mode -->
      <div v-for="field in ownerFields" :key="field.id" class="mb-4">
        <p class="text-sm font-medium text-gray-700">{{ field.label }}</p>
        <p class="text-gray-900">{{ placeholders[field.id] || '-' }}</p>
      </div>

      <div v-for="field in shopFields" :key="field.id" class="mb-4">
        <p class="text-sm font-medium text-gray-700">{{ field.label }}</p>
        <p class="text-gray-900">{{ placeholders[field.id] || '-' }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

toastr.options = {
  closeButton: true,
  progressBar: true,
  positionClass: 'toast-top-right',
  timeOut: 5000,
};

const props = defineProps({
  owner: Object,
  shop: Object,
});

const isEditing = ref(false); // New ref to track edit mode

const placeholders = ref({
  name: props.owner.name || 'Enter name',
  surname: props.owner.surname || 'Enter surname',
  username: props.owner.username || 'Enter username',
  email: props.owner.email || 'Enter email',
  storeName: props.shop.storeName || 'Enter store name',
  storeType: props.shop.storeType || 'Select store type',
  address: props.shop.address || 'Enter address',
  region: props.shop.region || 'Enter region',
  city: props.shop.city || 'Enter city',
  postalCode: props.shop.postalCode || 'Enter postal code (5 digits)',
  phone: props.shop.phone || 'Enter phone number (10 digits)',
});

const form = useForm({
  name: '',
  surname: '',
  username: '',
  email: '',
  storeName: '',
  storeType: '',
  address: '',
  region: '',
  city: '',
  postalCode: '',
  phone: '',
});

const ownerFields = ref([
  { id: 'name', label: 'Name', type: 'text' },
  { id: 'surname', label: 'Surname', type: 'text' },
  { id: 'username', label: 'Username', type: 'text' },
  { id: 'email', label: 'Email', type: 'email' },
]);

const shopFields = ref([
  { id: 'storeName', label: 'Store Name', type: 'text' },
  { id: 'storeType', label: 'Store Type', type: 'text' },
  { id: 'address', label: 'Address', type: 'text' },
  { id: 'region', label: 'Region', type: 'text' },
  { id: 'city', label: 'City', type: 'text' },
  { id: 'postalCode', label: 'Postal Code', type: 'text' },
  { id: 'phone', label: 'Phone Number', type: 'text' },
]);

const validationPatterns = {
  email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
  postalCode: /^[0-9]{5}$/,
  phone: /^\d{10}$/,
  region: /^[a-zA-Z\s]+$/,
  city: /^[a-zA-Z\s]+$/,
};

const validateField = (field) => {
  const value = form[field];
  let error = '';

  if (field === 'email' && value && !validationPatterns.email.test(value)) {
    error = 'Please enter a valid email address.';
  } else if (field === 'postalCode' && value && !validationPatterns.postalCode.test(value)) {
    error = 'Please enter a valid 5-digit postal code.';
  } else if (field === 'phone' && value && !validationPatterns.phone.test(value)) {
    error = 'Please enter a valid 10-digit phone number.';
  } else if ((field === 'region' || field === 'city') && value && !validationPatterns[field].test(value)) {
    error = `Please enter a valid ${field} name (letters only).`;
  }

  form.errors[field] = error;
};

const submitProfile = () => {
  const filledFields = Object.fromEntries(
    Object.entries(form.data()).filter(([_, value]) => value !== '')
  );

  if (Object.keys(filledFields).length === 0) {
    toastr.info('Please fill at least one field before submitting.');
    return;
  }

  let hasClientErrors = false;
  for (const field in filledFields) {
    validateField(field);
    if (form.errors[field]) {
      hasClientErrors = true;
    }
  }

  if (hasClientErrors) {
    toastr.error('Please correct the errors in the form.');
    return;
  }

  form.put(route('profile.update'), {
    data: filledFields,
    onSuccess: () => {
      toastr.success('Profile updated successfully.');
      Object.keys(placeholders.value).forEach((key) => {
        placeholders.value[key] = form[key] || placeholders.value[key];
      });
      form.reset();
      isEditing.value = false;
    },
    onError: (errors) => {
      toastr.error('Please review the errors in the form.');
      form.errors = errors;
    },
  });
};

const cancelEdit = () => {
  isEditing.value = false;
  form.reset();
};
</script>