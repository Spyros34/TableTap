<template>
  <div class="sidebar h-screen bg-gray-800 text-white w-64 fixed">
    <div class="py-4 px-6 bg-gray-900">
      <img class="h-12 w-auto" src="https://via.placeholder.com/150" alt="Logo">
      <h2 class="text-xl font-semibold mt-2">TableTap</h2>
      <p class="text-sm text-gray-500">Version: 1.0.0</p>
    </div>
    <ul class="mt-8">
      <SidebarLink href="/" icon="dashboard" label="Dashboard" />
      <SidebarLink href="/kitchen" icon="kitchen" label="Kitchen" />
      <SidebarLink href="/waiter" icon="person" label="Waiter" />
      <SidebarLink href="/products" icon="store" label="Products" />
      <SidebarLink href="/qrcode" icon="qr_code" label="QR-code" />
      <SidebarLink href="/profile" icon="account_circle" label="Profile" />
      <SidebarLink href="/settings" icon="settings" label="Settings" />
    </ul>
  </div>
</template>

<script setup>
import SidebarLink from '@/Layouts/SidebarLink.vue';
</script>
