<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Building2, Plus } from 'lucide-vue-next';
</script>

<template>
  <Head title="Painel do Sistema" />
  <AuthenticatedLayout>
    <div class="flex gap-4 mb-8 justify-between">
      <HeaderTitle title="Painel do Sistema" subtitle="Administração do sistema (super admin)" />
      <div class="flex gap-3 py-2">
        <Link :href="route('admin.offices.index')">
          <PrimaryButton :icon="Building2">
            <span class="mt-1">Ver Escritórios</span>
          </PrimaryButton>
        </Link>
        <Link :href="route('admin.offices.create')">
          <PrimaryButton :icon="Plus">
            <span class="mt-1">Novo Escritório</span>
          </PrimaryButton>
        </Link>
      </div>
    </div>

    <div class="bg-white shadow rounded p-6">
      <p class="text-sm text-text-secondary">Use o menu lateral para navegar pelas áreas de administração do sistema.</p>
    </div>
  </AuthenticatedLayout>
</template>
