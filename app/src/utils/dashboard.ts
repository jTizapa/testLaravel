import api from './api'

export interface DashboardStats {
  total_members: number
  active_subscriptions: number
  payments_today: number
  revenue_today: number
}

export async function fetchDashboardStats() {
  const { data } = await api.get('/dashboard')
  return data as DashboardStats
}
