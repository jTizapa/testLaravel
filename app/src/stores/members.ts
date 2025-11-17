import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Member, MemberPayload } from '@/utils/members'
import { fetchMembers, createMember, updateMember, deleteMember } from '@/utils/members'

interface PaginationMeta {
  current_page: number
  last_page: number
  total: number
}

interface Paginated<T> {
  data: T[]
  meta: PaginationMeta
}

export const useMembersStore = defineStore('members', () => {
  const items = ref<Member[]>([])
  const meta = ref<PaginationMeta | null>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)

  async function load(page = 1) {
    loading.value = true
    error.value = null
    try {
      const response: Paginated<Member> = await fetchMembers(page)
      items.value = response.data
      meta.value = response.meta
    } catch (e) {
      error.value = 'No se pudieron cargar los miembros'
      throw e
    } finally {
      loading.value = false
    }
  }

  async function create(payload: MemberPayload) {
    const member = await createMember(payload)
    items.value.unshift(member)
    return member
  }

  async function update(id: number, payload: Partial<MemberPayload>) {
    const updated = await updateMember(id, payload)
    const idx = items.value.findIndex((m) => m.id === id)
    if (idx !== -1) items.value[idx] = updated
    return updated
  }

  async function remove(id: number) {
    await deleteMember(id)
    items.value = items.value.filter((m) => m.id !== id)
  }

  return { items, meta, loading, error, load, create, update, remove }
})
