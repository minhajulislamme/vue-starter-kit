<template>
  <Head title="User Management - Admin" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <!-- Flash Messages -->
    <FlashMessage 
      v-if="$page.props.flash?.success" 
      type="success" 
      :message="$page.props.flash.success" 
    />
    <FlashMessage 
      v-if="$page.props.flash?.error" 
      type="error" 
      :message="$page.props.flash.error" 
    />
    
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <!-- Header Section -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">User Management</h1>
          <p class="text-muted-foreground">Manage all users and their roles</p>
        </div>
        <Link :href="route('admin.users.create')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
          Add New User
        </Link>
      </div>

      <!-- Simple Users List -->
      <div class="bg-white p-6 rounded-xl border">
        <h3 class="text-lg font-semibold mb-4">Users List</h3>
        
        <div v-if="users?.data && users.data.length > 0">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    User
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Email
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Role
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Joined
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-medium">
                          {{ user.name.charAt(0).toUpperCase() }}
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                        <div class="text-sm text-gray-500">ID: {{ user.id }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ user.email }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span 
                      class="px-2 py-1 text-xs font-medium rounded-full"
                      :class="getRoleClass(user.role)"
                    >
                      {{ user.role.charAt(0).toUpperCase() + user.role.slice(1) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(user.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex gap-2">
                      <Link 
                        :href="route('admin.users.edit', user.id)"
                        class="text-blue-600 hover:text-blue-900 px-2 py-1 rounded transition-colors"
                      >
                        Edit
                      </Link>
                      <button 
                        @click="deleteUser(user)"
                        class="text-red-600 hover:text-red-900 px-2 py-1 rounded transition-colors"
                        :disabled="user.id === $page.props.auth.user.id"
                      >
                        Delete
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        
        <div v-else class="text-center py-8 text-gray-500">
          No users found or data not loaded properly
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import FlashMessage from '@/components/FlashMessage.vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { onMounted } from 'vue'

const props = defineProps({
  users: Object,
  filters: Object,
})

const page = usePage()

const breadcrumbs = [
  {
    title: 'Admin Dashboard',
    href: '/admin/dashboard',
  },
  {
    title: 'User Management',
    href: '/admin/users',
  },
]

const getRoleClass = (role) => {
  switch (role) {
    case 'admin':
      return 'bg-red-100 text-red-800'
    case 'manager':
      return 'bg-yellow-100 text-yellow-800'
    case 'user':
      return 'bg-green-100 text-green-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const deleteUser = (user) => {
  if (confirm(`Are you sure you want to delete ${user.name}? This action cannot be undone.`)) {
    router.delete(route('admin.users.delete', user.id), {
      preserveScroll: true,
      onSuccess: () => {
        console.log('User deleted successfully')
      },
      onError: (errors) => {
        console.error('Failed to delete user:', errors)
        alert('Failed to delete user. Please try again.')
      }
    })
  }
}

// Debug and error handling
onMounted(() => {
  console.log('Admin Users component mounted')
  console.log('Users prop:', props.users)
  console.log('Filters prop:', props.filters)
  
  if (!props.users) {
    console.error('Users data is missing!')
  } else if (!props.users.data) {
    console.error('Users data array is missing!')
  } else {
    console.log('Users data loaded successfully:', props.users.data.length, 'users')
  }
})
</script>
