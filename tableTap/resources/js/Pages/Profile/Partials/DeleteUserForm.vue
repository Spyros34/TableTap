<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>
      <p class="mt-1 text-sm text-gray-600">
        Once your account is deleted, all of its resources and data will be permanently deleted.
      </p>
    </header>

    <form @submit.prevent="confirmDeleteAccount" class="mt-6 space-y-6">
      <div>
        <InputLabel for="password" value="Confirm Password" />
        <TextInput
          id="password"
          v-model="form.password"
          type="password"
          class="mt-1 block w-full"
          autocomplete="current-password"
        />
        <InputError :message="form.errors.password" class="mt-2" />
      </div>

      <PrimaryButton class="bg-red-600 hover:bg-red-700 text-white">Delete Account</PrimaryButton>
    </form>
  </section>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

toastr.options = {
closeButton: true,
progressBar: true,
positionClass: 'toast-top-right',
timeOut: 5000,
};

const form = useForm({
password: '',
});

// Confirm delete action
function confirmDeleteAccount() {
if (confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
  deleteAccount();
}
}

// Delete account function
function deleteAccount() {
form.delete(route('profile.destroy'), {
  onSuccess: () => {
    toastr.success('Account and associated shop deleted successfully.');
    form.reset();
    window.location.href = '/login'; // Redirect to login page
  },
  onError: (errors) => {
    toastr.error('Please check the errors and try again.');
    Object.entries(errors).forEach(([field, message]) => {
      if (Array.isArray(message)) {
        message.forEach((msg) => toastr.error(msg));
      } else {
        toastr.error(message);
      }
    });
  },
});
}
</script>