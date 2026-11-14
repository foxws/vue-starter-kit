<script setup lang="ts">
import SettingsModal from '@/components/Settings/SettingsModal.vue'
import { useAuth } from '@/composables/auth'
import { health } from '@/routes'
import { index as horizon } from '@/routes/horizon'
import type { DropdownMenuItem } from '@nuxt/ui'
import { computed, ref } from 'vue'

const { user, hasRole, logOut } = useAuth()

const isSettingsOpen = ref(false)
const settingsSection = ref('account')

const openSettings = (section: string) => {
  settingsSection.value = section
  isSettingsOpen.value = true
}

const items = computed<DropdownMenuItem[][]>(() => [
  [
    {
      label: user.value?.name ?? user.value?.email,
      type: 'label',
    },
  ],
  [
    {
      label: 'Settings',
      icon: 'i-lucide-settings',
      onClick: () => openSettings('account'),
    },
    ...(hasRole('super-admin')
      ? [
          {
            label: 'Application',
            icon: 'i-lucide-shield-half',
            onClick: () => openSettings('application'),
          },
          {
            label: 'Queue',
            icon: 'i-lucide-layers',
            to: horizon.url(),
            target: '_blank',
            external: true,
          },
          {
            label: 'Health',
            icon: 'i-lucide-heart-pulse',
            to: health.url(),
            target: '_blank',
            external: true,
          },
        ]
      : []),
  ],
  [
    {
      label: 'Log out',
      icon: 'i-lucide-log-out',
      onClick: logOut,
    },
  ],
])
</script>

<template>
  <div>
    <UDropdownMenu
      :items="items"
      :content="{ align: 'end', collisionPadding: 12 }"
    >
      <UAvatar
        :src="user?.avatar ?? undefined"
        :alt="user?.name ?? 'User'"
        class="cursor-pointer"
        size="sm"
      />
    </UDropdownMenu>

    <SettingsModal
      v-model:open="isSettingsOpen"
      v-model:section="settingsSection"
    />
  </div>
</template>
