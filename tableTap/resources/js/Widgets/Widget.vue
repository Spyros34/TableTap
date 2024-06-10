<template>
  <div :class="[sizeClasses, maxWidthClasses]" class="bg-white p-4 rounded-lg shadow mx-3 my-1 ">
    <h3 class="text-lg font-semibold mb-2 break-words">{{ title }}</h3>
    <div class="break-words">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  size: {
    type: String,
    default: 'medium',
  },
  title: {
    type: String,
    required: true,
  },
  maxWidth: {
    type: String,
    default: 'full', // can be 'full', 'half', or 'third'
  },
});

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'large':
      return 'w-full';
    case 'small':
      return 'w-full';
    default:
      return 'w-full';
  }
});

const maxWidthClasses = computed(() => {
  switch (props.maxWidth) {
    case 'half':
      return 'max-w-1/2';
    case 'third':
      return 'max-w-1/3';
    default:
      return 'max-w-full';
  }
});
</script>
