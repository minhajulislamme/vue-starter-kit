<template>
  <tr 
    class="hover:bg-muted/50 transition-colors"
    :class="className"
  >
    <slot />
  </tr>
</template>

<script setup lang="ts">
interface Props {
  className?: string;
}

defineProps<Props>();
</script>
