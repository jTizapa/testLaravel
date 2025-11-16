import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import DashboardView from '@/views/DashboardView.vue'
import LoginView from '@/views/LoginView.vue'
import MembersView from '@/views/MembersView.vue'
import PlansView from '@/views/PlansView.vue'
import PaymentsView from '@/views/PaymentsView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/login', name: 'login', component: LoginView },
    { path: '/', redirect: '/dashboard' },
    { path: '/dashboard', name: 'dashboard', component: DashboardView, meta: { requiresAuth: true } },
    { path: '/members', name: 'members', component: MembersView, meta: { requiresAuth: true } },
    { path: '/plans', name: 'plans', component: PlansView, meta: { requiresAuth: true } },
    { path: '/payments', name: 'payments', component: PaymentsView, meta: { requiresAuth: true } },
    { path: '/:pathMatch(.*)*', redirect: '/dashboard' },
  ],
})

router.beforeEach((to, _from, next) => {
  const auth = useAuthStore()
  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return next({ name: 'login', query: { redirect: to.fullPath } })
  }
  if (to.name === 'login' && auth.isAuthenticated) {
    return next({ name: 'dashboard' })
  }
  return next()
})

export default router
