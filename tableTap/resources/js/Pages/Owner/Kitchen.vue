<template>
    <div class="grid 2xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 mr-6 gap-5 ">
      <Widget maxWidth="full" title="Staff">
      <!-- Staff content goes here -->
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </Widget>
    </div>
</template>
  


<script setup>
import Widget from '@/Widgets/Widget.vue';
import { ref, onMounted  } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';


onMounted(() => {
  const { props } = usePage();
  props.title = 'Kitchen';
});
</script>

