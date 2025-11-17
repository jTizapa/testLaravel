import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Payment, PaymentPayload } from '@/utils/payments'
import { fetchPayments, createPayment } from '@/utils/payments'

interface PaginationMeta {
  current_page: number
  last_page: number
  total: number
}

interface Paginated<T> {
  data: T[]
  meta: PaginationMeta
}

export const usePaymentsStore = defineStore('payments', () => {
  const items = ref<Payment[]>([])
  const meta = ref<PaginationMeta | null>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)

  async function load(page = 1) {
    loading.value = true
    error.value = null
    try {
      const response: Paginated<Payment> = await fetchPayments(page)
      items.value = response.data
      meta.value = response.meta
    } catch (e) {
      error.value = 'No se pudieron cargar los pagos'
      throw e
    } finally {
      loading.value = false
    }
  }

  async function create(payload: PaymentPayload) {
    const payment = await createPayment(payload)
    items.value.unshift(payment)
    return payment
  }

  return { items, meta, loading, error, load, create }
})
