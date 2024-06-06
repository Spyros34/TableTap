<template>
    <div :class="widgetClass">
      <slot></slot>
    </div>
</template>
  
  <script setup>
  const props = defineProps({
    size: {
      type: String,
      default: 'large'  // default size
    }
  });
  
  const widgetClass = computed(() => {
    switch (props.size) {
      case 'small':
        return 'p-4 bg-white shadow rounded w-1/3';
      case 'medium':
        return 'p-4 bg-white shadow rounded w-2/3';
      case 'large':
        return 'p-4 bg-white shadow rounded w-full';
      default:
        return 'p-4 bg-white shadow rounded w-full';
    }
  });
  </script>
  