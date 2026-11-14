<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { nextTick, ref } from 'vue'

const props = defineProps<{
  action: string
}>()

const recovery = ref(false)

const form = useForm('post', props.action, {
  code: '',
  recovery_code: '',
})

const toggle = async () => {
  recovery.value = !recovery.value
  form.reset()
  form.clearErrors()

  await nextTick()
}

const onSubmit = () =>
  form.transform((data) => (recovery.value ? { recovery_code: data.recovery_code } : { code: data.code })).submit()
</script>

<template>
  <Head title="Two-factor authentication" />

  <UPageCard
    title="Two-factor authentication"
    :description="
      recovery ? 'Enter one of your emergency recovery codes.' : 'Enter the code from your authenticator application.'
    "
    class="sm:min-w-md"
  >
    <UForm
      :state="form"
      class="flex flex-col gap-4"
      @submit="onSubmit"
    >
      <UFormField
        v-if="!recovery"
        label="Code"
        required
        :error="form.errors.code"
      >
        <UInput
          v-model="form.code"
          inputmode="numeric"
          autocomplete="one-time-code"
          autofocus
          size="lg"
        />
      </UFormField>

      <UFormField
        v-else
        label="Recovery code"
        required
        :error="form.errors.recovery_code"
      >
        <UInput
          v-model="form.recovery_code"
          autocomplete="one-time-code"
          autofocus
          size="lg"
        />
      </UFormField>

      <UButton
        type="submit"
        label="Continue"
        class="justify-center"
        :loading="form.processing"
      />

      <UButton
        :label="recovery ? 'Use an authentication code' : 'Use a recovery code'"
        color="neutral"
        variant="link"
        class="justify-center"
        @click="toggle"
      />
    </UForm>
  </UPageCard>
</template>
