import type { AvatarProps, BadgeProps, SelectItem } from '@nuxt/ui'

export type FlashType = 'success' | 'error' | 'warning' | 'info' | 'primary'

export type FlashData = {
  readonly title?: string
  readonly description?: string
  readonly type?: FlashType
}

export type Features = {
  readonly registration: boolean
  readonly resetPasswords: boolean
  readonly twoFactor: boolean
  readonly passkeys: boolean
}

export type OptionItem = SelectItem & {
  label: string
  value: string | number | boolean | null
  disabled?: boolean
}

export type Model = {
  id: string
  created_at: string
  updated_at: string
}

export type ModelState = {
  name: string
  label: string
  icon: string
  color: BadgeProps['color']
}

export type User = Model & {
  name: string
  email: string
  avatar?: AvatarProps['src'] | null
  roles?: string[] | null
  permissions?: string[] | null
  state?: ModelState
  two_factor_enabled: boolean
  email_verified_at?: string | null
}

export type ApplicationSettings = {
  site_name: string
  timezone: string
  default_locale: 'en-US' | 'nl-NL'
  allow_registration: boolean
}

export type Passkey = {
  id: number
  name: string
  authenticator: string | null
  last_used_at: string | null
  created_at: string
}
