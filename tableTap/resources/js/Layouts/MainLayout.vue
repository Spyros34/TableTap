<template>
  <div class="flex">
    <Sidebar />
    <div class="flex-1 md:ml-64 bg-gray-100 min-h-screen">
      <!-- Header -->
      <header class="bg-white shadow p-4 py-10 flex justify-between items-center">
        <h1 class="text-2xl font-semibold">{{ title }}</h1>
        <div class="hidden sm:flex sm:items-center sm:ml-6">
          <!-- Settings Dropdown -->
          <div class="ml-3 relative">
            <Dropdown align="right" width="48">
              <template #trigger>
                <span class="inline-flex rounded-md">
                  <button
                    type="button"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                    @click="toggleDropdown"
                  >
                    {{ authUser.name }}
                    <svg
                      class="ml-2 -mr-0.5 h-4 w-4"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </button>
                </span>
              </template>

              <template #content>
                <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
              </template>
            </Dropdown>
          </div>
        </div>
      </header>
      <!-- Main Content -->
      <main class="mt-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { defineProps } from 'vue';
import Sidebar from './Sidebar.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
});

const { props: pageProps } = usePage();
const authUser = pageProps.auth.user;

const showingDropdown = ref(false);

function toggleDropdown() {
  showingDropdown.value = !showingDropdown.value;
}
</script>