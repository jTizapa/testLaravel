<template>
  <v-dialog v-model="open" max-width="520">
    <v-card>
      <v-card-title>Registrar pago manual</v-card-title>
      <v-card-text>
        <v-form class="d-flex flex-column ga-4">
          <v-select
            v-model="form.subscription_id"
            :items="options"
            item-title="label"
            item-value="id"
            label="Subscripción"
            variant="outlined"
            required
          />
          <v-text-field
            v-model.number="form.amount"
            label="Monto"
            type="number"
            step="0.01"
            min="0"
            variant="outlined"
            required
          />
          <v-text-field v-model="form.method" label="Método" variant="outlined" />
          <v-alert v-if="error" type="error" density="comfortable" variant="tonal">{{ error }}</v-alert>
        </v-form>
      </v-card-text>
      <v-card-actions class="justify-end ga-2">
        <v-btn variant="text" @click="closeDialog">Cancelar</v-btn>
        <v-btn color="primary" :loading="loading" @click="onSubmit">Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted, computed } from 'vue'
import { usePaymentsStore } from '@/stores/payments'
import type { PaymentPayload } from '@/utils/payments'
import { fetchSubscriptions } from '@/utils/subscriptions'

const emit = defineEmits<{ saved: []; close: []; 'update:modelValue': [boolean] }>()
const props = defineProps<{ modelValue: boolean }>()
const store = usePaymentsStore()

const form = reactive<PaymentPayload>({
  subscription_id: 0,
  amount: 0,
  method: 'manual',
})
const loading = ref(false)
const error = ref('')
const options = ref<{ id: number; label: string }[]>([])
const open = computed({
  get: () => props.modelValue,
  set: (val: boolean) => emit('update:modelValue', val),
})

onMounted(async () => {
  const data = await fetchSubscriptions(1)
  options.value = data.data.map((s: any) => ({
    id: s.id,
    label: `#${s.id} - ${s.member?.name || 'Miembro'} / ${s.plan?.name || 'Plan'}`,
  }))
})

const onSubmit = async () => {
  loading.value = true
  error.value = ''
  try {
    await store.create(form)
    emit('saved')
    emit('update:modelValue', false)
  } catch (e) {
    error.value = 'No se pudo registrar el pago'
  } finally {
    loading.value = false
  }
}

const closeDialog = () => {
  emit('update:modelValue', false)
  emit('close')
}
</script>
