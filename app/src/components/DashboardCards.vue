<template>
  <div class="grid">
    <div class="card">
      <p class="label">Miembros</p>
      <p class="value">{{ stats?.total_members ?? '—' }}</p>
    </div>
    <div class="card">
      <p class="label">Suscripciones activas</p>
      <p class="value">{{ stats?.active_subscriptions ?? '—' }}</p>
    </div>
    <div class="card">
      <p class="label">Pagos hoy</p>
      <p class="value">{{ stats?.payments_today ?? '—' }}</p>
    </div>
    <div class="card">
      <p class="label">Ingresos hoy</p>
      <p class="value">${{ stats?.revenue_today ?? '—' }}</p>
    </div>
  </div>
  <div class="stripe" />
</template>

<script setup lang="ts">
import type { DashboardStats } from '@/utils/dashboard'

defineProps<{ stats: DashboardStats | null }>()
</script>

<style scoped>
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1.1rem;
}
.card {
  background: linear-gradient(180deg, #f8fafc, #fff);
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  padding: 1.1rem;
  box-shadow: 0 8px 26px rgba(0, 0, 0, 0.05);
}
.label {
  color: #6b7280;
  margin: 0;
  font-weight: 600;
}
.value {
  font-size: 1.8rem;
  margin: 0.2rem 0 0;
  color: #0f172a;
}
.stripe {
  height: 1px;
  background: linear-gradient(90deg, transparent, #2563eb33, transparent);
  margin: 1rem 0;
}
</style>
