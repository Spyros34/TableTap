<template>
    <div>Show</div>
    <Link href="/">Main Page</Link>&nbsp;
    <Link href="/hello">Show Page</Link>
    <div> The page with time {{ timer }}</div>
    <slot>Default</slot>
</template>
  
  
<script setup>
import { ref } from 'vue'
import { Link } from "@inertiajs/vue3";
const timer = ref(0);

// Corrected setInterval syntax
setInterval(() => {
    timer.value++;
}, 1000);
</script>

