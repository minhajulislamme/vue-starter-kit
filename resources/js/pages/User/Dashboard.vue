<template>
  <Head title="User Dashboard" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
      <!-- Welcome Section -->
      <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border mb-8">
        <div class="p-6 text-foreground">
          <h3 class="text-2xl font-bold mb-2">Welcome back, {{ profile.name }}!</h3>
          <p class="text-muted-foreground">
            You're logged in as a {{ profile.role }}. Here's your dashboard overview.
          </p>
        </div>
      </div>

      <!-- Profile Info Cards -->
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
        <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <div class="p-6 text-foreground">
            <div class="flex items-center">
              <div class="text-sm font-medium text-muted-foreground">Profile</div>
            </div>
            <div class="mt-2">
              <div class="text-lg font-bold">{{ profile.name }}</div>
              <div class="text-sm text-muted-foreground">{{ profile.email }}</div>
            </div>
          </div>
        </div>
        
        <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <div class="p-6 text-foreground">
            <div class="flex items-center">
              <div class="text-sm font-medium text-muted-foreground">Role</div>
            </div>
            <div class="mt-2">
              <span :class="getRoleClass(profile.role)" class="px-3 py-1 text-sm font-semibold rounded-full">
                {{ profile.role.charAt(0).toUpperCase() + profile.role.slice(1) }}
              </span>
            </div>
          </div>
        </div>
        
        <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <div class="p-6 text-foreground">
            <div class="flex items-center">
              <div class="text-sm font-medium text-muted-foreground">Member Since</div>
            </div>
            <div class="mt-2 text-lg font-bold">{{ profile.joined }}</div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border mb-8">
        <div class="p-6 text-foreground">
          <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 border border-sidebar-border/70 rounded-lg">
              <h4 class="font-medium mb-2">Profile Settings</h4>
              <p class="text-sm text-muted-foreground mb-3">
                Update your personal information and preferences.
              </p>
              <Link :href="route('user.profile')" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                Edit Profile
              </Link>
            </div>
            
            <div class="p-4 border border-sidebar-border/70 rounded-lg">
              <h4 class="font-medium mb-2">Account Security</h4>
              <p class="text-sm text-muted-foreground mb-3">
                Manage your password and security settings.
              </p>
              <Link :href="route('password.request')" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-gray-600 rounded hover:bg-gray-700">
                Security Settings
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
        <div class="p-6 text-foreground">
          <h3 class="text-lg font-semibold mb-4">Account Information</h3>
          <div class="space-y-4">
            <div class="flex justify-between py-2 border-b border-sidebar-border/70">
              <span class="text-sm font-medium">Name:</span>
              <span class="text-sm">{{ user.name }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-sidebar-border/70">
              <span class="text-sm font-medium">Email:</span>
              <span class="text-sm">{{ user.email }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-sidebar-border/70">
              <span class="text-sm font-medium">Role:</span>
              <span class="text-sm">{{ user.role }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-sidebar-border/70">
              <span class="text-sm font-medium">Email Verified:</span>
              <span class="text-sm">
                <span v-if="user.email_verified_at" class="text-green-600">✓ Verified</span>
                <span v-else class="text-red-600">✗ Not Verified</span>
              </span>
            </div>
            <div class="flex justify-between py-2">
              <span class="text-sm font-medium">Account Created:</span>
              <span class="text-sm">{{ new Date(user.created_at).toLocaleDateString() }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, Head } from '@inertiajs/vue3'

defineProps({
  user: Object,
  profile: Object,
})

const breadcrumbs = [
  {
    title: 'User Dashboard',
    href: '/user/dashboard',
  },
]

const getRoleClass = (role) => {
  switch (role) {
    case 'admin':
      return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
    case 'manager':
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
    case 'user':
      return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
    default:
      return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
  }
}
</script>
