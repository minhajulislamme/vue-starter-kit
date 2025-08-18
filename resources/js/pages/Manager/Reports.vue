<template>
  <Head title="Manager Reports" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
      <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
        <div class="p-6 text-foreground">
          <h3 class="text-lg font-semibold mb-4">System Reports</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- User Growth Report -->
            <div class="p-4 border border-sidebar-border/70 rounded-lg">
              <h4 class="font-medium mb-2">User Growth</h4>
              <p class="text-sm text-muted-foreground mb-3">
                Track user registration trends over time.
              </p>
              <div class="text-2xl font-bold text-blue-600 mb-2">+15%</div>
              <p class="text-xs text-muted-foreground">This month</p>
            </div>
            
            <!-- Activity Report -->
            <div class="p-4 border border-sidebar-border/70 rounded-lg">
              <h4 class="font-medium mb-2">User Activity</h4>
              <p class="text-sm text-muted-foreground mb-3">
                Monitor user engagement and activity levels.
              </p>
              <div class="text-2xl font-bold text-green-600 mb-2">87%</div>
              <p class="text-xs text-muted-foreground">Active users</p>
            </div>
            
            <!-- Performance Report -->
            <div class="p-4 border border-sidebar-border/70 rounded-lg">
              <h4 class="font-medium mb-2">System Performance</h4>
              <p class="text-sm text-muted-foreground mb-3">
                System uptime and performance metrics.
              </p>
              <div class="text-2xl font-bold text-purple-600 mb-2">99.9%</div>
              <p class="text-xs text-muted-foreground">Uptime</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Recent Activity -->
      <div class="bg-background overflow-hidden shadow-sm rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
        <div class="p-6 text-foreground">
          <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
          <div class="space-y-3">
            <div class="flex items-center justify-between py-2 border-b border-sidebar-border/70">
              <div>
                <span class="text-sm font-medium">New user registration</span>
                <span class="text-xs text-muted-foreground block">john.doe@example.com</span>
              </div>
              <span class="text-xs text-muted-foreground">2 hours ago</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-sidebar-border/70">
              <div>
                <span class="text-sm font-medium">System backup completed</span>
                <span class="text-xs text-muted-foreground block">Daily backup successful</span>
              </div>
              <span class="text-xs text-muted-foreground">5 hours ago</span>
            </div>
            <div class="flex items-center justify-between py-2">
              <div>
                <span class="text-sm font-medium">User profile updated</span>
                <span class="text-xs text-muted-foreground block">jane.smith@example.com</span>
              </div>
              <span class="text-xs text-muted-foreground">1 day ago</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'

const breadcrumbs = [
  {
    title: 'Manager Dashboard',
    href: '/manager/dashboard',
  },
  {
    title: 'Reports',
    href: '/manager/reports',
  },
]
</script>
