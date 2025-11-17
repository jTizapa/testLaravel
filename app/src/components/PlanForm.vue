<template>
  <div class="modal">
    <div class="modal__content">
      <header class="modal__header">
        <h3>{{ plan ? 'Editar plan' : 'Nuevo plan' }}</h3>
        <button class="icon" @click="$emit('close')">✕</button>
      </header>
      <form @submit.prevent="onSubmit">
        <label>
          Nombre
          <input v-model="form.name" required />
        </label>
        <label>
          Duración (días)
          <input v-model.number="form.duration_days" type="number" min="1" required />
        </label>
        <label>
          Precio
          <input v-model.number="form.price" type="number" step="0.01" min="0" required />
        </label>
        <label class="checkbox">
          <input v-model="form.active" type="checkbox" /> Activo
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
import type { Plan, PlanPayload } from '@/utils/plans'
import { usePlansStore } from '@/stores/plans'

const props = defineProps<{ plan: Plan | null }>()
const emit = defineEmits<{ saved: []; close: [] }>()
const store = usePlansStore()

const form = reactive<PlanPayload>({
  name: '',
  duration_days: 30,
  price: 0,
  active: true,
})
const loading = ref(false)
const error = ref('')

watch(() => props.plan, (p) => {
  if (p) {
    form.name = p.name
    form.duration_days = p.duration_days
    form.price = p.price
    form.active = p.active
  } else {
    form.name = ''
    form.duration_days = 30
    form.price = 0
    form.active = true
  }
}, { immediate: true })

const onSubmit = async () => {
  loading.value = true
  error.value = ''
  try {
    if (props.plan) {
      await store.update(props.plan.id, form)
    } else {
      await store.create(form)
    }
    emit('saved')
  } catch (e) {
    error.value = 'No se pudo guardar el plan'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.modal { position: fixed; inset: 0; background: rgba(0,0,0,0.4); display: grid; place-items: center; }
.modal__content { background: #fff; padding: 1rem; border-radius: 8px; width: min(480px, 90vw); border: 1px solid #e5e7eb; }
.modal__header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
.icon { border: none; background: transparent; font-size: 1.1rem; cursor: pointer; }
form { display: flex; flex-direction: column; gap: 0.75rem; }
input { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px; }
.checkbox { display: flex; align-items: center; gap: 0.5rem; }
.actions { display: flex; justify-content: flex-end; gap: 0.5rem; }
button { padding: 0.5rem 0.8rem; border-radius: 6px; border: none; cursor: pointer; }
button.primary { background: #2563eb; color: #fff; }
.error { color: #b91c1c; }
</style>
