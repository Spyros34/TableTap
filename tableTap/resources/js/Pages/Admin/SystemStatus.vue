<template>
    <div class="max-w-lg mx-auto mt-10 p-6 bg-white rounded-md shadow-md">
      <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">System Status</h1>
        <!-- Logout Button -->
        <button
          type="button"
          @click="logout"
          class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700"
        >
          Logout
        </button>
      </div>
  
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="flex items-center space-x-2">
            <input
              type="radio"
              v-model="form.enabled"
              :value="true"
              class="form-radio h-5 w-5 text-blue-600"
            />
            <span>Enabled</span>
          </label>
        </div>
        <div>
          <label class="flex items-center space-x-2">
            <input
              type="radio"
              v-model="form.enabled"
              :value="false"
              class="form-radio h-5 w-5 text-blue-600"
            />
            <span>Disabled (Maintenance Mode)</span>
          </label>
        </div>
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700"
        >
          Update Status
        </button>
      </form>
  
      <div v-if="flash" class="mt-4 text-green-600 font-semibold">
        {{ flash }}
      </div>
    </div>
</template>
  
<script setup>
  import { useForm, router } from '@inertiajs/vue3'
  
  // Props from controller
  const props = defineProps({
    enabled: Boolean,
    flash: String,
  })
  
  // Create the Inertia form with the current status
  const form = useForm({
    enabled: props.enabled,
  })
  
  // Submit form to update system status
  function submit() {
    form.post(route('admin.system_status.update'), {
      onError: (errors) => {
        console.log('Validation failed:', errors)
      },
      onSuccess: () => {
        console.log('Status updated successfully')
      },
    })
  }
  
  // Logout function
  function logout() {
    router.post(route('admin.logout'))
  }
  </script>