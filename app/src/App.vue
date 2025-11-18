<script setup lang="ts">
import { computed } from 'vue'
import { useRoute, RouterView } from 'vue-router'
import AppShell from './components/AppShell.vue'

const route = useRoute()
const isAuthPage = computed(() => route.name === 'login')
</script>

<template>
  <v-app>
    <v-main>
      <RouterView v-if="isAuthPage" />
      <AppShell v-else />
    </v-main>
  </v-app>
</template>
