<template>
  <MainLayout :title="title">
    <h1 class="text-2xl font-bold mb-6">Profile</h1>
    <div class="space-y-6">
      <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
      <UpdatePasswordForm />
      <DeleteUserForm />
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const title = ref('Profile');
const { mustVerifyEmail, status } = usePage().props.value;
</script>
