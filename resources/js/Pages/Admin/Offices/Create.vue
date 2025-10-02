<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
  office: {
    name: '',
    fantasy_name: '',
    cnpj: '',
    phone: '',
    email: '',
    street: '',
    number: '',
    city: '',
    state: '',
    zip_code: '',
    is_active: true,
    current_plan: '',
  },
  user: {
    name: '',
    email: '',
    password: '',
    role: 'office-owner',
  },
});

function submit() {
  form.post(route('admin.offices.store'));
}
</script>

<template>
  <Head title="Cadastrar Novo Escritório" />

  <div class="max-w-5xl mx-auto p-6">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-semibold">Cadastrar Novo Escritório</h1>
      <Link class="text-surface-accent hover:underline" :href="route('admin.dashboard')">Voltar</Link>
    </div>

    <form @submit.prevent="submit" class="space-y-8">
      <!-- Seção 1: Dados do Office -->
      <div class="bg-white shadow rounded p-6">
        <h2 class="text-lg font-medium mb-4">Dados do Escritório</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Nome*</label>
            <input v-model="form.office.name" type="text" class="w-full border rounded p-2" />
            <div v-if="form.errors['office.name']" class="text-red-600 text-sm mt-1">{{ form.errors['office.name'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Nome Fantasia</label>
            <input v-model="form.office.fantasy_name" type="text" class="w-full border rounded p-2" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">CNPJ*</label>
            <input v-model="form.office.cnpj" type="text" class="w-full border rounded p-2" />
            <div v-if="form.errors['office.cnpj']" class="text-red-600 text-sm mt-1">{{ form.errors['office.cnpj'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Telefone</label>
            <input v-model="form.office.phone" type="text" class="w-full border rounded p-2" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input v-model="form.office.email" type="email" class="w-full border rounded p-2" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Plano Atual</label>
            <input v-model="form.office.current_plan" type="text" class="w-full border rounded p-2" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Rua</label>
            <input v-model="form.office.street" type="text" class="w-full border rounded p-2" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Número</label>
            <input v-model="form.office.number" type="text" class="w-full border rounded p-2" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Cidade</label>
            <input v-model="form.office.city" type="text" class="w-full border rounded p-2" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Estado</label>
            <input v-model="form.office.state" type="text" class="w-full border rounded p-2" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">CEP</label>
            <input v-model="form.office.zip_code" type="text" class="w-full border rounded p-2" />
          </div>
          <div class="flex items-center gap-2">
            <input id="is_active" v-model="form.office.is_active" type="checkbox" class="h-4 w-4" />
            <label for="is_active" class="text-sm">Escritório Ativo</label>
          </div>
        </div>
      </div>

      <!-- Seção 2: Primeiro Usuário -->
      <div class="bg-white shadow rounded p-6">
        <h2 class="text-lg font-medium mb-4">Primeiro Usuário do Escritório</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Nome*</label>
            <input v-model="form.user.name" type="text" class="w-full border rounded p-2" />
            <div v-if="form.errors['user.name']" class="text-red-600 text-sm mt-1">{{ form.errors['user.name'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Email*</label>
            <input v-model="form.user.email" type="email" class="w-full border rounded p-2" />
            <div v-if="form.errors['user.email']" class="text-red-600 text-sm mt-1">{{ form.errors['user.email'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Senha*</label>
            <input v-model="form.user.password" type="password" class="w-full border rounded p-2" />
            <div v-if="form.errors['user.password']" class="text-red-600 text-sm mt-1">{{ form.errors['user.password'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Função</label>
            <select v-model="form.user.role" class="w-full border rounded p-2">
              <option value="office-owner">Dono do Escritório</option>
              <option value="admin">Administrador</option>
              <option value="worker">Colaborador</option>
            </select>
          </div>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <Link :href="route('admin.dashboard')" class="px-4 py-2 border rounded">Cancelar</Link>
        <button type="submit" class="px-4 py-2 bg-surface-accent text-white rounded" :disabled="form.processing">
          {{ form.processing ? 'Salvando...' : 'Salvar' }}
        </button>
      </div>
    </form>
  </div>
</template>
