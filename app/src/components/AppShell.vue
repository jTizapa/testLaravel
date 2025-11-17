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
        <div class="container">
          <RouterView />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts"></script>

<style scoped>
.layout {
  min-height: 100vh;
  display: grid;
  grid-template-columns: 240px 1fr;
  background: #0f172a;
}
.sidebar {
  background: linear-gradient(180deg, #0f172a, #0b1220);
  color: #f3f4f6;
  padding: 2rem 1.25rem;
}
.logo {
  margin: 0 0 1.75rem;
  font-size: 1.4rem;
}
nav {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}
.nav-link {
  color: #e5e7eb;
  text-decoration: none;
  padding: 0.65rem 0.9rem;
  border-radius: 10px;
}
.nav-link.active {
  background: #2563eb;
  color: #fff;
  font-weight: 600;
}
.content {
  display: flex;
  flex-direction: column;
  background: #f5f6fa;
}
.topbar {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding: 0.9rem 1.75rem;
  background: #fff;
  border-bottom: 1px solid #e5e7eb;
}
.topbar .small {
  font-size: 0.95rem;
  color: #2563eb;
}
.main {
  flex: 1;
  padding: 2rem 2rem;
}
.container {
  background: #fff;
  border-radius: 14px;
  border: 1px solid #e5e7eb;
  min-height: calc(100vh - 120px);
  padding: 2rem;
  box-shadow: 0 10px 34px rgba(0, 0, 0, 0.08);
}
@media (max-width: 900px) {
  .layout {
    grid-template-columns: 1fr;
  }
  .sidebar {
    position: sticky;
    top: 0;
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  nav {
    flex-direction: row;
    flex-wrap: wrap;
  }
  .nav-link {
    padding: 0.5rem 0.7rem;
  }
  .container {
    min-height: auto;
  }
}
</style>
