<template>
  <td 
    class="px-6 py-4 text-sm text-foreground"
    :class="className"
  >
    <slot />
  </td>
</template>

<script setup lang="ts">
interface Props {
  className?: string;
}

defineProps<Props>();
</script>
