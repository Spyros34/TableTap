<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
      <p class="mt-1 text-sm text-gray-600">
        Ensure your account is using a long, random password to stay secure.
      </p>
    </header>

    <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
      <!-- Hidden Username Field for Accessibility -->
      <input type="text" id="username" name="username" autocomplete="username" class="hidden" />

      <div>
        <InputLabel for="current_password" value="Current Password" />
        <TextInput
          id="current_password"
          v-model="form.current_password"
          type="password"
          class="mt-1 block w-full"
          autocomplete="current-password"
        />
        <InputError :message="form.errors.current_password" class="mt-2" />
      </div>

      <div>
        <InputLabel for="new_password" value="New Password" />
        <TextInput
          id="new_password"
          v-model="form.password"
          type="password"
          class="mt-1 block w-full"
          autocomplete="new-password"
        />
        <InputError :message="form.errors.password" class="mt-2" />
      </div>

      <div>
        <InputLabel for="password_confirmation" value="Confirm Password" />
        <TextInput
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          class="mt-1 block w-full"
          autocomplete="new-password"
        />
        <InputError :message="form.errors.password_confirmation" class="mt-2" />
      </div>

      <!-- Save Button -->
      <button
        type="submit"
        :disabled="form.processing"
        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700"
      >
        Save
      </button>
    </form>
  </section>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

toastr.options = {
  closeButton: true,
  progressBar: true,
  positionClass: 'toast-top-right',
  timeOut: 5000,
};

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

function updatePassword() {
  form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => {
      toastr.success('Password updated successfully.');
      form.reset();
    },
    onError: (errors) => {
      toastr.error('Please review the errors in the form.');

      Object.entries(errors).forEach(([field, message]) => {
        // Check if message is an array and display each error message
        if (Array.isArray(message)) {
          message.forEach((msg) => toastr.error(msg));
        } else {
          toastr.error(message); // Handle single string message
        }
      });
    },
  });
}
</script>