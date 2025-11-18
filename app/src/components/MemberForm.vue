<template>
  <v-dialog v-model="open" max-width="520">
    <v-card>
      <v-card-title>{{ member ? 'Editar miembro' : 'Nuevo miembro' }}</v-card-title>
      <v-card-text>
        <v-form @submit.prevent="onSubmit" class="d-flex flex-column ga-4">
          <v-text-field v-model="form.name" label="Nombre" required variant="outlined" />
          <v-text-field v-model="form.email" label="Correo" type="email" required variant="outlined" />
          <v-text-field v-model="form.phone" label="Teléfono" variant="outlined" />
          <v-select
            v-model="form.status"
            label="Estado"
            :items="['active', 'inactive']"
            variant="outlined"
          />
          <v-alert v-if="error" type="error" density="compact" variant="tonal">{{ error }}</v-alert>
        </v-form>
      </v-card-text>
      <v-card-actions class="justify-end ga-2">
        <v-btn variant="text" @click="emit('close')">Cancelar</v-btn>
        <v-btn color="primary" :loading="loading" @click="onSubmit">
          Guardar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { reactive, ref, watch, computed } from 'vue'
import type { Member, MemberPayload } from '@/utils/members'
import { useMembersStore } from '@/stores/members'

const props = defineProps<{ member: Member | null; modelValue: boolean }>()
const emit = defineEmits<{ saved: []; close: []; 'update:modelValue': [boolean] }>()
const store = useMembersStore()

const form = reactive<MemberPayload>({
  name: '',
  email: '',
  phone: '',
  status: 'active',
})
const loading = ref(false)
const error = ref('')
const open = computed({
  get: () => props.modelValue,
  set: (val: boolean) => emit('update:modelValue', val),
})

watch(
  () => props.member,
  (m) => {
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
  },
  { immediate: true }
)

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
    emit('update:modelValue', false)
  } catch (e) {
    error.value = 'No se pudo guardar el miembro'
  } finally {
    loading.value = false
  }
}
</script>
