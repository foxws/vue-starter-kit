<script setup lang="ts">
import AppHeader from '@/components/Ui/AppHeader.vue'
import { useAppearance } from '@/composables/appearance'
import { useFlash } from '@/composables/flash'

const { nonce } = useAppearance()
useFlash()
</script>

<template>
  <UApp :nonce="nonce">
    <div class="flex min-h-dvh flex-col">
      <AppHeader />

      <UMain class="flex-1">
        <UContainer class="py-8">
          <slot />
        </UContainer>
      </UMain>
    </div>
  </UApp>
</template>
