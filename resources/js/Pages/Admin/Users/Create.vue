<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import FormSelect from '@/Components/FormSelect.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import IconTextButton from '@/Components/IconTextButton.vue';
import { Save, X } from 'lucide-vue-next';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
  roles: { type: Array, default: () => [] },
});

const form = useForm({
  name: '',
  email: '',
  role: props.roles[0]?.value || 'worker',
  password: '',
  password_confirmation: '',
});

const localErrors = ref({
  name: '', email: '', role: '', password: '', password_confirmation: ''
});

const emailRegex = /^(?:[a-zA-Z0-9_'^&\/+-])+(?:\.(?:[a-zA-Z0-9_'^&\/+-])+)*@(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/;

function validateField(field) {
  switch (field) {
    case 'name':
      localErrors.value.name = form.name.trim() ? '' : 'Informe o nome.'; break;
    case 'email':
      if (!form.email) localErrors.value.email = 'Informe o e-mail.';
      else if (!emailRegex.test(form.email)) localErrors.value.email = 'Informe um e-mail válido.';
      else localErrors.value.email = '';
      break;
    case 'role':
      localErrors.value.role = form.role ? '' : 'Selecione o papel.'; break;
    case 'password':
      if (!form.password) localErrors.value.password = 'Informe a senha.';
      else if (form.password.length < 8) localErrors.value.password = 'A senha deve conter ao menos 8 caracteres.';
      else localErrors.value.password = '';
      // revalidate confirmation if filled
      if (form.password_confirmation) validateField('password_confirmation');
      break;
    case 'password_confirmation':
      localErrors.value.password_confirmation = (form.password_confirmation && form.password_confirmation === form.password)
        ? '' : 'A confirmação da senha não confere.';
      break;
  }
}

function validateForm() {
  ['name','email','role','password','password_confirmation'].forEach(validateField);
  const e = localErrors.value;
  return !e.name && !e.email && !e.role && !e.password && !e.password_confirmation;
}

function onCancel() { router.get(route('manage.users.index')); }

function onSubmit() {
  if (!validateForm()) return;
  form.clearErrors();
  form.post(route('manage.users.store'), {
    onSuccess: () => router.get(route('manage.users.index')),
  });
}

const hasAnyError = computed(() => Object.values(localErrors.value).some(Boolean) || Object.keys(form.errors || {}).length > 0);
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Novo Usuário" />

    <div class="flex items-center justify-between">
      <HeaderTitle title="Novo Usuário" subtitle="Crie um novo usuário para sua equipe" />
    </div>

    <form @submit.prevent="onSubmit" novalidate class="mt-6 grid gap-6 max-w-2xl">
      <div v-if="hasAnyError" class="rounded-md border border-state-danger/30 bg-red-50 px-3 py-2 text-sm text-state-danger">
        Corrija os campos destacados abaixo.
      </div>

      <div class="bg-white rounded-lg shadow p-6 grid gap-4">
        <div>
          <InputLabel for="name" value="Nome" />
          <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name"
                     :invalid="Boolean(localErrors.name || form.errors.name)" @blur="validateField('name')" autofocus />
          <InputError class="mt-2" :message="localErrors.name || form.errors.name" />
        </div>

        <div>
          <InputLabel for="email" value="E-mail" />
          <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email"
                     :invalid="Boolean(localErrors.email || form.errors.email)" @blur="validateField('email')" />
          <InputError class="mt-2" :message="localErrors.email || form.errors.email" />
        </div>

        <div>
          <InputLabel for="role" value="Papel" />
          <FormSelect id="role" v-model="form.role" :options="roles"
                      :invalid="Boolean(localErrors.role || form.errors.role)" @blur="validateField('role')" />
          <InputError class="mt-2" :message="localErrors.role || form.errors.role" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <InputLabel for="password" value="Senha" />
            <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                       :invalid="Boolean(localErrors.password || form.errors.password)" @blur="validateField('password')" />
            <InputError class="mt-2" :message="localErrors.password || form.errors.password" />
          </div>
          <div>
            <InputLabel for="password_confirmation" value="Confirmar senha" />
            <TextInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation"
                       :invalid="Boolean(localErrors.password_confirmation || form.errors.password_confirmation)" @blur="validateField('password_confirmation')" />
            <InputError class="mt-2" :message="localErrors.password_confirmation || form.errors.password_confirmation" />
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6 flex justify-between">
        <IconTextButton type="button" class="my-2 border-red-500" iconClass="text-red-500" :icon="X" @click="onCancel">
          <span class="mt-1 text-red-500">Cancelar</span>
        </IconTextButton>
        <IconTextButton type="submit" class="my-2 bg-brand-accent" iconClass="text-white" :icon="Save">
          <span class="mt-1 text-white">Salvar</span>
        </IconTextButton>
      </div>
    </form>
  </AuthenticatedLayout>
</template>
