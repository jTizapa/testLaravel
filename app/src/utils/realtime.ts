import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

// Opcional: expone una instancia singleton de Echo.
let echo: Echo | null = null

export function useEcho() {
  if (echo) return echo
  echo = new Echo({
    broadcaster: 'pusher',
    key: 'local',
    wsHost: import.meta.env.VITE_APP_WS_URL?.replace('ws://', '').replace('wss://', ''),
    wsPort: 6001,
    wssPort: 6001,
    forceTLS: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    cluster: 'mt',
    plugins: [],
    authorizer: (channel) => ({
      authorize: (socketId: string, callback: Function) => callback(false, {}),
    }),
    client: new Pusher('local', {
      wsHost: import.meta.env.VITE_APP_WS_URL?.replace('ws://', '').replace('wss://', ''),
      wsPort: 6001,
      wssPort: 6001,
      forceTLS: false,
      enableStats: false,
    }),
  })
  return echo
}
