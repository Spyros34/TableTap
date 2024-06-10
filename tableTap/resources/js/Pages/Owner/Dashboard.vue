<template>
  <div class="grid 2xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 mr-6 gap-5 ">
    <Widget size="small" maxWidth="full" title="Staff">
      <!-- Staff content goes here -->
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </Widget>
    <Widget size="small" maxWidth="full" title="Assigned Action Items">
      <!-- Assigned Action Items content goes here -->
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis vehicula orci a urna volutpat, et interdum mi luctus.</p>
    </Widget>
    <Widget size="small" maxWidth="full" title="Notifications">
      <!-- Notifications content goes here -->
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </Widget>
    <Widget size="small" maxWidth="full" title="Notifications">
      <!-- Notifications content goes here -->
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </Widget>
  </div>
</template>

<script setup>
import Widget from '@/Widgets/Widget.vue';
</script>
