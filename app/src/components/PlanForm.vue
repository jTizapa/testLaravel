<template>
  <v-dialog v-model="open" max-width="520">
    <v-card>
      <v-card-title>{{ plan ? 'Editar plan' : 'Nuevo plan' }}</v-card-title>
      <v-card-text>
        <v-form class="d-flex flex-column ga-4">
          <v-text-field v-model="form.name" label="Nombre" variant="outlined" required />
          <v-text-field
            v-model.number="form.duration_days"
            label="Duración (días)"
            type="number"
            min="1"
            variant="outlined"
            required
          />
          <v-text-field
            v-model.number="form.price"
            label="Precio"
            type="number"
            step="0.01"
            min="0"
            variant="outlined"
            required
          />
          <v-switch v-model="form.active" label="Activo" color="primary" />
          <v-alert v-if="error" type="error" density="comfortable" variant="tonal">{{ error }}</v-alert>
        </v-form>
      </v-card-text>
      <v-card-actions class="justify-end ga-2">
        <v-btn variant="text" @click="emit('close')">Cancelar</v-btn>
        <v-btn color="primary" :loading="loading" @click="onSubmit">Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { reactive, ref, watch, computed } from 'vue'
import type { Plan, PlanPayload } from '@/utils/plans'
import { usePlansStore } from '@/stores/plans'

const props = defineProps<{ plan: Plan | null; modelValue: boolean }>()
const emit = defineEmits<{ saved: []; close: []; 'update:modelValue': [boolean] }>()
const store = usePlansStore()

const form = reactive<PlanPayload>({
  name: '',
  duration_days: 30,
  price: 0,
  active: true,
})
const loading = ref(false)
const error = ref('')
const open = computed({
  get: () => props.modelValue,
  set: (val: boolean) => emit('update:modelValue', val),
})

watch(
  () => props.plan,
  (p) => {
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
  },
  { immediate: true }
)

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
    emit('update:modelValue', false)
  } catch (e) {
    error.value = 'No se pudo guardar el plan'
  } finally {
    loading.value = false
  }
}
</script>
