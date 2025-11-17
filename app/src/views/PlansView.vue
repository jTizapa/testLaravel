<template>
  <PlansTable />
  <p class="note">CRUD de planes conectado a la API. Pendiente filtros/paginación real.</p>
</template>

<script setup lang="ts">
import PlansTable from '@/components/PlansTable.vue'
</script>

<style scoped>
.note { margin-top: 0.75rem; color: #6b7280; }
</style>
