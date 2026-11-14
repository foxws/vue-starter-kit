<script setup lang="ts">
import { useAuth } from '@/composables/auth'
import { home as apiHome } from '@/routes/api'
import { login } from '@/routes'
import { Head } from '@inertiajs/vue3'
import type { ButtonProps } from '@nuxt/ui'
import { computed } from 'vue'

const { user } = useAuth()

const links = computed<ButtonProps[]>(() => [
  user.value
    ? { label: `Signed in as ${user.value.name}`, icon: 'i-lucide-circle-user', color: 'neutral', variant: 'subtle' }
    : { label: 'Log in', icon: 'i-lucide-log-in', to: login.url() },
  {
    label: 'API',
    icon: 'i-lucide-braces',
    color: 'neutral',
    variant: 'outline',
    to: apiHome.url(),
    target: '_blank',
    external: true,
  },
])

const features = [
  {
    title: 'Inertia & Vue',
    description: 'Server-side routing with Inertia v3, Vue and Nuxt UI.',
    icon: 'i-lucide-layers',
  },
  {
    title: 'Authentication',
    description: 'Fortify with passkeys, two-factor and email verification.',
    icon: 'i-lucide-shield-check',
  },
  { title: 'API', description: 'A versioned API under /api/v1, guarded by Sanctum.', icon: 'i-lucide-braces' },
  { title: 'Octane', description: 'Served by FrankenPHP workers that boot once.', icon: 'i-lucide-zap' },
  {
    title: 'Domain-driven',
    description: 'Domain, Modules, Support and Foundation layers in src/.',
    icon: 'i-lucide-boxes',
  },
  {
    title: 'Spatie',
    description: 'Permissions, settings, media, states, tags, CSP and response cache.',
    icon: 'i-lucide-package',
  },
]
</script>

<template>
  <Head title="Home" />

  <div class="flex flex-col gap-12">
    <UPageHero
      title="Vue Starter Kit"
      description="A Laravel starter kit with Inertia, Vue, Fortify and Octane."
      :links="links"
    />

    <UPageGrid>
      <UPageCard
        v-for="feature in features"
        :key="feature.title"
        :title="feature.title"
        :description="feature.description"
        :icon="feature.icon"
        variant="subtle"
      />
    </UPageGrid>
  </div>
</template>
