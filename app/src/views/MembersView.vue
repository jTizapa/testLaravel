<template>
  <MembersTable />
  <p class="note">Faltan filtros/paginación real; la base de CRUD está conectada a la API.</p>
</template>

<script setup lang="ts">
import MembersTable from '@/components/MembersTable.vue'
</script>

<style scoped>
.note { margin-top: 0.75rem; color: #6b7280; }
</style>
