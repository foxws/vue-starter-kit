<script setup lang="ts">
import PasswordConfirmModal from '@/components/Passkeys/PasswordConfirmModal.vue'
import { useAuth } from '@/composables/auth'
import { usePasskeys } from '@/composables/passkeys'
import { useTwoFactor } from '@/composables/twoFactor'
import { computed, ref } from 'vue'

const { user } = useAuth()
const { isPasswordConfirmed } = usePasskeys()
const { http, qrSvg, codes, start, confirmSetup, fetchRecoveryCodes, regenerate, turnOff } = useTwoFactor()

const enabled = computed(() => user.value?.two_factor_enabled ?? false)
const confirmOpen = ref(false)

let pendingAction: (() => Promise<void> | void) | null = null

const withConfirmedPassword = async (action: () => Promise<void> | void) => {
  if (await isPasswordConfirmed()) {
    await action()

    return
  }

  pendingAction = action
  confirmOpen.value = true
}

const onConfirmed = async () => {
  await pendingAction?.()
  pendingAction = null
}
</script>

<template>
  <div>
    <UPageCard
      title="Two-factor authentication"
      description="Add an extra layer of security with a code from an authenticator app."
      variant="naked"
      orientation="vertical"
      :ui="{ body: 'flex w-full flex-col gap-3' }"
    >
      <template #body>
        <template v-if="qrSvg">
          <p class="text-sm text-muted">Scan this QR code with your authenticator app, then enter the code it shows.</p>

          <div
            class="w-fit rounded-md bg-white p-2"
            v-html="qrSvg"
          />

          <UFormField
            label="Code"
            :error="http.errors.code"
          >
            <UInput
              v-model="http.code"
              inputmode="numeric"
              autocomplete="one-time-code"
              class="w-56"
            />
          </UFormField>

          <UButton
            label="Confirm"
            class="self-start"
            :loading="http.processing"
            @click="confirmSetup"
          />
        </template>

        <template v-else-if="enabled">
          <UBadge
            label="Enabled"
            color="success"
            variant="subtle"
            class="self-start"
          />

          <ul
            v-if="codes.length"
            class="grid grid-cols-2 gap-1 rounded-md bg-elevated p-3 font-mono text-sm"
          >
            <li
              v-for="code in codes"
              :key="code"
            >
              {{ code }}
            </li>
          </ul>

          <div class="flex flex-wrap gap-2">
            <UButton
              :label="codes.length ? 'Regenerate recovery codes' : 'Show recovery codes'"
              color="neutral"
              variant="soft"
              @click="withConfirmedPassword(codes.length ? regenerate : fetchRecoveryCodes)"
            />

            <UButton
              label="Disable"
              color="error"
              variant="soft"
              @click="withConfirmedPassword(turnOff)"
            />
          </div>
        </template>

        <UButton
          v-else
          label="Enable two-factor"
          icon="i-lucide-shield-check"
          color="neutral"
          variant="soft"
          class="self-start"
          @click="withConfirmedPassword(start)"
        />
      </template>
    </UPageCard>

    <PasswordConfirmModal
      v-model:open="confirmOpen"
      @confirmed="onConfirmed"
    />
  </div>
</template>
