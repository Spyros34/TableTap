<template>
  <AuthLayout title="Select User Type">
    <div class="flex flex-col items-center">
      <div class="flex space-x-4 mb-4">
        <Link href="/login/owner" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Owner</Link>
        <Link href="/login/kitchen" class="bg-green-600 text-white px-4 py-2 rounded-lg">Kitchen</Link>
        <Link href="/login/waiter" class="bg-red-600 text-white px-4 py-2 rounded-lg">Waiter</Link>
      </div>
    </div>
  </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Link } from '@inertiajs/vue3';
</script>