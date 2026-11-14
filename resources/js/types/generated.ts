export type CursorPaginatedDataCollection<TKey, TValue> = CursorPaginator<TKey, TValue>
export type CursorPaginator<TKey, TValue> = {
  data: TKey extends string ? Record<TKey, TValue> : TValue[]
  links: {
    url: string | null
    label: string
    active: boolean
  }[]
  meta: {
    path: string
    per_page: number
    next_cursor: string | null
    next_page_url: string | null
    prev_cursor: string | null
    prev_page_url: string | null
  }
}
export type CursorPaginatorInterface<TKey, TValue> = CursorPaginator<TKey, TValue>
export type Language = 'en' | 'nl'
export type LengthAwarePaginator<TKey, TValue> = {
  data: TKey extends string ? Record<TKey, TValue> : TValue[]
  links: {
    url: string | null
    label: string
    active: boolean
  }[]
  meta: {
    total: number
    current_page: number
    first_page_url: string
    from: number | null
    last_page: number
    last_page_url: string
    next_page_url: string | null
    path: string
    per_page: number
    prev_page_url: string | null
    to: number | null
  }
}
export type LengthAwarePaginatorInterface<TKey, TValue> = LengthAwarePaginator<TKey, TValue>
export type Locale = 'en-US' | 'nl-NL'
export type PaginatedDataCollection<TKey, TValue> = LengthAwarePaginator<TKey, TValue>
export type StateData = {
  name: string
  label: string
  icon: string
  color: string
}
export type UserData = {
  id: string
  name: string | null
  email: string
  avatar: string | null
  state: StateData
  two_factor_enabled: boolean
  roles?: string[]
  permissions?: string[]
  email_verified_at: string | null
  created_at: string | null
  updated_at: string | null
}
