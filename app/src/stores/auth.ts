import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/utils/api'

interface User {
  id: number
  name: string
  email: string
}

interface LoginPayload {
  email: string
  password: string
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const token = ref<string | null>(localStorage.getItem('auth_token'))
  const isAuthenticated = computed(() => Boolean(token.value))

  async function login(payload: LoginPayload) {
    const { data } = await api.post('/auth/login', payload)
    token.value = data.token
    user.value = data.user
    localStorage.setItem('auth_token', token.value || '')
  }

  async function fetchMe() {
    if (!token.value) return
    try {
      const { data } = await api.get('/auth/me')
      user.value = data
    } catch (e) {
      logout()
      throw e
    }
  }

  function logout() {
    token.value = null
    user.value = null
    localStorage.removeItem('auth_token')
  }

  return { user, token, isAuthenticated, login, logout, fetchMe }
})
