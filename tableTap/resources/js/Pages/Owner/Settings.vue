<template>
    <div>
      <h1 class="text-2xl font-bold">Settings</h1>
      <p>Settings page content</p>
    </div>
</template>


<script setup>
import Widget from '@/Widgets/Widget.vue';
import { ref, onMounted  } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';


onMounted(() => {
  const { props } = usePage();
  props.title = 'Setings';
});
</script>
