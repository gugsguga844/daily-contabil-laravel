<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
  <Head title="Admin Dashboard" />
  <div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Admin Dashboard</h1>
    <div class="bg-white shadow rounded p-6">
      <p class="mb-4">Bem-vindo ao painel de administração.</p>
      <div class="flex gap-3">
        <Link :href="route('admin.offices.create')" class="px-4 py-2 bg-surface-accent text-white rounded">Cadastrar Novo Escritório</Link>
      </div>
    </div>
  </div>
</template>
