<template>
  <div class="sm:max-md:hidden sidebar h-screen bg-gray text-gray2 w-64 shadow fixed">
    <div class=" py-4 px-6 bg-gray-900 border-b border-gray3 shadow-bottom">
      <img class=" h-12 w-auto" src="https://via.placeholder.com/150" alt="Logo">
      <h2 class="text-xl font-semibold mt-2">TableTap</h2>
      <p class="text-sm text-gray-500">Version: 1.0.0</p>
    </div>
    <ul class="mt-8">
      <SidebarLink href="/" icon="dashboard" label="Dashboard" iconColor="text-gray-800" />
      <SidebarLink href="/kitchen" icon="kitchen" label="Kitchen" iconColor="text-gray-800" />
      <SidebarLink href="/waiter" icon="person" label="Waiter" iconColor="text-gray-800" />
      <SidebarLink href="/products" icon="store" label="Products" iconColor="text-gray-800" />
      <SidebarLink href="/qrcode" icon="qr_code" label="QR-code" iconColor="text-gray-800" />
      <SidebarLink href="/profile" icon="account_circle" label="Profile" iconColor="text-gray-800" />
      <SidebarLink href="/settings" icon="settings"label="Settings" iconColor="text-gray-800" />
    </ul>
  </div>


  <div class="md:hidden fixed z-50 w-full h-16 max-w-lg -translate-x-1/2 bg-white border border-gray2 rounded-full bottom-4 left-1/2">
    <div class="grid h-full max-w-lg grid-cols-7 mx-auto">
      <Link href="/" class="inline-flex flex-col items-center justify-center px-5 rounded-s-full hover:bg-slate dark:hover:bg-gray group">
        <span class="material-icons text-gray2 dark:text-gray3 group-hover:text-blue-600 dark:group-hover:text-blue-500">dashboard</span>
        <span class="sr-only">Dashboard</span>
      </Link>
      <Link href="/kitchen" class="inline-flex flex-col items-center justify-center px-5 hover:bg-slate dark:hover:bg-gray group">
        <span class="material-icons text-gray2 dark:text-gray group-hover:text-blue-600 dark:group-hover:text-blue-500">kitchen</span>
        <span class="sr-only">Kitchen</span>
      </Link>
      <Link href="/waiter" class="inline-flex flex-col items-center justify-center px-5 hover:bg-slate dark:hover:bg-slate group">
        <span class="material-icons text-gray2 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">person</span>
        <span class="sr-only">Waiter</span>
      </Link>
      <Link href="/products" class="inline-flex flex-col items-center justify-center px-5 hover:bg-slate dark:hover:bg-slate group">
        <span class="material-icons text-gray2 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">store</span>
        <span class="sr-only">Products</span>
      </Link>
      <Link href="/qrcode" class="inline-flex flex-col items-center justify-center px-5 hover:bg-slate dark:hover:bg-slate group">
        <span class="material-icons text-gray2 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">qr_code</span>
        <span class="sr-only">QR-code</span>
      </Link>
      <Link href="/profile" class="inline-flex flex-col items-center justify-center px-5 hover:bg-slate dark:hover:bg-gray-800 group">
        <span class="material-icons text-gray2 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">account_circle</span>
        <span class="sr-only">Profile</span>
      </Link>
      <Link href="/settings" class="inline-flex flex-col items-center justify-center px-5 rounded-e-full hover:bg-slate dark:hover:bg-gray-800 group">
        <span class="material-icons text-gray2 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">settings</span>
        <span class="sr-only">Settings</span>
      </Link>
    </div>
  </div>
</template>

<script setup>
import SidebarLink from '@/Layouts/SidebarLink.vue';
import { Link } from "@inertiajs/vue3";
</script>
