<template>
  <li class="py-2 px-4 hover:bg-slate">
    <Link :href="href" class="flex items-center">
      <span class="material-icons mr-2" :class="iconColor">{{ icon }}</span>
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
  iconColor: {
    type: String,
    default: 'text-white', // Default color is black
  },
});
</script>
