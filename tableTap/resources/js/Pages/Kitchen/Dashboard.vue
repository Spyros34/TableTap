<template>
  <div class="container mx-auto px-4 py-8 space-y-6">
    <!-- Header Section -->
    <h2 class="text-xl font-semibold mb-4 text-center sm:text-left flex justify-between items-center">
      <span>Kitchen Dashboard</span>
      <button
        class="bg-gray2 hover:bg-gray-900 text-white px-4 py-2 rounded-3xl text-sm"
        @click="logout"
      >
        Logout
      </button>
    </h2>

    <!-- Products Section -->
    <div>
      <h2 class="text-xl font-semibold mb-4 text-center sm:text-left">Products</h2>
      <div v-if="localProducts.length === 0" class="text-center text-gray-500">
        No products available.
      </div>
      <div v-else class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-6">
        <div
          v-for="product in localProducts"
          :key="product.id"
          class="bg-white p-4 rounded-lg shadow flex flex-col space-y-4"
        >
          <div>
            <h3 class="text-lg font-semibold mb-2">{{ product.name }}</h3>
            <p class="text-sm text-gray-600 mb-1">Price: ${{ product.price }}</p>
          </div>
          <div class="flex flex-col space-y-3">
            <!-- Quantity Input -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2">
              <label class="text-sm font-medium sm:mr-2">Quantity:</label>
              <input
                type="number"
                class="border border-gray-300 rounded px-2 py-1 w-full sm:w-24"
                v-model="product.quantity"
                @change="updateProduct(product.id, { quantity: product.quantity })"
              />
            </div>
            <!-- Availability Dropdown -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2">
              <label class="text-sm font-medium sm:mr-2">Availability:</label>
              <select
                class="border border-gray-300 rounded px-2 py-1 w-full sm:w-32"
                v-model="product.availability"
                @change="updateProduct(product.id, { availability: product.availability })"
              >
                <option value="1">Available</option>
                <option value="0">Out of Stock</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Orders Section -->
    <div>
      <h2 class="text-xl font-semibold mb-4 text-center sm:text-left">Orders</h2>
      <div v-if="localOrders.length === 0" class="text-center text-gray-500">
        No orders available.
      </div>
      <div v-else class="grid lg:grid-cols-2 md:grid-cols-1 gap-6">
        <div
          v-for="order in localOrders"
          :key="order.id"
          class="bg-white p-4 rounded-lg shadow"
        >
          <div class="mb-4">
            <p class="text-sm font-medium">Order ID: {{ order.id }}</p>
            <p class="text-sm">Customer: {{ order.customer?.name || 'N/A' }}</p>
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
                - Qty: {{ item.amount }}
              </li>
            </ul>
          </div>
          <div class="flex justify-center mt-4">
            <button
              class="px-4 py-2 rounded text-sm w-full max-w-xs"
              :class="{
                'bg-green-500 hover:bg-green-600 text-white': order.status !== 'ready',
                'bg-gray-300 text-gray-500 cursor-not-allowed': order.status === 'ready',
              }"
              :disabled="order.status === 'ready'"
              @click="markOrderAsReady(order)"
            >
              {{ order.status === 'ready' ? 'Marked as Ready' : 'Mark as Ready' }}
            </button>
          </div>
        </div>
      </div>

      <div class="flex justify-center mt-6">
        <button
          class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm max-w-xs w-full"
          @click="clearReadyOrders"
        >
          Clear Ready Orders
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

// We rename the props to avoid referencing them directly
const props = defineProps({
  orders: {
    type: Array,
    default: () => [],
  },
  products: {
    type: Array,
    default: () => [],
  }
})

// Create local reactive copies so we can mutate them (e.g. mark as ready)
const localOrders  = ref([...props.orders])
const localProducts = ref([...props.products])


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


/**
 * Mark an order as "ready" locally and persist via the backend
 */
const markOrderAsReady = (order) => {
  if (!order || order.status === 'ready') return
  
  // Locally mark it "ready" so the UI updates
  order.status = 'ready'
  
  // Post the update to the backend
  router.post(`/kitchen/orders/${order.id}/ready`, {}, {
    preserveScroll: true,
    onSuccess: () => {
      console.log(`Order ${order.id} marked as ready.`)
    },
    onError: (error) => {
      console.error(`Failed to mark order ${order.id} as ready:`, error)
      // Revert local state if there's an error
      order.status = 'pending'
    }
  })
}

/**
 * Clear "ready" orders by reloading the page data
 * The `index` method in your controller only returns "pending" orders
 */
const clearReadyOrders = () => {
  router.reload({
    preserveScroll: true,
    onSuccess: (page) => {
      // The controller returns only "pending" orders, so we replace the local array
      localOrders.value = page.props.orders
      console.log('Reloaded to show only pending orders.')
    },
    onError: (error) => {
      console.error('Failed to reload orders:', error)
    }
  })
}

/**
 * Update a product's quantity/availability
 */
const updateProduct = (id, data) => {
  router.post(`/kitchen/products/${id}/update`, data, {
    preserveScroll: true,
    onSuccess: () => {
      console.log(`Product ${id} updated successfully.`)
    },
    onError: (error) => {
      console.error(`Failed to update product ${id}:`, error)
    }
  })
}

/**
 * Compute dynamic classes for the status
 */
const statusClass = (status) => {
  switch (status) {
    case 'pending':
      return 'text-yellow-500'
    case 'ready':
      return 'text-green-500'
    default:
      return 'text-gray-500'
  }
}
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
.text-gray-500 {
  color: #6b7280;
}
</style>