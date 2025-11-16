<template>
  <div class="layout">
    <aside class="sidebar">
      <h1 class="logo">Gym Payments</h1>
      <nav>
        <RouterLink to="/dashboard" class="nav-link" active-class="active">Dashboard</RouterLink>
        <RouterLink to="/members" class="nav-link" active-class="active">Miembros</RouterLink>
        <RouterLink to="/plans" class="nav-link" active-class="active">Planes</RouterLink>
        <RouterLink to="/payments" class="nav-link" active-class="active">Pagos</RouterLink>
      </nav>
    </aside>
    <div class="content">
      <header class="topbar">
        <div class="spacer" />
        <RouterLink to="/login" class="nav-link small">Salir</RouterLink>
      </header>
      <main class="main">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts"></script>

<style scoped>
.layout {
  display: grid;
  grid-template-columns: 220px 1fr;
  min-height: 100vh;
  background: #f7f7f8;
}
.sidebar {
  background: #111827;
  color: #f3f4f6;
  padding: 1.5rem 1rem;
}
.logo {
  margin: 0 0 1.5rem;
  font-size: 1.2rem;
}
nav {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.nav-link {
  color: #e5e7eb;
  text-decoration: none;
  padding: 0.5rem 0.75rem;
  border-radius: 6px;
}
.nav-link.active {
  background: #2563eb;
  color: #fff;
}
.content {
  display: flex;
  flex-direction: column;
}
.topbar {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding: 0.75rem 1.5rem;
  background: #fff;
  border-bottom: 1px solid #e5e7eb;
}
.topbar .small {
  font-size: 0.9rem;
}
.main {
  padding: 1.5rem;
}
</style>
