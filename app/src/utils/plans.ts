import api from './api'

export interface Plan {
  id: number
  name: string
  duration_days: number
  price: number
  active: boolean
}

export interface PlanPayload {
  name: string
  duration_days: number
  price: number
  active?: boolean
}

export async function fetchPlans(page = 1) {
  const { data } = await api.get(`/plans?page=${page}`)
  return data
}

export async function createPlan(payload: PlanPayload) {
  const { data } = await api.post('/plans', payload)
  return data as Plan
}

export async function updatePlan(id: number, payload: Partial<PlanPayload>) {
  const { data } = await api.put(`/plans/${id}`, payload)
  return data as Plan
}

export async function deletePlan(id: number) {
  await api.delete(`/plans/${id}`)
}
