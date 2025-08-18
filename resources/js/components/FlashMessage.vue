<template>
  <div v-if="show" :class="alertClasses" class="fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-md">
    <div class="flex items-center">
      <div class="flex-shrink-0">
        <CheckCircleIcon v-if="type === 'success'" class="h-5 w-5" />
        <ExclamationTriangleIcon v-else-if="type === 'error'" class="h-5 w-5" />
        <InformationCircleIcon v-else class="h-5 w-5" />
      </div>
      <div class="ml-3">
        <p class="text-sm font-medium">{{ message }}</p>
      </div>
      <div class="ml-auto pl-3">
        <button @click="close" class="inline-flex text-gray-400 hover:text-gray-600">
          <XMarkIcon class="h-5 w-5" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const show = ref(false)

const props = defineProps({
  type: {
    type: String,
    default: 'info'
  },
  message: {
    type: String,
    default: ''
  },
  duration: {
    type: Number,
    default: 5000
  }
})

const alertClasses = computed(() => {
  const base = 'border'
  switch (props.type) {
    case 'success':
      return `${base} bg-green-50 border-green-200 text-green-800`
    case 'error':
      return `${base} bg-red-50 border-red-200 text-red-800`
    case 'warning':
      return `${base} bg-yellow-50 border-yellow-200 text-yellow-800`
    default:
      return `${base} bg-blue-50 border-blue-200 text-blue-800`
  }
})

const close = () => {
  show.value = false
}

// Auto-close after duration
onMounted(() => {
  if (props.message) {
    show.value = true
    setTimeout(() => {
      show.value = false
    }, props.duration)
  }
})

// Watch for page flash messages
watch(() => page.props.flash, (flash) => {
  if (flash?.success) {
    show.value = true
    setTimeout(() => {
      show.value = false
    }, props.duration)
  }
}, { deep: true })

// Simple icon components
const CheckCircleIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.236 4.53L7.53 10.1a.75.75 0 00-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
  </svg>`
}

const ExclamationTriangleIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
  </svg>`
}

const InformationCircleIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
  </svg>`
}

const XMarkIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
  </svg>`
}
</script>
