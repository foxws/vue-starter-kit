// Echo's Reverb broadcaster looks Pusher up on `window`.
import Pusher from 'pusher-js'

if (typeof window !== 'undefined') {
  window.Pusher = Pusher
}
