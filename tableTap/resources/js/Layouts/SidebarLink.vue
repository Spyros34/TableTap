<template>
  <li class="py-2 px-4 hover:bg-gray-700">
    <Link :href="href" class="flex items-center">
      <span class="material-icons mr-2">{{ icon }}</span>
      {{ label }}
    </Link>
  </li>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  href: String,
  icon: String,
  label: String,
});
</script>
