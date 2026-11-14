<script setup lang="ts">
import { useAppearance } from '@/composables/appearance'

defineProps<{
  location?: string
}>()

const { app } = useAppearance()
</script>

<template>
  <ULink
    class="flex items-center gap-2.5 text-lg font-bold tracking-tight text-highlighted"
    :to="location || '/'"
  >
    <span
      class="grid size-7 shrink-0 place-items-center rounded-[9px] bg-(image:--logo-gradient) shadow-[inset_0_1px_0_rgb(255_255_255/0.35)]"
      aria-hidden="true"
    >
      <UIcon
        name="i-lucide-box"
        class="size-4 text-white"
      />
    </span>
    <span class="inline">{{ app }}</span>
  </ULink>
</template>
