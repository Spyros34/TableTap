<template>
    <section>
      <header>
        <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
        <p class="mt-1 text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
      </header>
  
      <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
        <div>
          <InputLabel for="current_password" value="Current Password" />
          <TextInput id="current_password" v-model="form.current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
          <InputError :message="form.errors.current_password" class="mt-2" />
        </div>
  
        <div>
          <InputLabel for="password" value="New Password" />
          <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
          <InputError :message="form.errors.password" class="mt-2" />
        </div>
  
        <div>
          <InputLabel for="password_confirmation" value="Confirm Password" />
          <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
          <InputError :message="form.errors.password_confirmation" class="mt-2" />
        </div>
  
        <!-- Save Button -->
        <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
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
current_password: '',
password: '',
password_confirmation: '',
});

function updatePassword() {
form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
});
}
</script>
  