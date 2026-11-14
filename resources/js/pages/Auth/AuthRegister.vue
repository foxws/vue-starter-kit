<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps<{
  action: string
  loginUrl: string
}>()

const form = useForm('post', props.action, {
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const onSubmit = () =>
  form.submit({
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
</script>

<template>
  <Head title="Register" />

  <UPageCard
    title="Create an account"
    description="Enter your details to get started."
    class="sm:min-w-md"
  >
    <UForm
      :state="form"
      class="flex flex-col gap-4"
      @submit="onSubmit"
    >
      <UFormField
        label="Name"
        required
        :error="form.errors.name"
      >
        <UInput
          v-model="form.name"
          name="name"
          :model-modifiers="{ trim: true }"
          required
          autofocus
          autocomplete="name"
          size="lg"
        />
      </UFormField>

      <UFormField
        label="Email"
        required
        :error="form.errors.email"
      >
        <UInput
          v-model="form.email"
          name="email"
          :model-modifiers="{ trim: true }"
          type="email"
          required
          autocomplete="email"
          placeholder="email@example.com"
          size="lg"
        />
      </UFormField>

      <UFormField
        label="Password"
        required
        :error="form.errors.password"
      >
        <UInput
          v-model="form.password"
          name="password"
          type="password"
          required
          autocomplete="new-password"
          size="lg"
        />
      </UFormField>

      <UFormField
        label="Confirm password"
        required
        :error="form.errors.password_confirmation"
      >
        <UInput
          v-model="form.password_confirmation"
          name="password_confirmation"
          type="password"
          required
          autocomplete="new-password"
          size="lg"
        />
      </UFormField>

      <UButton
        type="submit"
        label="Create account"
        class="justify-center"
        :loading="form.processing"
      />
    </UForm>

    <template #footer>
      <p class="text-center text-sm text-muted">
        Already have an account?
        <ULink :to="loginUrl">Log in</ULink>
      </p>
    </template>
  </UPageCard>
</template>
