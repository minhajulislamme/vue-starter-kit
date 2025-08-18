<template>
  <Head title="Admin Dashboard" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <div class="p-6 text-foreground">
            <div class="flex items-center">
              <div class="text-sm font-medium text-muted-foreground">Total Users</div>
            </div>
            <div class="mt-2 text-3xl font-bold">{{ stats.total_users }}</div>
          </div>
        </div>
        
        <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <div class="p-6 text-foreground">
            <div class="flex items-center">
              <div class="text-sm font-medium text-muted-foreground">Admins</div>
            </div>
            <div class="mt-2 text-3xl font-bold text-red-600">{{ stats.admins }}</div>
          </div>
        </div>
        
        <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <div class="p-6 text-foreground">
            <div class="flex items-center">
              <div class="text-sm font-medium text-muted-foreground">Managers</div>
            </div>
            <div class="mt-2 text-3xl font-bold text-yellow-600">{{ stats.managers }}</div>
          </div>
        </div>
        
        <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <div class="p-6 text-foreground">
            <div class="flex items-center">
              <div class="text-sm font-medium text-muted-foreground">Users</div>
            </div>
            <div class="mt-2 text-3xl font-bold text-green-600">{{ stats.users }}</div>
          </div>
        </div>
      </div>

      <!-- Recent Users Table -->
      <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
        <div class="p-6 text-foreground">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">All Users</h3>
            <Link :href="route('admin.users')" class="text-blue-600 hover:text-blue-500">
              View All
            </Link>
          </div>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
              <thead class="bg-muted/50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                    Name
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                    Email
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                    Role
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                    Joined
                  </th>
                </tr>
              </thead>
              <tbody class="bg-background divide-y divide-border">
                <tr v-for="user in users.slice(0, 10)" :key="user.id">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-foreground">
                    {{ user.name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                    {{ user.email }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getRoleClass(user.role)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ user.role.charAt(0).toUpperCase() + user.role.slice(1) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                    {{ new Date(user.created_at).toLocaleDateString() }}
                  </td>
                </tr>
              </tbody>
            </table>
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
  users: Array,
  stats: Object,
})

const breadcrumbs = [
  {
    title: 'Admin Dashboard',
    href: '/admin/dashboard',
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
