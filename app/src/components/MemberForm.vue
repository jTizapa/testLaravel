<template>
  <div class="modal">
    <div class="modal__content">
      <header class="modal__header">
        <h3>{{ member ? 'Editar miembro' : 'Nuevo miembro' }}</h3>
        <button class="icon" @click="$emit('close')">✕</button>
      </header>
      <form @submit.prevent="onSubmit">
        <label>
          Nombre
          <input v-model="form.name" required />
        </label>
        <label>
          Correo
          <input v-model="form.email" type="email" required />
        </label>
        <label>
          Teléfono
          <input v-model="form.phone" />
        </label>
        <label>
          Estado
          <select v-model="form.status">
            <option value="active">Activo</option>
            <option value="inactive">Inactivo</option>
          </select>
        </label>
        <footer class="actions">
          <button type="button" @click="$emit('close')">Cancelar</button>
          <button type="submit" class="primary" :disabled="loading">
            {{ loading ? 'Guardando…' : 'Guardar' }}
          </button>
        </footer>
        <p class="error" v-if="error">{{ error }}</p>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, watch } from 'vue'
import type { Member, MemberPayload } from '@/utils/members'
import { useMembersStore } from '@/stores/members'

const props = defineProps<{ member: Member | null }>()
const emit = defineEmits<{ saved: [] ; close: [] }>()
const store = useMembersStore()

const form = reactive<MemberPayload>({
  name: '',
  email: '',
  phone: '',
  status: 'active',
})
const loading = ref(false)
const error = ref('')

watch(() => props.member, (m) => {
  if (m) {
    form.name = m.name
    form.email = m.email
    form.phone = m.phone || ''
    form.status = m.status
  } else {
    form.name = ''
    form.email = ''
    form.phone = ''
    form.status = 'active'
  }
}, { immediate: true })

const onSubmit = async () => {
  loading.value = true
  error.value = ''
  try {
    if (props.member) {
      await store.update(props.member.id, form)
    } else {
      await store.create(form)
    }
    emit('saved')
  } catch (e) {
    error.value = 'No se pudo guardar el miembro'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.modal {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.4);
  display: grid;
  place-items: center;
}
.modal__content {
  background: #fff;
  padding: 1rem;
  border-radius: 8px;
  width: min(500px, 90vw);
  border: 1px solid #e5e7eb;
}
.modal__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}
.icon {
  border: none;
  background: transparent;
  font-size: 1.1rem;
  cursor: pointer;
}
form {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}
input, select {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
}
.actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
}
button {
  padding: 0.5rem 0.8rem;
  border-radius: 6px;
  border: none;
  cursor: pointer;
}
button.primary { background: #2563eb; color: #fff; }
.error { color: #b91c1c; }
</style>
