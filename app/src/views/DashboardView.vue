<template>
  <section class="panel">
    <h2>Dashboard</h2>
    <p>Bienvenido. Aquí mostraremos métricas en tiempo real de pagos y suscripciones.</p>
    <div class="grid">
      <div class="card">Total miembros (seed/demo)</div>
      <div class="card">Pagos recientes</div>
      <div class="card">Suscripciones por vencer</div>
    </div>
  </section>
</template>

<script setup lang="ts"></script>

<style scoped>
.panel {
  background: #fff;
  padding: 1.25rem;
  border-radius: 10px;
  border: 1px solid #e5e7eb;
}
.grid {
  display: grid;
  gap: 1rem;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  margin-top: 1rem;
}
.card {
  background: #f8fafc;
  padding: 1rem;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}
</style>
