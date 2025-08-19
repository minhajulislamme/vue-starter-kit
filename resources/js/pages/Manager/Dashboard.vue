<template>
  <Head title="Manager Dashboard" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
        <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <div class="p-6 text-foreground">
            <div class="flex items-center">
              <div class="text-sm font-medium text-muted-foreground">Total Users</div>
            </div>
            <div class="mt-2 text-3xl font-bold text-blue-600">{{ stats.total_users }}</div>
          </div>
        </div>
        
        <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <div class="p-6 text-foreground">
            <div class="flex items-center">
              <div class="text-sm font-medium text-muted-foreground">Recent Users</div>
            </div>
            <div class="mt-2 text-3xl font-bold text-green-600">{{ stats.recent_users.length }}</div>
          </div>
        </div>

        <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <div class="p-6 text-foreground">
            <div class="flex items-center">
              <div class="text-sm font-medium text-muted-foreground">Quick Actions</div>
            </div>
            <div class="mt-4 space-y-2">
              <Link :href="route('manager.users')" class="block text-sm text-blue-600 hover:text-blue-500">
                Manage Users
              </Link>
              <Link :href="route('manager.reports')" class="block text-sm text-blue-600 hover:text-blue-500">
                View Reports
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Users -->
      <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border mb-8">
        <div class="p-6 text-foreground">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Recent Users</h3>
            <Link :href="route('manager.users')" class="text-blue-600 hover:text-blue-500">
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
                    Joined
                  </th>
                </tr>
              </thead>
              <tbody class="bg-background divide-y divide-border">
                <tr v-for="user in stats.recent_users" :key="user.id">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-foreground">
                    {{ user.name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                    {{ user.email }}
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

      <!-- Quick Stats -->
      <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
        <div class="p-6 text-foreground">
          <h3 class="text-lg font-semibold mb-4">Manager Tools</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 border border-sidebar-border/70 rounded-lg">
              <h4 class="font-medium mb-2">User Management</h4>
              <p class="text-sm text-muted-foreground mb-3">
                View and manage all users in the system.
              </p>
              <Link :href="route('manager.users')" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                Manage Users
              </Link>
            </div>
            
            <div class="p-4 border border-sidebar-border/70 rounded-lg">
              <h4 class="font-medium mb-2">Reports</h4>
              <p class="text-sm text-muted-foreground mb-3">
                Generate and view system reports.
              </p>
              <Link :href="route('manager.reports')" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700">
                View Reports
              </Link>
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
  users: Array,
  stats: Object,
})

const breadcrumbs = [
  {
    title: 'Manager Dashboard',
    href: '/manager/dashboard',
  },
]
</script>
