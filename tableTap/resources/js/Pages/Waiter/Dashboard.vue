<template>
    <div class="container mx-auto px-4 py-8 space-y-6">
      <h2 class="text-xl font-semibold mb-4 text-center sm:text-left flex justify-between items-center">
        <span>Waiter Dashboard</span>
        <button
        class="bg-gray2 hover:bg-gray-900 text-white px-4 py-2 rounded-3xl text-sm"
        @click="logout"
      >
        Logout
      </button>
      </h2>
  
      <!-- Display message if no orders -->
      <div v-if="ordersLocal.length === 0" class="text-center text-gray-500">
        No orders yet.
      </div>
  
      <!-- Display orders if they exist -->
      <div v-else class="grid lg:grid-cols-2 md:grid-cols-1 gap-6">
        <div
          v-for="order in ordersLocal"
          :key="order.id"
          class="bg-white p-4 rounded-lg shadow"
        >
          <div class="mb-4">
            <p class="text-sm font-medium">Order ID: {{ order.id }}</p>
            <p class="text-sm">Customer: {{ order.customer?.name || 'N/A' }}</p>
            <p class="text-sm">
              Table:
              {{
                order.customer?.table && order.customer.table.length
                  ? order.customer.table[0].table_num
                  : 'N/A'
              }}
            </p>
            <p :class="statusClass(order.status)" class="text-sm font-semibold">
              Status: {{ order.status }}
            </p>
          </div>
          <div>
            <h3 class="text-sm font-medium mb-2">Order Items:</h3>
            <ul class="space-y-2">
              <li
                v-for="item in order.order_items"
                :key="item.id"
                class="text-sm"
              >
                <strong>{{ item.product?.name || 'Unknown Product' }}</strong>
                - Qty: {{ item.amount }} x ${{ item.product?.price }}
              </li>
            </ul>
          </div>
          <div class="flex justify-center mt-4">
            <button
              class="px-4 py-2 rounded text-sm w-full max-w-xs"
              :class="{
                'bg-blue-500 hover:bg-blue-600 text-white': order.status === 'ready',
                'bg-gray-300 text-gray-500 cursor-not-allowed': order.status !== 'ready',
              }"
              :disabled="order.status !== 'ready'"
              @click="markOrderAsCompleted(order.id)"
            >
              {{ order.status === 'ready' ? 'Mark as Completed' : 'Waiting...' }}
            </button>
          </div>
        </div>
      </div>
  
      <!-- "Clear Completed" button -->
      <div class="flex justify-center mt-6" v-if="hasCompletedOrders">
        <button
          class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm"
          @click="clearCompletedOrders"
        >
          Clear Completed Orders
        </button>
      </div>
    </div>
  </template>
  
<script setup>
  import { ref, computed } from 'vue'
  import { router } from '@inertiajs/vue3'
  
  // Props from the controller
  const props = defineProps({
    orders: {
      type: Array,
      default: () => []
    }
  });
  
  // Local reactive copy
  const ordersLocal = ref([...props.orders]);


    const logout = () => {
    router.post('/logout', {}, {
        onSuccess: () => {
        console.log('Logged out successfully.');
        },
        onError: (err) => {
        console.error('Failed to logout:', err);
        }
    })
    };

  
  // Mark a ready order as completed
  const markOrderAsCompleted = (orderId) => {
    router.post(`/waiter/orders/${orderId}/completed`, {}, {
      preserveScroll: true,
      onSuccess: () => {
        console.log(`Order ${orderId} marked as completed.`);
        // Update local state
        const order = ordersLocal.value.find(o => o.id === orderId);
        if (order) {
          order.status = 'completed';
        }
      },
      onError: (err) => {
        console.error('Failed to mark order as completed:', err);
      }
    })
  };
  
  // Clear completed orders locally
  const clearCompletedOrders = () => {
    ordersLocal.value = ordersLocal.value.filter(o => o.status !== 'completed');
    console.log('Completed orders cleared locally.');
  };
  
  // Show the "Clear Completed Orders" button only if any are completed
  const hasCompletedOrders = computed(() =>
    ordersLocal.value.some(o => o.status === 'completed')
  );
  
  // Dynamically assign classes by status
  const statusClass = (status) => {
    switch (status) {
      case 'pending':
        return 'text-yellow-500';
      case 'ready':
        return 'text-green-500';
      case 'completed':
        return 'text-blue-500';
      default:
        return 'text-gray-500';
    }
  };
  </script>
  
  <style scoped>
  button {
    transition: background-color 0.2s ease-in-out;
  }
  .text-yellow-500 {
    color: #fbbf24;
  }
  .text-green-500 {
    color: #10b981;
  }
  .text-blue-500 {
    color: #3b82f6;
  }
  .text-gray-500 {
    color: #6b7280;
  }
  </style>