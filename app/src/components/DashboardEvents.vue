<template>
  <section class="panel">
    <header class="panel__header">
      <h3>Eventos recientes</h3>
      <span class="count">{{ events.length }}</span>
    </header>
    <ul class="list">
      <li v-for="event in events" :key="event.at + event.type">
        <div class="row">
          <strong>{{ event.type }}</strong>
          <small>{{ new Date(event.at).toLocaleTimeString() }}</small>
        </div>
        <pre>{{ event.payload }}</pre>
      </li>
      <li v-if="events.length === 0">Sin eventos aún</li>
    </ul>
  </section>
</template>

<script setup lang="ts">
import type { DashboardEvent } from '@/stores/dashboard'

defineProps<{ events: DashboardEvent[] }>()
</script>

<style scoped>
.panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 1.2rem; box-shadow: 0 6px 20px rgba(0,0,0,0.05); }
.panel__header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; }
.list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem; }
li { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 0.75rem; }
.row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem; }
pre { margin: 0; font-size: 0.9rem; white-space: pre-wrap; word-break: break-all; }
.count { background: #e5e7eb; border-radius: 999px; padding: 0.15rem 0.6rem; font-size: 0.9rem; }
</style>
