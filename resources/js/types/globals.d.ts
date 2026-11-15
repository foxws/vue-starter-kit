import type { EchoConfig, Features, FlashData, OptionItem, User } from '@/types'
import type Pusher from 'pusher-js'

declare global {
  interface Window {
    Pusher: typeof Pusher
  }
}

declare module '@inertiajs/core' {
  export interface InertiaConfig {
    readonly flashDataType: FlashData
  }

  export interface PageProps {
    readonly app: string
    readonly nonce: string
    readonly locale: string
    readonly locales: OptionItem[] | undefined
    readonly languages: OptionItem[] | undefined
    readonly features: Features | undefined
    readonly auth: User | null | undefined
    readonly echo: EchoConfig | undefined
  }
}
