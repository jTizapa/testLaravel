<script setup lang="ts">
import { computed } from 'vue'
import { useRoute, RouterView } from 'vue-router'
import AppShell from './components/AppShell.vue'

const route = useRoute()
const isAuthPage = computed(() => route.name === 'login')
</script>

<template>
  <RouterView v-if="isAuthPage" />
  <AppShell v-else />
</template>
