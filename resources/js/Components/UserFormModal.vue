<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import FormSelect from '@/Components/FormSelect.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
  show: { type: Boolean, default: false },
  roles: { type: Array, default: () => [] },
});
const emit = defineEmits(['close','created']);

const form = useForm({
  name: '',
  email: '',
  role: null,
  password: '',
  password_confirmation: '',
});

const localErrors = ref({ name:'', email:'', role:'', password:'', password_confirmation:'' });
const emailRegex = /^(?:[a-zA-Z0-9_'^&/+ -])+(?:\.(?:[a-zA-Z0-9_'^&/+ -])+)*@(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/;

function resetForm() {
  form.reset();
  form.clearErrors();
  localErrors.value = { name:'', email:'', role:'', password:'', password_confirmation:'' };
  form.role = props.roles[0]?.value || null;
}

watch(() => props.show, (v) => { if (v) resetForm(); });
watch(() => props.roles, (list) => {
  if (!form.role && Array.isArray(list) && list.length) {
    form.role = String(list[0].value ?? 'worker');
  }
}, { immediate: true });

function validateField(field) {
  switch (field) {
    case 'name': localErrors.value.name = form.name.trim() ? '' : 'Informe o nome.'; break;
    case 'email':
      if (!form.email) localErrors.value.email = 'Informe o e-mail.';
      else if (!emailRegex.test(form.email)) localErrors.value.email = 'Informe um e-mail válido.';
      else localErrors.value.email = '';
      break;
    case 'role': localErrors.value.role = form.role ? '' : 'Selecione o papel.'; break;
    case 'password':
      if (!form.password) localErrors.value.password = 'Informe a senha.';
      else if (form.password.length < 8) localErrors.value.password = 'A senha deve conter ao menos 8 caracteres.';
      else localErrors.value.password = '';
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

function close() { emit('close'); }

function submit() {
  // Coerce role to a valid option value
  const allowed = (props.roles || []).map(r => String(r.value));
  if (!allowed.includes(String(form.role))) {
    form.role = allowed[0] || 'worker';
  }
  if (!validateForm()) return;
  form.clearErrors();
  form.post(route('manage.users.store'), {
    onSuccess: () => { emit('created'); close(); },
    preserveScroll: true,
  });
}

const hasAnyError = computed(() => Object.values(localErrors.value).some(Boolean) || Object.keys(form.errors || {}).length > 0);
</script>

<template>
  <div v-if="show" @click="close" class="fixed inset-0 bg-black/50 z-[998]">
    <div @click.stop class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-xl z-[999] p-6 w-full max-w-xl">
      <h2 class="text-lg font-semibold text-text-primary">Novo Usuário</h2>
      <p class="text-sm text-text-secondary mb-4">Crie um novo usuário para sua equipe.</p>

      <div v-if="hasAnyError" class="rounded-md border border-state-danger/30 bg-red-50 px-3 py-2 text-sm text-state-danger mb-3">
        Corrija os campos destacados abaixo.
      </div>

      <form @submit.prevent="submit" class="grid gap-4">
        <div>
          <InputLabel for="name" value="Nome" />
          <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full"
                     :invalid="Boolean(localErrors.name || form.errors.name)" @blur="validateField('name')" autofocus />
          <InputError class="mt-1" :message="localErrors.name || form.errors.name" />
        </div>

        <div>
          <InputLabel for="email" value="E-mail" />
          <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full"
                     :invalid="Boolean(localErrors.email || form.errors.email)" @blur="validateField('email')" />
          <InputError class="mt-1" :message="localErrors.email || form.errors.email" />
        </div>

        <div>
          <InputLabel for="role" value="Papel" />
          <FormSelect id="role" v-model="form.role" :options="roles"
                      :invalid="Boolean(localErrors.role || form.errors.role)" @blur="validateField('role')" />
          <InputError class="mt-1" :message="localErrors.role || form.errors.role" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <InputLabel for="password" value="Senha" />
            <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full"
                       :invalid="Boolean(localErrors.password || form.errors.password)" @blur="validateField('password')" />
            <InputError class="mt-1" :message="localErrors.password || form.errors.password" />
          </div>
          <div>
            <InputLabel for="password_confirmation" value="Confirmar senha" />
            <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full"
                       :invalid="Boolean(localErrors.password_confirmation || form.errors.password_confirmation)" @blur="validateField('password_confirmation')" />
            <InputError class="mt-1" :message="localErrors.password_confirmation || form.errors.password_confirmation" />
          </div>
        </div>

        <div class="mt-2 flex justify-end gap-2">
          <SecondaryButton type="button" @click="close">Cancelar</SecondaryButton>
          <PrimaryButton type="submit" :disabled="form.processing">Salvar</PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>
