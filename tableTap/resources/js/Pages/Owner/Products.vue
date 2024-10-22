<template>
  <MainLayout :title="title">
    <div>
      <h1 class="text-2xl font-bold">Products</h1>
      <p>Products page content</p>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const title = ref('Products');

onMounted(() => {
  const { props } = usePage();
  props.title = title.value;
});
</script>