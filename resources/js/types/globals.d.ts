import type { Features, FlashData, OptionItem, User } from '@/types'

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
  }
}
