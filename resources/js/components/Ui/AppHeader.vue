<script setup lang="ts">
import AppLogo from '@/components/Ui/AppLogo.vue'
import UserMenu from '@/components/Ui/UserMenu.vue'
import { useAuth } from '@/composables/auth'
import { login, register } from '@/routes'
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const { user } = useAuth()

const canRegister = computed(() => usePage().props.features?.registration ?? false)
</script>

<template>
  <UHeader :toggle="false">
    <template #left>
      <AppLogo />
    </template>

    <template #right>
      <UserMenu v-if="user" />

      <div
        v-else
        class="flex items-center gap-2"
      >
        <UButton
          label="Log in"
          color="neutral"
          variant="ghost"
          :to="login.url()"
        />

        <UButton
          v-if="canRegister"
          label="Register"
          color="primary"
          :to="register.url()"
        />
      </div>
    </template>
  </UHeader>
</template>
