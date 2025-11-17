<template>
  <div class="login-page">
    <div class="login-card">
      <div class="brand">
        <span class="logo-dot" /> Gym Payments
      </div>
      <h2>Iniciar sesión</h2>
      <p class="subtitle">Ingresa con tu usuario para gestionar miembros y pagos.</p>
      <form @submit.prevent="onSubmit">
        <label>
          Correo
          <input v-model="form.email" type="email" autocomplete="username" required />
        </label>
        <label>
          Contraseña
          <input v-model="form.password" type="password" autocomplete="current-password" required />
        </label>
        <button type="submit" :disabled="loading">
          {{ loading ? 'Entrando…' : 'Entrar' }}
        </button>
        <p class="error" v-if="error">{{ error }}</p>
      </form>
      <small class="hint">Usuario seed: admin@gymstack.test / password</small>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const route = useRoute()
const auth = useAuthStore()

const form = reactive({ email: '', password: '' })
const loading = ref(false)
const error = ref('')

const onSubmit = async () => {
  loading.value = true
  error.value = ''
  try {
    await auth.login(form)
    await auth.fetchMe()
    const redirect = (route.query.redirect as string) || '/dashboard'
    router.push(redirect)
  } catch (e) {
    error.value = 'Credenciales inválidas o no se pudo contactar el servidor'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: grid;
  place-items: center;
  background: radial-gradient(circle at 15% 20%, #1d4ed81a, transparent 35%), radial-gradient(circle at 80% 30%, #2563eb14, transparent 40%), #0b1220;
  padding: 2rem 1rem;
}
.login-card {
  width: min(560px, 96vw);
  background: linear-gradient(180deg, #ffffff, #f8fafc);
  padding: 2.6rem;
  border-radius: 18px;
  box-shadow: 0 22px 60px rgba(0, 0, 0, 0.22);
  border: 1px solid #e5e7eb;
}
.brand {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 0.5rem;
  font-size: 1.1rem;
}
.logo-dot {
  width: 14px;
  height: 14px;
  border-radius: 50%;
  background: #2563eb;
}
h2 {
  margin: 0;
  color: #0f172a;
  font-size: 1.6rem;
}
.subtitle {
  color: #6b7280;
  margin: 0.35rem 0 1.5rem;
  font-size: 1rem;
}
form {
  display: flex;
  flex-direction: column;
  gap: 0.95rem;
}
label {
  font-weight: 600;
  color: #0f172a;
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  font-size: 0.95rem;
}
input {
  width: 100%;
  padding: 0.9rem 1rem;
  border: 1px solid #d1d5db;
  border-radius: 12px;
  background: #f8fafc;
  font-size: 1rem;
}
input:focus {
  outline: 2px solid #bfdbfe;
  border-color: #2563eb;
}
button {
  padding: 0.95rem;
  border: none;
  border-radius: 12px;
  background: #2563eb;
  color: #fff;
  cursor: pointer;
  font-weight: 700;
  font-size: 1rem;
}
button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
.error {
  color: #b91c1c;
  font-size: 0.98rem;
  margin: 0.2rem 0 0;
}
.hint {
  display: block;
  margin-top: 1rem;
  color: #6b7280;
  font-size: 0.95rem;
}
@media (min-width: 768px) {
  .login-card {
    padding: 3rem;
    border-radius: 20px;
  }
}
</style>
