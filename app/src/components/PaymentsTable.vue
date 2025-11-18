<template>
  <section class="panel">
    <header class="panel__header">
      <div>
        <h2>Pagos</h2>
        <p class="subtitle">Pagos manuales registrados</p>
      </div>
      <div class="filters">
        <v-text-field v-model="search" label="Buscar método/estado" density="comfortable" hide-details />
        <v-btn color="primary" @click="showForm = true">Registrar pago</v-btn>
      </div>
    </header>

    <div v-if="message" class="info">{{ message }}</div>
    <div v-if="store.error" class="alert">{{ store.error }}</div>

    <v-data-table
      :headers="headers"
      :items="filtered"
      density="comfortable"
      :items-per-page="store.meta?.per_page || 15"
      class="elevation-1"
      disable-pagination
    >
      <template #item.status="{ item }">
        <v-chip :color="item.status === 'paid' ? 'green' : 'grey'" variant="tonal" size="small">
          {{ item.status }}
        </v-chip>
      </template>
    </v-data-table>

    <div class="pagination" v-if="store.meta">
      <v-btn :disabled="store.meta.current_page <= 1" @click="changePage(store.meta.current_page - 1)">Anterior</v-btn>
      <span>Página {{ store.meta.current_page }} de {{ store.meta.last_page }}</span>
      <v-btn :disabled="store.meta.current_page >= store.meta.last_page" @click="changePage(store.meta.current_page + 1)">Siguiente</v-btn>
    </div>

    <PaymentForm v-model="showForm" @close="showForm = false" @saved="onSaved" />
    <v-snackbar v-model="messageShown" color="success" timeout="2000">
      {{ message }}
    </v-snackbar>
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
const messageShown = ref(false)
const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Subscripción', key: 'subscription_id' },
  { title: 'Método', key: 'method' },
  { title: 'Monto', key: 'amount' },
  { title: 'Estado', key: 'status' },
  { title: 'Fecha', key: 'paid_at' },
]

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
  messageShown.value = true
}
</script>

<style scoped>
.panel { background: #fff; padding: 1rem; border-radius: 8px; border: 1px solid #e5e7eb; }
.panel__header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; gap: 1rem; flex-wrap: wrap; }
.subtitle { color: #6b7280; margin: 0.25rem 0 0; }
.filters { display: flex; align-items: center; gap: 0.5rem; }
.info { margin-bottom: 0.5rem; color: #0f172a; }
.alert { color: #b91c1c; margin-bottom: 0.5rem; }
.pagination { margin-top: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
</style>
