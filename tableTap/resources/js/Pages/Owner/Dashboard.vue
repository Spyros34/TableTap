<template>
  <MainLayout :title="title">
    <div class="grid 2xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 mr-6 gap-5">

      <!-- Shop Count Widget -->
      <Widget maxWidth="full" title="Shops Managed">
        <p class="text-xl font-semibold">{{ dashboardData.shopCount }}</p>
      </Widget>

      <!-- Staff Count Widget -->
      <Widget maxWidth="full" title="Staff">
        <p class="text-xl font-semibold">{{ dashboardData.staffCount }}</p>
      </Widget>

      <!-- Pending Orders Widget -->
      <Widget maxWidth="full" title="Pending Orders">
        <p class="text-xl font-semibold">{{ dashboardData.pendingOrdersCount }}</p>
      </Widget>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import Widget from '@/Widgets/Widget.vue';
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const title = ref('Dashboard');
const { props } = usePage();
const dashboardData = computed(() => props.dashboardData || {
  shopCount: 0,
  staffCount: 0,
  pendingOrdersCount: 0,
});
</script>