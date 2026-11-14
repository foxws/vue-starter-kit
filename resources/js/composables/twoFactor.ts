import { confirm, disable, enable, qrCode, recoveryCodes, regenerateRecoveryCodes } from '@/routes/two-factor'
import { router, useHttp } from '@inertiajs/vue3'
import { readonly, ref } from 'vue'

export function useTwoFactor() {
  const qrSvg = ref<string | null>(null)
  const codes = ref<string[]>([])

  const http = useHttp({ code: '' })
  const fetcher = useHttp<object, unknown>({})

  const reloadUser = () => router.reload({ only: ['auth'] })

  const fetchQrCode = async () => {
    const response = (await fetcher.get(qrCode.url())) as { svg: string }

    qrSvg.value = response.svg
  }

  const fetchRecoveryCodes = async () => {
    codes.value = (await fetcher.get(recoveryCodes.url())) as string[]
  }

  const start = async () => {
    await fetcher.post(enable.url())
    await fetchQrCode()
  }

  const confirmSetup = () =>
    http.post(confirm.url(), {
      onSuccess: async () => {
        qrSvg.value = null
        http.reset()
        await fetchRecoveryCodes()
        reloadUser()
      },
    })

  const regenerate = async () => {
    await fetcher.post(regenerateRecoveryCodes.url())
    await fetchRecoveryCodes()
  }

  const turnOff = async () => {
    await fetcher.delete(disable.url())
    qrSvg.value = null
    codes.value = []
    reloadUser()
  }

  return {
    http,
    qrSvg: readonly(qrSvg),
    codes: readonly(codes),
    start,
    confirmSetup,
    fetchRecoveryCodes,
    regenerate,
    turnOff,
  }
}
