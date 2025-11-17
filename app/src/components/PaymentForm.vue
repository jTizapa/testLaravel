<template>
  <div class="modal">
    <div class="modal__content">
      <header class="modal__header">
        <h3>Registrar pago manual</h3>
        <button class="icon" @click="$emit('close')">✕</button>
      </header>
      <form @submit.prevent="onSubmit">
        <label>
          Subscripción
          <select v-model.number="form.subscription_id" required>
            <option value="" disabled>Selecciona una suscripción</option>
            <option v-for="option in options" :key="option.id" :value="option.id">
              #{{ option.id }} - {{ option.member }} / {{ option.plan }}
            </option>
          </select>
        </label>
        <label>
          Monto
          <input v-model.number="form.amount" type="number" step="0.01" min="0" required />
        </label>
        <label>
          Método
          <input v-model="form.method" placeholder="manual" />
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
import { reactive, ref, onMounted } from 'vue'
import { usePaymentsStore } from '@/stores/payments'
import type { PaymentPayload } from '@/utils/payments'
import { fetchSubscriptions } from '@/utils/subscriptions'

const emit = defineEmits<{ saved: []; close: [] }>()
const store = usePaymentsStore()

const form = reactive<PaymentPayload>({
  subscription_id: 0,
  amount: 0,
  method: 'manual',
})
const loading = ref(false)
const error = ref('')
const options = ref<{ id: number; member: string; plan: string }[]>([])

onMounted(async () => {
  const data = await fetchSubscriptions(1)
  options.value = data.data.map((s: any) => ({
    id: s.id,
    member: s.member?.name || `Miembro ${s.member_id}`,
    plan: s.plan?.name || `Plan ${s.plan_id}`,
  }))
})

const onSubmit = async () => {
  loading.value = true
  error.value = ''
  try {
    await store.create(form)
    emit('saved')
  } catch (e) {
    error.value = 'No se pudo registrar el pago'
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
.actions { display: flex; justify-content: flex-end; gap: 0.5rem; }
button { padding: 0.5rem 0.8rem; border-radius: 6px; border: none; cursor: pointer; }
button.primary { background: #2563eb; color: #fff; }
.error { color: #b91c1c; }
</style>
