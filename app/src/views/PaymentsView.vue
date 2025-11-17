<template>
  <PaymentsTable />
  <p class="note">Formulario simple requiere ID de suscripción existente.</p>
</template>

<script setup lang="ts">
import PaymentsTable from '@/components/PaymentsTable.vue'
</script>

<style scoped>
.note { margin-top: 0.75rem; color: #6b7280; }
</style>
