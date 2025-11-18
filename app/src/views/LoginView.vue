<template>
  <v-container fluid class="auth-wrapper pa-0">
    <v-row class="fill-height ma-0" align="center" justify="center">
      <v-col cols="12" sm="8" md="5" lg="4">
        <v-card elevation="16" rounded="xl">
          <v-card-text class="pa-8 pa-sm-10">
            <div class="d-flex align-center justify-space-between mb-6">
              <div class="d-flex align-center">
                <v-avatar color="primary" variant="tonal" size="40" class="mr-3">
                  <v-icon icon="mdi-dumbbell" color="primary" />
                </v-avatar>
                <div>
                  <p class="text-overline text-medium-emphasis mb-1">Gym Payments</p>
                  <h1 class="text-h5 font-weight-bold mb-0">Bienvenido</h1>
                </div>
              </div>
            </div>

            <p class="text-body-1 text-medium-emphasis mb-6">
              Inicia sesión para gestionar miembros, planes y pagos.
            </p>

            <v-form @submit.prevent="onSubmit" class="d-flex flex-column ga-5">
              <v-text-field
                v-model="form.email"
                label="Correo"
                type="email"
                variant="outlined"
                hide-details="auto"
                autocomplete="username"
                density="comfortable"
                required
              />
              <v-text-field
                v-model="form.password"
                label="Contraseña"
                type="password"
                variant="outlined"
                hide-details="auto"
                autocomplete="current-password"
                density="comfortable"
                required
              />

              <v-btn type="submit" :loading="loading" color="primary" size="x-large" block>
                Entrar
              </v-btn>

              <v-alert
                v-if="error"
                type="error"
                variant="tonal"
                density="comfortable"
              >
                {{ error }}
              </v-alert>
            </v-form>

            <p class="text-body-2 mt-8 text-medium-emphasis text-center">
              Usuario seed: admin@gymstack.test / password
            </p>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
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
.auth-wrapper {
  min-height: 100vh;
  background: radial-gradient(circle at 20% 20%, rgba(59, 130, 246, 0.12), transparent 30%),
    radial-gradient(circle at 80% 0%, rgba(45, 212, 191, 0.14), transparent 28%),
    linear-gradient(135deg, #f7f8fb 0%, #edf1f7 50%, #f7f9fc 100%);
}
</style>
