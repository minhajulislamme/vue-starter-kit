<template>
  <Head title="Admin Users" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
      <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
        <div class="p-6 text-foreground">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Manage Users</h3>
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
                    Current Role
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                    Joined
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-background divide-y divide-border">
                <tr v-for="user in users.data" :key="user.id">
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
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <select 
                      :value="user.role" 
                      @change="updateRole(user.id, $event.target.value)"
                      class="text-sm border border-sidebar-border/70 rounded px-2 py-1 bg-background"
                    >
                      <option value="user">User</option>
                      <option value="manager">Manager</option>
                      <option value="admin">Admin</option>
                    </select>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          <div v-if="users.links" class="mt-4">
            <div class="flex justify-between items-center">
              <div class="text-sm text-muted-foreground">
                Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results
              </div>
              <div class="flex space-x-2">
                <Link 
                  v-for="link in users.links" 
                  :key="link.label"
                  :href="link.url"
                  v-html="link.label"
                  :class="[
                    'px-3 py-2 text-sm border rounded',
                    link.active 
                      ? 'bg-blue-500 text-white border-blue-500' 
                      : 'bg-background text-foreground border-sidebar-border/70 hover:bg-muted/50'
                  ]"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, Head, router } from '@inertiajs/vue3'

defineProps({
  users: Object,
})

const breadcrumbs = [
  {
    title: 'Admin Dashboard',
    href: '/admin/dashboard',
  },
  {
    title: 'Users',
    href: '/admin/users',
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

const updateRole = (userId, newRole) => {
  router.put(route('admin.users.updateRole', userId), {
    role: newRole
  }, {
    preserveScroll: true,
    onSuccess: () => {
      // Handle success
    },
    onError: () => {
      // Handle error
    }
  })
}
</script>
