import api from './api'

export interface Subscription {
  id: number
  member_id: number
  plan_id: number
  status: string
  start_date: string
  end_date: string
  amount: number
  member?: { id: number; name: string; email: string }
  plan?: { id: number; name: string }
}

export async function fetchSubscriptions(page = 1) {
  const { data } = await api.get(`/subscriptions?page=${page}`)
  return data
}
