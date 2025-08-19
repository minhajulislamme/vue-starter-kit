<template>
  <th 
    class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider"
    :class="className"
  >
    <slot />
  </th>
</template>

<script setup lang="ts">
interface Props {
  className?: string;
}

defineProps<Props>();
</script>
