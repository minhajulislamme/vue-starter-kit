<template>
  <Head title="Edit User - Admin" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <!-- Header Section -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">Edit User</h1>
          <p class="text-muted-foreground">Update user information</p>
        </div>
        <Link href="/admin/users" class="px-4 py-2 bg-secondary text-secondary-foreground rounded-lg hover:bg-secondary/90 transition-colors">
          Back to Users
        </Link>
      </div>

      <!-- Edit User Form -->
      <div class="bg-white p-6 rounded-xl border max-w-2xl">
        <form @submit.prevent="submitForm" class="space-y-6">
          <!-- Name Field -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
              Full Name <span class="text-red-500">*</span>
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              :class="{ 'border-red-500': errors.name }"
              placeholder="Enter full name"
            />
            <div v-if="errors.name" class="mt-1 text-sm text-red-600">
              {{ errors.name }}
            </div>
          </div>

          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
              Email Address <span class="text-red-500">*</span>
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              :class="{ 'border-red-500': errors.email }"
              placeholder="Enter email address"
            />
            <div v-if="errors.email" class="mt-1 text-sm text-red-600">
              {{ errors.email }}
            </div>
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
              New Password <span class="text-gray-500">(leave blank to keep current)</span>
            </label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              :class="{ 'border-red-500': errors.password }"
              placeholder="Enter new password (optional)"
            />
            <div v-if="errors.password" class="mt-1 text-sm text-red-600">
              {{ errors.password }}
            </div>
          </div>

          <!-- Confirm Password Field -->
          <div v-if="form.password">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
              Confirm New Password <span class="text-red-500">*</span>
            </label>
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              :class="{ 'border-red-500': errors.password_confirmation }"
              placeholder="Confirm new password"
            />
            <div v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600">
              {{ errors.password_confirmation }}
            </div>
          </div>

          <!-- Role Field -->
          <div>
            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
              User Role <span class="text-red-500">*</span>
            </label>
            <select
              id="role"
              v-model="form.role"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              :class="{ 'border-red-500': errors.role }"
              :disabled="user.id === $page.props.auth.user.id"
            >
              <option value="">Select a role</option>
              <option value="user">User</option>
              <option value="manager">Manager</option>
              <option value="admin">Admin</option>
            </select>
            <div v-if="user.id === $page.props.auth.user.id" class="mt-1 text-sm text-yellow-600">
              You cannot change your own role
            </div>
            <div v-if="errors.role" class="mt-1 text-sm text-red-600">
              {{ errors.role }}
            </div>
          </div>

          <!-- User Info -->
          <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-gray-700 mb-2">User Information</h3>
            <div class="text-sm text-gray-600 space-y-1">
              <p><strong>User ID:</strong> {{ user.id }}</p>
              <p><strong>Created:</strong> {{ formatDate(user.created_at) }}</p>
              <p><strong>Last Updated:</strong> {{ formatDate(user.updated_at) }}</p>
            </div>
          </div>

          <!-- Submit Buttons -->
          <div class="flex gap-4 pt-4">
            <button
              type="submit"
              :disabled="processing"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <span v-if="processing">Updating...</span>
              <span v-else>Update User</span>
            </button>
            
            <Link
              href="/admin/users"
              class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors"
            >
              Cancel
            </Link>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import FlashMessage from '@/components/FlashMessage.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  user: Object,
})

const breadcrumbs = [
  {
    title: 'Admin Dashboard',
    href: '/admin/dashboard',
  },
  {
    title: 'User Management',
    href: '/admin/users',
  },
  {
    title: 'Edit User',
    href: `/admin/users/${props.user.id}/edit`,
  },
]

const { data: form, put, processing, errors } = useForm({
  name: props.user.name,
  email: props.user.email,
  password: '',
  password_confirmation: '',
  role: props.user.role,
})

const submitForm = () => {
  put(`/admin/users/${props.user.id}`)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>
