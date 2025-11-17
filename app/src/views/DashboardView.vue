<template>
  <section class="panel">
    <h2>Dashboard</h2>
    <p>Métricas básicas y eventos en tiempo real.</p>
    <DashboardCards :stats="store.stats" />
  </section>
  <DashboardEvents :events="store.events" />
</template>

<script setup lang="ts">
import { onMounted, onBeforeUnmount } from 'vue'
import DashboardCards from '@/components/DashboardCards.vue'
import DashboardEvents from '@/components/DashboardEvents.vue'
import { useDashboardStore } from '@/stores/dashboard'
import { useEcho } from '@/utils/realtime'

const store = useDashboardStore()
let echo: ReturnType<typeof useEcho> | null = null

onMounted(async () => {
  await store.loadStats()
  echo = useEcho()
  echo.channel('dashboard')
    .listen('.payment.recorded', (payload: any) => store.pushEvent('payment.recorded', payload))
    .listen('.subscription.status_changed', (payload: any) => store.pushEvent('subscription.status_changed', payload))
})

onBeforeUnmount(() => {
  echo?.disconnect()
})
</script>

<style scoped>
.panel {
  background: #fff;
  padding: 1.25rem;
  border-radius: 10px;
  border: 1px solid #e5e7eb;
  margin-bottom: 1rem;
}
</style>
