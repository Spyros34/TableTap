<template>
    <section>
      <header>
        <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>
        <p class="mt-1 text-sm text-gray-600">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
      </header>
  
      <form @submit.prevent="deleteAccount" class="mt-6 space-y-6">
        <div>
          <InputLabel for="password" value="Confirm Password" />
          <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
          <InputError :message="form.errors.password" class="mt-2" />
        </div>
  
        <PrimaryButton class="bg-red-600 hover:bg-red-700">Delete Account</PrimaryButton>
      </form>
    </section>
</template>
  
  <script setup>
  import { useForm } from '@inertiajs/vue3';
  import InputLabel from '@/Components/InputLabel.vue';
  import TextInput from '@/Components/TextInput.vue';
  import InputError from '@/Components/InputError.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  
  const form = useForm({
    password: '',
  });
  
  function deleteAccount() {
    form.delete(route('profile.destroy'), {
      onSuccess: () => {
        // Optional: Redirect or provide feedback
      },
    });
  }
  </script>
  