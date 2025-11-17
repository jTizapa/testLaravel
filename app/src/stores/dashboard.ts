import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { DashboardStats } from '@/utils/dashboard'
import { fetchDashboardStats } from '@/utils/dashboard'

export interface DashboardEvent {
  type: 'payment.recorded' | 'subscription.status_changed'
  payload: Record<string, unknown>
  at: string
}

export const useDashboardStore = defineStore('dashboard', () => {
  const stats = ref<DashboardStats | null>(null)
  const events = ref<DashboardEvent[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  async function loadStats() {
    loading.value = true
    error.value = null
    try {
      stats.value = await fetchDashboardStats()
    } catch (e) {
      error.value = 'No se pudieron cargar las métricas'
      throw e
    } finally {
      loading.value = false
    }
  }

  function pushEvent(type: DashboardEvent['type'], payload: Record<string, unknown>) {
    events.value.unshift({
      type,
      payload,
      at: new Date().toISOString(),
    })
    if (events.value.length > 50) events.value.pop()
  }

  return { stats, events, loading, error, loadStats, pushEvent }
})
