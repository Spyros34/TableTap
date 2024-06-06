<template>
  <div class="flex flex-wrap justify-center gap-6">
    <Widget size="small" maxWidth="half" title="Staff">
      <!-- Staff content goes here -->
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </Widget>
    <Widget size="small" maxWidth="half" title="Assigned Action Items">
      <!-- Assigned Action Items content goes here -->
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis vehicula orci a urna volutpat, et interdum mi luctus.</p>
    </Widget>
    <Widget size="small" maxWidth="half" title="Notifications">
      <!-- Notifications content goes here -->
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </Widget>
  </div>
</template>

<script setup>
import Widget from '@/Widgets/Widget.vue';
</script>
