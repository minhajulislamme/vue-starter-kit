<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ProductsTable } from '@/components/products';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Product {
  id: number;
  name: string;
  sku: string;
  category: string;
  price: number;
  stock: number;
  status: 'active' | 'inactive' | 'draft';
  image?: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/admin/products',
    },
];

// Sample products data
const products = ref<Product[]>([
  {
    id: 1,
    name: 'MacBook Pro 16"',
    sku: 'MBP16-001',
    category: 'Electronics',
    price: 2499.99,
    stock: 15,
    status: 'active',
  },
  {
    id: 2,
    name: 'iPhone 15 Pro',
    sku: 'IP15P-001',
    category: 'Electronics',
    price: 999.99,
    stock: 5,
    status: 'active',
  },
  {
    id: 3,
    name: 'AirPods Pro',
    sku: 'APP-001',
    category: 'Electronics',
    price: 249.99,
    stock: 0,
    status: 'inactive',
  }
]);

const handleEditProduct = (product: Product) => {
  console.log('Edit product:', product);
  // Handle edit product logic
};

const handleViewProduct = (product: Product) => {
  console.log('View product:', product);
  // Handle view product logic
};

const handleDeleteProduct = (product: Product) => {
  if (confirm(`Are you sure you want to delete ${product.name}?`)) {
    const index = products.value.findIndex((p: Product) => p.id === product.id);
    if (index > -1) {
      products.value.splice(index, 1);
    }
  }
};
</script>

<template>
    <Head title="Products" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <ProductsTable 
              :products="products"
              @edit-product="handleEditProduct"
              @view-product="handleViewProduct"
              @delete-product="handleDeleteProduct"
            />
        </div>
    </AppLayout>
</template>
