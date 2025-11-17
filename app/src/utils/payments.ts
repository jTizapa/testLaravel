import api from './api'

export interface Payment {
  id: number
  subscription_id: number
  member_id: number
  amount: number
  method: string
  status: string
  paid_at?: string | null
}

export interface PaymentPayload {
  subscription_id: number
  amount: number
  method?: string
}

export async function fetchPayments(page = 1) {
  const { data } = await api.get(`/payments?page=${page}`)
  return data
}

export async function createPayment(payload: PaymentPayload) {
  const { data } = await api.post('/payments', payload)
  return data as Payment
}
