<!-- FlashMessage.vue -->
<template>
  <div v-if="message" :class="messageClass" class="p-4 rounded-lg my-4">
    <p>{{ message }}</p>
  </div>
</template>

<script setup>
const props = defineProps({
  type: {
    type: String,
    default: 'success', // 'success' or 'error'
  },
  message: {
    type: String,
    required: true,
  },
});

// Dynamically set the class based on the type
const messageClass = computed(() => {
  return props.type === 'success'
    ? 'bg-green-100 text-green-800 border border-green-200'
    : 'bg-red-100 text-red-800 border border-red-200';
});
</script>
