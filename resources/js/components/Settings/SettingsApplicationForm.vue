<script setup lang="ts">
import { show, update } from '@/actions/Modules/Web/Settings/Controllers/ApplicationSettingsController'
import type { ApplicationSettings } from '@/types'
import { useForm, useHttp, usePage } from '@inertiajs/vue3'
import { computed, onMounted, ref } from 'vue'

const loaded = ref(false)
const locales = computed(() => usePage().props.locales ?? [])

const http = useHttp<object, ApplicationSettings>({})

const form = useForm<ApplicationSettings>(update(), {
  site_name: '',
  timezone: 'UTC',
  default_locale: 'en-US',
  allow_registration: false,
})

const onSubmit = () =>
  form.submit({
    preserveScroll: true,
    preserveState: true,
  })

onMounted(async () => {
  const settings = await http.get(show.url())

  form.defaults(settings)
  form.reset()
  loaded.value = true
})

defineExpose({
  submit: onSubmit,
  get processing() {
    return form.processing
  },
  get recentlySuccessful() {
    return form.recentlySuccessful
  },
})

const fieldClass = 'flex max-sm:flex-col justify-between items-start gap-4'
</script>

<template>
  <UForm
    :state="form"
    loading-auto
    @submit="onSubmit"
  >
    <UPageCard
      title="Application"
      description="Settings stored with spatie/laravel-settings, shared by every user."
      variant="naked"
      orientation="vertical"
      :ui="{ body: 'flex w-full flex-col gap-3' }"
    >
      <template #body>
        <USkeleton
          v-if="!loaded"
          class="h-40 w-full rounded-md"
        />

        <template v-else>
          <UFormField
            label="Site name"
            :error="form.errors.site_name"
            :class="fieldClass"
          >
            <UInput
              v-model="form.site_name"
              class="w-56"
            />
          </UFormField>

          <USeparator />

          <UFormField
            label="Timezone"
            :error="form.errors.timezone"
            :class="fieldClass"
          >
            <UInput
              v-model="form.timezone"
              class="w-56"
            />
          </UFormField>

          <USeparator />

          <UFormField
            label="Default locale"
            :error="form.errors.default_locale"
            :class="fieldClass"
          >
            <USelect
              v-model="form.default_locale"
              :items="locales"
              class="w-56"
            />
          </UFormField>

          <USeparator />

          <UFormField
            label="Allow registration"
            description="Let visitors create their own account."
            :error="form.errors.allow_registration"
            :class="fieldClass"
          >
            <USwitch v-model="form.allow_registration" />
          </UFormField>
        </template>
      </template>
    </UPageCard>
  </UForm>
</template>
