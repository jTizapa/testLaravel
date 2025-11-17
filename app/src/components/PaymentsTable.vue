<template>
  <section class="panel">
    <header class="panel__header">
      <div>
        <h2>Pagos</h2>
        <p class="subtitle">Pagos manuales registrados</p>
      </div>
      <div class="filters">
        <input v-model="search" placeholder="Buscar por método o status" />
        <button class="primary" @click="showForm = true">Registrar pago</button>
      </div>
    </header>

    <div v-if="message" class="info">{{ message }}</div>
    <div v-if="store.error" class="alert">{{ store.error }}</div>

    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Subscripción</th>
          <th>Método</th>
          <th>Monto</th>
          <th>Estado</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="payment in filtered" :key="payment.id">
          <td>{{ payment.id }}</td>
          <td>#{{ payment.subscription_id }}</td>
          <td>{{ payment.method }}</td>
          <td>${{ payment.amount }}</td>
          <td>{{ payment.status }}</td>
          <td>{{ payment.paid_at || '-' }}</td>
        </tr>
      </tbody>
    </table>

    <div class="pagination" v-if="store.meta">
      <button :disabled="store.meta.current_page <= 1" @click="changePage(store.meta.current_page - 1)">Anterior</button>
      <span>Página {{ store.meta.current_page }} de {{ store.meta.last_page }}</span>
      <button :disabled="store.meta.current_page >= store.meta.last_page" @click="changePage(store.meta.current_page + 1)">Siguiente</button>
    </div>

    <PaymentForm v-if="showForm" @close="showForm = false" @saved="onSaved" />
  </section>
</template>

<script setup lang="ts">
import { onMounted, ref, computed } from 'vue'
import { usePaymentsStore } from '@/stores/payments'
import PaymentForm from './PaymentForm.vue'

const store = usePaymentsStore()
const showForm = ref(false)
const search = ref('')
const message = ref('')

onMounted(() => {
  store.load()
})

const filtered = computed(() => {
  if (!search.value) return store.items
  const term = search.value.toLowerCase()
  return store.items.filter((p) => p.method.toLowerCase().includes(term) || p.status.toLowerCase().includes(term))
})

const changePage = (page: number) => {
  store.load(page)
}

const onSaved = () => {
  message.value = 'Pago registrado'
  showForm.value = false
  setTimeout(() => (message.value = ''), 2500)
}
</script>

<style scoped>
.panel { background: #fff; padding: 1rem; border-radius: 8px; border: 1px solid #e5e7eb; }
.panel__header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
.subtitle { color: #6b7280; margin: 0.25rem 0 0; }
.filters { display: flex; align-items: center; gap: 0.5rem; }
.filters input { padding: 0.4rem 0.6rem; border: 1px solid #d1d5db; border-radius: 6px; }
.info { margin-bottom: 0.5rem; color: #0f172a; }
.table { width: 100%; border-collapse: collapse; }
th, td { padding: 0.6rem; border-bottom: 1px solid #e5e7eb; text-align: left; }
button { border: none; padding: 0.4rem 0.7rem; border-radius: 6px; cursor: pointer; }
button.primary { background: #2563eb; color: #fff; }
.alert { color: #b91c1c; margin-bottom: 0.5rem; }
.pagination { margin-top: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
</style>
