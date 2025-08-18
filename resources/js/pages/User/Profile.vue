<template>
  <Head title="User Profile" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
      <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
        <div class="p-6 text-foreground">
          <h3 class="text-lg font-semibold mb-4">Profile Information</h3>
          
          <form @submit.prevent="updateProfile">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="name" class="block text-sm font-medium text-foreground mb-2">
                  Name
                </label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  class="w-full px-3 py-2 border border-sidebar-border/70 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-background text-foreground"
                  required
                />
                <div v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</div>
              </div>
              
              <div>
                <label for="email" class="block text-sm font-medium text-foreground mb-2">
                  Email
                </label>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  class="w-full px-3 py-2 border border-sidebar-border/70 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-background text-foreground"
                  required
                />
                <div v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</div>
              </div>
            </div>
            
            <div class="mt-6">
              <label class="block text-sm font-medium text-foreground mb-2">
                Role
              </label>
              <span class="px-3 py-2 text-sm bg-muted/50 text-muted-foreground rounded-md">
                {{ user.role.charAt(0).toUpperCase() + user.role.slice(1) }}
              </span>
              <p class="mt-1 text-xs text-muted-foreground">Your role cannot be changed.</p>
            </div>
            
            <div class="mt-6 flex items-center justify-between">
              <button
                type="submit"
                :disabled="processing"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
              >
                <span v-if="processing">Updating...</span>
                <span v-else>Update Profile</span>
              </button>
              
              <Link :href="route('user.dashboard')" class="text-sm text-muted-foreground hover:text-foreground">
                Back to Dashboard
              </Link>
            </div>
          </form>
        </div>
      </div>
      
      <!-- Account Information -->
      <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
        <div class="p-6 text-foreground">
          <h3 class="text-lg font-semibold mb-4">Account Information</h3>
          <div class="space-y-4">
            <div class="flex justify-between py-2 border-b border-sidebar-border/70">
              <span class="text-sm font-medium">Account Created:</span>
              <span class="text-sm">{{ new Date(user.created_at).toLocaleDateString() }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-sidebar-border/70">
              <span class="text-sm font-medium">Email Verified:</span>
              <span class="text-sm">
                <span v-if="user.email_verified_at" class="text-green-600">✓ Verified</span>
                <span v-else class="text-red-600">✗ Not Verified</span>
              </span>
            </div>
            <div class="flex justify-between py-2">
              <span class="text-sm font-medium">Last Updated:</span>
              <span class="text-sm">{{ new Date(user.updated_at).toLocaleDateString() }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
  user: Object,
  errors: Object,
})

const breadcrumbs = [
  {
    title: 'User Dashboard',
    href: '/user/dashboard',
  },
  {
    title: 'Profile',
    href: '/user/profile',
  },
]

const { data: form, put, processing } = useForm({
  name: props.user.name,
  email: props.user.email,
})

const updateProfile = () => {
  put(route('user.profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
      // Handle success
    },
  })
}
</script>
