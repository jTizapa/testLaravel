import api from './api'

export interface Member {
  id: number
  name: string
  email: string
  phone?: string | null
  status: 'active' | 'inactive'
  joined_at?: string | null
}

export interface MemberPayload {
  name: string
  email: string
  phone?: string | null
  status?: 'active' | 'inactive'
  joined_at?: string | null
}

export async function fetchMembers(page = 1) {
  const { data } = await api.get(`/members?page=${page}`)
  return data
}

export async function createMember(payload: MemberPayload) {
  const { data } = await api.post('/members', payload)
  return data as Member
}

export async function updateMember(id: number, payload: Partial<MemberPayload>) {
  const { data } = await api.put(`/members/${id}`, payload)
  return data as Member
}

export async function deleteMember(id: number) {
  await api.delete(`/members/${id}`)
}
