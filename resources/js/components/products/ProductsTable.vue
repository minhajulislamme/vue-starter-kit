<template>
  <div class="space-y-4">
    <!-- Table Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-lg font-semibold">Products</h2>
        <p class="text-sm text-muted-foreground">A list of all products in your store.</p>
      </div>
      <Link :href="route('admin.products.create')" class="flex items-center gap-2">
        <Button class="flex items-center gap-2">
          <Plus class="h-4 w-4" />
          Add Product
        </Button>
      </Link>
    </div>

    <!-- Search and Filters -->
    <div class="flex flex-col gap-4">
      <!-- Search -->
      <div class="w-full">
        <div class="relative">
          <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground h-4 w-4" />
          <input
            v-model="searchTerm"
            type="text"
            placeholder="Search products..."
            class="w-full pl-10 pr-4 py-2 border border-input bg-background rounded-md text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
          />
        </div>
      </div>

      <!-- Filters - Mobile Responsive -->
      <div class="flex flex-col sm:flex-row gap-2 w-full">
        <!-- Category Filter -->
        <select
          v-model="selectedCategory"
          class="flex-1 px-3 py-2 border border-input bg-background rounded-md text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
        >
          <option value="">All Categories</option>
          <option v-for="category in availableCategories" :key="category" :value="category">
            {{ category }}
          </option>
        </select>

        <!-- Status Filter -->
        <select
          v-model="selectedStatus"
          class="flex-1 px-3 py-2 border border-input bg-background rounded-md text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
        >
          <option value="">All Status</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
          <option value="draft">Draft</option>
        </select>

        <!-- Stock Filter -->
        <select
          v-model="selectedStockStatus"
          class="flex-1 px-3 py-2 border border-input bg-background rounded-md text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
        >
          <option value="">All Stock</option>
          <option value="in-stock">In Stock</option>
          <option value="low-stock">Low Stock</option>
          <option value="out-of-stock">Out of Stock</option>
        </select>

        <!-- Reset Filters -->
        <Button 
          v-if="hasActiveFilters" 
          variant="outline" 
          size="sm" 
          @click="resetFilters"
          class="flex items-center justify-center gap-2 sm:w-auto w-full"
        >
          <X class="h-4 w-4" />
          Reset
        </Button>
      </div>
    </div>

    <!-- Results Info -->
    <div v-if="hasActiveFilters" class="text-sm text-muted-foreground">
      Showing {{ filteredProducts.length }} of {{ products.length }} products
    </div>

    <!-- Table - Desktop View -->
    <div class="hidden md:block rounded-lg border">
      <Table>
        <TableHead>
          <tr>
            <TableHeader>Image</TableHeader>
            <TableHeader>Name</TableHeader>
            <TableHeader>Category</TableHeader>
            <TableHeader>Price</TableHeader>
            <TableHeader>Stock</TableHeader>
            <TableHeader>Status</TableHeader>
            <TableHeader class="text-right">Actions</TableHeader>
          </tr>
        </TableHead>
        <TableBody>
          <TableRow v-for="product in filteredProducts" :key="product.id">
            <TableCell>
              <div class="h-10 w-10 overflow-hidden rounded-lg bg-muted">
                <img 
                  v-if="product.image" 
                  :src="product.image" 
                  :alt="product.name"
                  class="h-full w-full object-cover"
                />
                <div v-else class="flex h-full w-full items-center justify-center">
                  <Package class="h-4 w-4 text-muted-foreground" />
                </div>
              </div>
            </TableCell>
            <TableCell class="font-medium">
              <div>
                <div class="font-medium">{{ product.name }}</div>
                <div class="text-xs text-muted-foreground">SKU: {{ product.sku }}</div>
              </div>
            </TableCell>
            <TableCell>
              <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                {{ product.category }}
              </span>
            </TableCell>
            <TableCell class="font-medium">
              ${{ formatPrice(product.price) }}
            </TableCell>
            <TableCell>
              <div class="flex items-center gap-2">
                <span class="text-sm">{{ product.stock }}</span>
                <span 
                  class="inline-flex items-center rounded-full px-1.5 py-0.5 text-xs font-medium"
                  :class="getStockStatusClass(product.stock)"
                >
                  {{ getStockStatus(product.stock) }}
                </span>
              </div>
            </TableCell>
            <TableCell>
              <span 
                class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium"
                :class="getStatusClass(product.status)"
              >
                {{ product.status }}
              </span>
            </TableCell>
            <TableCell class="text-right">
              <div class="flex items-center justify-end gap-2">
                <Button variant="ghost" size="sm" @click="$emit('edit-product', product)">
                  <Edit class="h-4 w-4" />
                </Button>
                <Button variant="ghost" size="sm" @click="$emit('view-product', product)">
                  <Eye class="h-4 w-4" />
                </Button>
                <Button variant="ghost" size="sm" @click="$emit('delete-product', product)">
                  <Trash2 class="h-4 w-4" />
                </Button>
              </div>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4">
      <div 
        v-for="product in filteredProducts" 
        :key="product.id"
        class="bg-background border rounded-lg p-4 space-y-3"
      >
        <!-- Product Header -->
        <div class="flex items-start gap-3">
          <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-lg bg-muted">
            <img 
              v-if="product.image" 
              :src="product.image" 
              :alt="product.name"
              class="h-full w-full object-cover"
            />
            <div v-else class="flex h-full w-full items-center justify-center">
              <Package class="h-5 w-5 text-muted-foreground" />
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-medium text-foreground truncate">{{ product.name }}</h3>
            <p class="text-xs text-muted-foreground">SKU: {{ product.sku }}</p>
            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20 mt-1">
              {{ product.category }}
            </span>
          </div>
        </div>

        <!-- Product Details -->
        <div class="grid grid-cols-2 gap-4 pt-2 border-t border-border">
          <div>
            <p class="text-xs text-muted-foreground">Price</p>
            <p class="font-medium">${{ formatPrice(product.price) }}</p>
          </div>
          <div>
            <p class="text-xs text-muted-foreground">Stock</p>
            <div class="flex items-center gap-2">
              <span class="text-sm font-medium">{{ product.stock }}</span>
              <span 
                class="inline-flex items-center rounded-full px-1.5 py-0.5 text-xs font-medium"
                :class="getStockStatusClass(product.stock)"
              >
                {{ getStockStatus(product.stock) }}
              </span>
            </div>
          </div>
        </div>

        <!-- Status and Actions -->
        <div class="flex items-center justify-between pt-2 border-t border-border">
          <span 
            class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium"
            :class="getStatusClass(product.status)"
          >
            {{ product.status }}
          </span>
          <div class="flex items-center gap-2">
            <Button variant="ghost" size="sm" @click="$emit('edit-product', product)">
              <Edit class="h-4 w-4" />
            </Button>
            <Button variant="ghost" size="sm" @click="$emit('view-product', product)">
              <Eye class="h-4 w-4" />
            </Button>
            <Button variant="ghost" size="sm" @click="$emit('delete-product', product)">
              <Trash2 class="h-4 w-4" />
            </Button>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!filteredProducts || filteredProducts.length === 0" class="text-center py-12">
      <Package class="h-12 w-12 text-muted-foreground mx-auto mb-4" />
      <h3 class="text-lg font-medium text-foreground mb-2">
        {{ hasActiveFilters ? 'No products match your filters' : 'No products found' }}
      </h3>
      <p class="text-muted-foreground mb-4">
        {{ hasActiveFilters ? 'Try adjusting your search criteria or filters.' : 'Get started by creating your first product.' }}
      </p>
      <div class="flex gap-2 justify-center">
        <Button v-if="hasActiveFilters" variant="outline" @click="resetFilters">
          Clear Filters
        </Button>
        <Link :href="route('admin.products.create')">
          <Button>
            <Plus class="h-4 w-4 mr-2" />
            Add Product
          </Button>
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableHead, TableHeader, TableBody, TableRow, TableCell } from '@/components/ui/table';
import { Plus, Package, Edit, Eye, Trash2, Search, X } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { ref, computed } from 'vue';

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

interface Props {
  products: Product[];
}

const props = defineProps<Props>();

defineEmits<{
  'edit-product': [product: Product];
  'view-product': [product: Product];
  'delete-product': [product: Product];
}>();

// Filter states
const searchTerm = ref('');
const selectedCategory = ref('');
const selectedStatus = ref('');
const selectedStockStatus = ref('');

// Get unique categories from products
const availableCategories = computed(() => {
  const categories = new Set(props.products.map((product: Product) => product.category));
  return Array.from(categories).sort();
});

// Check if any filters are active
const hasActiveFilters = computed(() => {
  return searchTerm.value !== '' || 
         selectedCategory.value !== '' || 
         selectedStatus.value !== '' || 
         selectedStockStatus.value !== '';
});

// Filter products based on search and filters
const filteredProducts = computed(() => {
  let filtered = [...props.products];

  // Search filter
  if (searchTerm.value) {
    const search = searchTerm.value.toLowerCase();
    filtered = filtered.filter(product => 
      product.name.toLowerCase().includes(search) ||
      product.sku.toLowerCase().includes(search) ||
      product.category.toLowerCase().includes(search)
    );
  }

  // Category filter
  if (selectedCategory.value) {
    filtered = filtered.filter(product => product.category === selectedCategory.value);
  }

  // Status filter
  if (selectedStatus.value) {
    filtered = filtered.filter(product => product.status === selectedStatus.value);
  }

  // Stock status filter
  if (selectedStockStatus.value) {
    filtered = filtered.filter(product => {
      const stockStatus = getStockStatusValue(product.stock);
      return stockStatus === selectedStockStatus.value;
    });
  }

  return filtered;
});

// Reset all filters
const resetFilters = () => {
  searchTerm.value = '';
  selectedCategory.value = '';
  selectedStatus.value = '';
  selectedStockStatus.value = '';
};

const formatPrice = (price: number) => {
  return price.toFixed(2);
};

const getStockStatus = (stock: number) => {
  if (stock === 0) return 'Out of Stock';
  if (stock < 10) return 'Low Stock';
  return 'In Stock';
};

const getStockStatusValue = (stock: number) => {
  if (stock === 0) return 'out-of-stock';
  if (stock < 10) return 'low-stock';
  return 'in-stock';
};

const getStockStatusClass = (stock: number) => {
  if (stock === 0) return 'bg-red-100 text-red-800';
  if (stock < 10) return 'bg-yellow-100 text-yellow-800';
  return 'bg-green-100 text-green-800';
};

const getStatusClass = (status: string) => {
  switch (status) {
    case 'active':
      return 'bg-green-100 text-green-800';
    case 'inactive':
      return 'bg-red-100 text-red-800';
    case 'draft':
      return 'bg-gray-100 text-gray-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
};
</script>
