import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Plan, PlanPayload } from '@/utils/plans'
import { fetchPlans, createPlan, updatePlan, deletePlan } from '@/utils/plans'

interface PaginationMeta {
  current_page: number
  last_page: number
  total: number
}

interface Paginated<T> {
  data: T[]
  meta: PaginationMeta
}

export const usePlansStore = defineStore('plans', () => {
  const items = ref<Plan[]>([])
  const meta = ref<PaginationMeta | null>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)

  async function load(page = 1) {
    loading.value = true
    error.value = null
    try {
      const response: Paginated<Plan> = await fetchPlans(page)
      items.value = response.data
      meta.value = response.meta
    } catch (e) {
      error.value = 'No se pudieron cargar los planes'
      throw e
    } finally {
      loading.value = false
    }
  }

  async function create(payload: PlanPayload) {
    const plan = await createPlan(payload)
    items.value.unshift(plan)
    return plan
  }

  async function update(id: number, payload: Partial<PlanPayload>) {
    const updated = await updatePlan(id, payload)
    const idx = items.value.findIndex((p) => p.id === id)
    if (idx !== -1) items.value[idx] = updated
    return updated
  }

  async function remove(id: number) {
    await deletePlan(id)
    items.value = items.value.filter((p) => p.id !== id)
  }

  return { items, meta, loading, error, load, create, update, remove }
})
