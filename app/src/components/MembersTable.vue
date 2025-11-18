<template>
  <section class="panel">
    <header class="panel__header">
      <div>
        <h2>Miembros</h2>
        <p class="subtitle">Gestiona los miembros registrados</p>
      </div>
      <div class="filters">
        <input v-model="search" placeholder="Buscar por nombre o correo" />
        <button class="primary" @click="openCreate">Agregar</button>
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
        <v-chip :color="item.status === 'active' ? 'green' : 'grey'" variant="tonal" size="small">
          {{ item.status }}
        </v-chip>
      </template>
      <template #item.actions="{ item }">
        <v-btn icon="mdi-pencil" size="small" variant="text" @click="edit(item)"></v-btn>
        <v-btn icon="mdi-delete" size="small" variant="text" color="error" @click="remove(item.id)"></v-btn>
      </template>
    </v-data-table>

    <div class="pagination" v-if="store.meta">
      <v-btn :disabled="store.meta.current_page <= 1" @click="changePage(store.meta.current_page - 1)">Anterior</v-btn>
      <span>Página {{ store.meta.current_page }} de {{ store.meta.last_page }}</span>
      <v-btn :disabled="store.meta.current_page >= store.meta.last_page" @click="changePage(store.meta.current_page + 1)">Siguiente</v-btn>
    </div>

    <MemberForm v-model="showForm" :member="selected" @close="closeForm" @saved="onSaved" />
    <v-snackbar v-model="messageShown" color="success" timeout="2000">
      {{ message }}
    </v-snackbar>
  </section>
</template>

<script setup lang="ts">
import { onMounted, ref, computed } from 'vue'
import { useMembersStore } from '@/stores/members'
import MemberForm from './MemberForm.vue'
import type { Member } from '@/utils/members'

const store = useMembersStore()
const showForm = ref(false)
const selected = ref<Member | null>(null)
const search = ref('')
const message = ref('')
const messageShown = ref(false)
const headers = [
  { title: 'Nombre', key: 'name' },
  { title: 'Correo', key: 'email' },
  { title: 'Teléfono', key: 'phone' },
  { title: 'Estado', key: 'status' },
  { title: '', key: 'actions', sortable: false },
]

onMounted(() => {
  store.load()
})

const openCreate = () => {
  selected.value = null
  showForm.value = true
}

const edit = (member: Member) => {
  selected.value = member
  showForm.value = true
}

const closeForm = () => {
  showForm.value = false
}

const remove = async (id: number) => {
  if (!confirm('¿Eliminar miembro?')) return
  await store.remove(id)
}

const onSaved = () => {
  showForm.value = false
  message.value = 'Miembro guardado'
  messageShown.value = true
}

const filtered = computed(() => {
  if (!search.value) return store.items
  const term = search.value.toLowerCase()
  return store.items.filter((m) => m.name.toLowerCase().includes(term) || m.email.toLowerCase().includes(term))
})

const changePage = (page: number) => {
  store.load(page)
}
</script>

<style scoped>
.panel {
  background: #fff;
  padding: 1rem;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}
.panel__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}
.filters {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.filters input {
  padding: 0.4rem 0.6rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
}
.subtitle {
  color: #6b7280;
  margin: 0.25rem 0 0;
}
.info { color: #166534; margin-bottom: 0.5rem; }
.table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  padding: 0.6rem;
  border-bottom: 1px solid #e5e7eb;
  text-align: left;
}
.actions {
  display: flex;
  gap: 0.5rem;
}
button {
  border: none;
  padding: 0.4rem 0.7rem;
  border-radius: 6px;
  cursor: pointer;
}
button.primary { background: #2563eb; color: #fff; }
button.danger { background: #dc2626; color: #fff; }
.pill {
  padding: 0.25rem 0.5rem;
  border-radius: 999px;
  font-size: 0.85rem;
}
.pill--green { background: #dcfce7; color: #166534; }
.pill--gray { background: #e5e7eb; color: #374151; }
.alert { color: #b91c1c; margin-bottom: 0.5rem; }
.pagination {
  margin-top: 0.75rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
</style>
