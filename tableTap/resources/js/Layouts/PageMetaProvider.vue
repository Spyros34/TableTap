<!-- resources/js/Layouts/PageMetaProvider.vue -->
<template>
    <component :is="layout" :title="title">
      <slot />
    </component>
</template>
  
  <script setup>
  import { usePage } from '@inertiajs/vue3';
  import MainLayout from './MainLayout.vue';
  
  const page = usePage();
  
  const layout = page.props.layout || MainLayout;
  const title = page.props.title || 'Default Title';
  </script>
  