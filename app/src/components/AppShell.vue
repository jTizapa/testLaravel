<template>
  <v-layout class="min-h-screen">
    <v-navigation-drawer rail :rail-width="72" expand-on-hover permanent color="primary-darken-4">
      <v-list density="comfortable" nav>
        <v-list-item prepend-icon="mdi-dumbbell" title="Gym Payments" />
        <v-divider class="my-2" />
        <v-list-item
          v-for="link in links"
          :key="link.to"
          :to="link.to"
          :title="link.title"
          :prepend-icon="link.icon"
          rounded
        />
      </v-list>
    </v-navigation-drawer>

    <v-main>
      <v-app-bar flat color="white">
        <v-spacer />
        <v-btn variant="text" prepend-icon="mdi-logout" @click="logout">Salir</v-btn>
      </v-app-bar>
      <v-container fluid class="py-6 px-6">
        <v-sheet rounded="xl" elevation="3" class="pa-6">
          <RouterView />
        </v-sheet>
      </v-container>
    </v-main>
  </v-layout>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const links = [
  { to: '/dashboard', title: 'Dashboard', icon: 'mdi-view-dashboard' },
  { to: '/members', title: 'Miembros', icon: 'mdi-account-group' },
  { to: '/plans', title: 'Planes', icon: 'mdi-clipboard-list' },
  { to: '/payments', title: 'Pagos', icon: 'mdi-cash-multiple' },
]

const auth = useAuthStore()
const router = useRouter()

const logout = async () => {
  try {
    await auth.logout()
  } finally {
    router.push({ name: 'login' })
  }
}
</script>

<style scoped>
.min-h-screen {
  min-height: 100vh;
}
</style>
