<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

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
    role: 'office_owner',
  },
});

function submit() {
  form.clearErrors();

  const requiredFields = [
    ['office.name', form.office.name, 'O nome do escritório é obrigatório.'],
    ['office.fantasy_name', form.office.fantasy_name, 'O nome fantasia é obrigatório.'],
    ['office.cnpj', form.office.cnpj, 'O CNPJ é obrigatório.'],
    ['office.phone', form.office.phone, 'O telefone é obrigatório.'],
    ['office.email', form.office.email, 'O email do escritório é obrigatório.'],
    ['office.street', form.office.street, 'A rua é obrigatória.'],
    ['office.number', form.office.number, 'O número é obrigatório.'],
    ['office.city', form.office.city, 'A cidade é obrigatória.'],
    ['office.state', form.office.state, 'O estado é obrigatório.'],
    ['office.zip_code', form.office.zip_code, 'O CEP é obrigatório.'],
    ['office.current_plan', form.office.current_plan, 'O plano atual é obrigatório.'],
    ['user.name', form.user.name, 'O nome do usuário é obrigatório.'],
    ['user.email', form.user.email, 'O email do usuário é obrigatório.'],
    ['user.password', form.user.password, 'A senha do usuário é obrigatória.'],
  ];

  let hasErrors = false;
  for (const [path, value, message] of requiredFields) {
    if (String(value ?? '').trim() === '') {
      form.setError(path, message);
      hasErrors = true;
    }
  }

  if (hasErrors) return;

  form.post(route('admin.offices.store'));
}
</script>

<template>
  <Head title="Cadastrar Novo Escritório" />

  <AuthenticatedLayout>
    <div class="flex gap-4 mb-8 justify-between">
      <div class="flex gap-4">
        <Link :href="route('admin.offices.index')">
          <SecondaryButton :icon="ArrowLeft">
            <span class="mt-1">Voltar</span>
          </SecondaryButton>
        </Link>
        <HeaderTitle title="Cadastrar Novo Escritório" subtitle="Onboarding de um novo tenant" />
      </div>
    </div>

    <form @submit.prevent="submit" class="space-y-8 max-w-5xl">
      <!-- Seção 1: Dados do Office -->
      <div class="bg-white shadow rounded p-6">
        <h2 class="text-lg font-medium mb-4">Dados do Escritório</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Nome*</label>
            <input v-model="form.office.name" type="text" class="w-full border rounded p-2" required />
            <div v-if="form.errors['office.name']" class="text-red-600 text-sm mt-1">{{ form.errors['office.name'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Nome Fantasia</label>
            <input v-model="form.office.fantasy_name" type="text" class="w-full border rounded p-2" required />
            <div v-if="form.errors['office.fantasy_name']" class="text-red-600 text-sm mt-1">{{ form.errors['office.fantasy_name'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">CNPJ*</label>
            <input v-model="form.office.cnpj" type="text" class="w-full border rounded p-2" required />
            <div v-if="form.errors['office.cnpj']" class="text-red-600 text-sm mt-1">{{ form.errors['office.cnpj'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Telefone</label>
            <input v-model="form.office.phone" type="text" class="w-full border rounded p-2" required />
            <div v-if="form.errors['office.phone']" class="text-red-600 text-sm mt-1">{{ form.errors['office.phone'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input v-model="form.office.email" type="email" class="w-full border rounded p-2" required />
            <div v-if="form.errors['office.email']" class="text-red-600 text-sm mt-1">{{ form.errors['office.email'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Plano Atual</label>
            <input v-model="form.office.current_plan" type="text" class="w-full border rounded p-2" required />
            <div v-if="form.errors['office.current_plan']" class="text-red-600 text-sm mt-1">{{ form.errors['office.current_plan'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Rua</label>
            <input v-model="form.office.street" type="text" class="w-full border rounded p-2" required />
            <div v-if="form.errors['office.street']" class="text-red-600 text-sm mt-1">{{ form.errors['office.street'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Número</label>
            <input v-model="form.office.number" type="text" class="w-full border rounded p-2" required />
            <div v-if="form.errors['office.number']" class="text-red-600 text-sm mt-1">{{ form.errors['office.number'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Cidade</label>
            <input v-model="form.office.city" type="text" class="w-full border rounded p-2" required />
            <div v-if="form.errors['office.city']" class="text-red-600 text-sm mt-1">{{ form.errors['office.city'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Estado</label>
            <input v-model="form.office.state" type="text" class="w-full border rounded p-2" required />
            <div v-if="form.errors['office.state']" class="text-red-600 text-sm mt-1">{{ form.errors['office.state'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">CEP</label>
            <input v-model="form.office.zip_code" type="text" class="w-full border rounded p-2" required />
            <div v-if="form.errors['office.zip_code']" class="text-red-600 text-sm mt-1">{{ form.errors['office.zip_code'] }}</div>
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
            <input v-model="form.user.name" type="text" class="w-full border rounded p-2" required />
            <div v-if="form.errors['user.name']" class="text-red-600 text-sm mt-1">{{ form.errors['user.name'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Email*</label>
            <input v-model="form.user.email" type="email" class="w-full border rounded p-2" required />
            <div v-if="form.errors['user.email']" class="text-red-600 text-sm mt-1">{{ form.errors['user.email'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Senha*</label>
            <input v-model="form.user.password" type="password" class="w-full border rounded p-2" required minlength="8" />
            <div v-if="form.errors['user.password']" class="text-red-600 text-sm mt-1">{{ form.errors['user.password'] }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Função</label>
            <select v-model="form.user.role" class="w-full border rounded p-2">
              <option value="office_owner">Dono do Escritório</option>
              <option value="admin">Administrador</option>
              <option value="worker">Colaborador</option>
            </select>
          </div>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <Link :href="route('admin.offices.index')" class="px-4 py-2 border rounded">Cancelar</Link>
        <button type="submit" class="px-4 py-2 bg-surface-accent text-white rounded" :disabled="form.processing">
          {{ form.processing ? 'Salvando...' : 'Salvar' }}
        </button>
      </div>
    </form>
  </AuthenticatedLayout>
</template>
