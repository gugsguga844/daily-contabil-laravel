<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ArrowLeft, Building2, Cog, MapPin, Save, X } from 'lucide-vue-next';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import IconButton from '@/Components/IconButton.vue';
import SmallHeaderTitle from '@/Components/SmallHeaderTitle.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import FormSelect from '@/Components/FormSelect.vue';
import IconTextButton from '@/Components/IconTextButton.vue';
import InputError from '@/Components/InputError.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    accountants: Array,
});

const form = useForm({
    name: '',
    fantasy_name: '',
    cnpj: '',
    tax_regime: '',
    phone: '',
    email: '',
    zip_code: '',
    street: '',
    number: '',
    city: '',
    state: '',
    accountant_id: props.accountants.length > 0 ? props.accountants[0].value : null,
})

function formatCnpj(value) {
    const digits = String(value || '').replace(/\D/g, '').slice(0, 14);
    const parts = [];
    if (digits.length > 0) parts.push(digits.substring(0, 2));
    if (digits.length > 2) parts.push(digits.substring(2, 5));
    if (digits.length > 5) parts.push(digits.substring(5, 8));
    let rest = '';
    if (digits.length > 8) rest = digits.substring(8, 12) + (digits.length > 12 ? '-' + digits.substring(12, 14) : '');
    const main = parts.length >= 3 ? `${parts[0]}.${parts[1]}.${parts[2]}` : parts.length === 2 ? `${parts[0]}.${parts[1]}` : parts[0] || '';
    return main + (rest ? '/' + rest : '');
}

function formatPhone(value) {
    const digits = String(value || '').replace(/\D/g, '').slice(0, 11);
    if (digits.length <= 2) return digits;
    if (digits.length <= 7) return `(${digits.slice(0, 2)}) ${digits.slice(2)}`;
    return `(${digits.slice(0, 2)}) ${digits.slice(2, 7)}-${digits.slice(7)}`;
}

function formatCep(value) {
    const digits = String(value || '').replace(/\D/g, '').slice(0, 8);
    if (digits.length <= 5) return digits;
    return `${digits.slice(0, 5)}-${digits.slice(5)}`;
}

const cepLoading = ref(false);
async function tryFetchAddressByCep() {
    const digits = String(form.zip_code || '').replace(/\D/g, '');
    if (digits.length !== 8) return;
    try {
        cepLoading.value = true;
        const res = await fetch(`https://viacep.com.br/ws/${digits}/json/`);
        const data = await res.json();
        if (data?.erro) {
            localErrors.value.zip_code = 'CEP não encontrado.';
            return;
        }
        // Fill address fields if available
        if (data.logradouro && !form.street) form.street = data.logradouro;
        if (data.localidade && !form.city) form.city = data.localidade;
        if (data.uf && !form.state) form.state = data.uf;
        // Clear zip error if previously set
        if (localErrors.value.zip_code === 'CEP não encontrado.') localErrors.value.zip_code = '';
    } catch (e) {
        // Network or parsing error
        localErrors.value.zip_code = 'Não foi possível consultar o CEP agora.';
    } finally {
        cepLoading.value = false;
    }
}

watch(() => form.zip_code, (val) => {
    const digits = String(val || '').replace(/\D/g, '');
    if (digits.length === 8 && !cepLoading.value) {
        tryFetchAddressByCep();
    }
});

function onSubmit() {
    if (!validateForm()) return;
    form.clearErrors();
    form.post(route('companies.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
}
// Client-side validation (PT-BR)
const localErrors = ref({
    name: '',
    fantasy_name: '',
    cnpj: '',
    tax_regime: '',
    phone: '',
    email: '',
    zip_code: '',
    street: '',
    number: '',
    city: '',
    state: '',
    accountant_id: '',
});

const emailRegex = /^(?:[a-zA-Z0-9_'^&\/+-])+(?:\.(?:[a-zA-Z0-9_'^&\/+-])+)*@(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/;

function validateField(field) {
    switch (field) {
        case 'name':
            localErrors.value.name = form.name.trim() ? '' : 'Informe a razão social.';
            break;
        case 'fantasy_name':
            localErrors.value.fantasy_name = form.fantasy_name.trim() ? '' : 'Informe o nome fantasia.';
            break;
        case 'cnpj': {
            const digits = form.cnpj.replace(/\D/g, '');
            if (!digits) localErrors.value.cnpj = 'Informe o CNPJ.';
            else if (digits.length !== 14) localErrors.value.cnpj = 'CNPJ deve conter 14 dígitos.';
            else localErrors.value.cnpj = '';
            break; }
        case 'tax_regime':
            localErrors.value.tax_regime = form.tax_regime ? '' : 'Selecione o regime tributário.';
            break;
        case 'phone': {
            const digits = form.phone.replace(/\D/g, '');
            if (!digits) localErrors.value.phone = 'Informe o telefone.';
            else if (digits.length !== 11) localErrors.value.phone = 'Telefone deve conter 11 dígitos (DDD + número).';
            else localErrors.value.phone = '';
            break; }
        case 'email':
            if (!form.email) localErrors.value.email = 'Informe o e-mail.';
            else if (!emailRegex.test(form.email)) localErrors.value.email = 'Informe um e-mail válido.';
            else localErrors.value.email = '';
            break;
        case 'zip_code': {
            const digits = form.zip_code.replace(/\D/g, '');
            if (!digits) localErrors.value.zip_code = 'Informe o CEP.';
            else if (digits.length !== 8) localErrors.value.zip_code = 'CEP deve conter 8 dígitos.';
            else localErrors.value.zip_code = '';
            break; }
        case 'street':
            localErrors.value.street = form.street.trim() ? '' : 'Informe o logradouro.';
            break;
        case 'number':
            localErrors.value.number = form.number.trim() ? '' : 'Informe o número.';
            break;
        case 'city':
            localErrors.value.city = form.city.trim() ? '' : 'Informe a cidade.';
            break;
        case 'state':
            localErrors.value.state = form.state.trim() ? '' : 'Informe o estado.';
            break;
        case 'accountant_id':
            localErrors.value.accountant_id = form.accountant_id ? '' : 'Selecione o contador responsável.';
            break;
    }
}

function validateForm() {
    ['name','fantasy_name','cnpj','tax_regime','phone','email','zip_code','street','number','city','state','accountant_id']
        .forEach(validateField);
    const errs = localErrors.value;
    return !errs.name && !errs.fantasy_name && !errs.cnpj && !errs.tax_regime && !errs.phone && !errs.email && !errs.zip_code && !errs.street && !errs.number && !errs.city && !errs.state && !errs.accountant_id;
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex gap-4">
            <div class="py-2">
                <SecondaryButton :icon="ArrowLeft">
                    <span class="mt-1">Voltar</span>
                </SecondaryButton>
            </div>
            <HeaderTitle title="Nova Empresa" subtitle="Crie uma nova empresa" />
        </div>

        <form @submit.prevent="onSubmit" novalidate class="mt-6 flex flex-col gap-6">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex gap-4">
                    <IconButton class="my-2" :icon="Building2" />
                    <SmallHeaderTitle title="Dados da Empresa" subtitle="Preencha os dados da empresa" />
                </div>
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <InputLabel for="name">Razão Social</InputLabel>
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            :invalid="Boolean(localErrors.name || form.errors.name)"
                            @blur="validateField('name')"
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-1" :message="localErrors.name || form.errors.name" />
                    </div>
                    <div class="form-group">
                        <InputLabel for="fantasy_name">Nome Fantasia</InputLabel>
                        <TextInput
                            id="fantasy_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.fantasy_name"
                            :invalid="Boolean(localErrors.fantasy_name || form.errors.fantasy_name)"
                            @blur="validateField('fantasy_name')"
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-1" :message="localErrors.fantasy_name || form.errors.fantasy_name" />
                    </div>
                    <div class="form-group">
                        <InputLabel for="cnpj">CNPJ</InputLabel>
                        <TextInput
                            id="cnpj"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.cnpj"
                            :invalid="Boolean(localErrors.cnpj || form.errors.cnpj)"
                            autofocus
                            autocomplete="username"
                            placeholder="00.000.000/0000-00"
                            @input="form.cnpj = formatCnpj($event.target.value)"
                            @blur="validateField('cnpj')"
                        />
                        <InputError class="mt-1" :message="localErrors.cnpj || form.errors.cnpj" />
                    </div>
                    <div class="form-group">
                        <InputLabel for="e-mail">Regime Tributário</InputLabel>
                        <FormSelect
                            id="tax_regime"
                            v-model="form.tax_regime"
                            :options="[
                                { value: 'simples_nacional', label: 'Simples Nacional' },
                                { value: 'lucro_presumido', label: 'Lucro Presumido' },
                                { value: 'lucro_real', label: 'Lucro Real' },
                            ]"
                            :invalid="Boolean(localErrors.tax_regime || form.errors.tax_regime)"
                            autofocus
                            autocomplete="username"
                            @blur="validateField('tax_regime')"
                        />
                        <InputError class="mt-1" :message="localErrors.tax_regime || form.errors.tax_regime" />
                    </div>
                    <div class="form-group">
                        <InputLabel for="phone">Telefone</InputLabel>
                        <TextInput
                            id="phone"
                            type="tel"
                            class="mt-1 block w-full"
                            v-model="form.phone"
                            :invalid="Boolean(localErrors.phone || form.errors.phone)"
                            autofocus
                            autocomplete="username"
                            placeholder="(00) 00000-0000"
                            @input="form.phone = formatPhone($event.target.value)"
                            @blur="validateField('phone')"
                        />
                        <InputError class="mt-1" :message="localErrors.phone || form.errors.phone" />
                    </div>
                    <div class="form-group">
                        <InputLabel for="e-mail">E-mail</InputLabel>
                        <TextInput
                            id="e-mail"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            :invalid="Boolean(localErrors.email || form.errors.email)"
                            autofocus
                            autocomplete="username"
                            @blur="validateField('email')"
                        />
                        <InputError class="mt-1" :message="localErrors.email || form.errors.email" />
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex gap-4">
                    <IconButton class="my-2" :icon="MapPin" />
                    <SmallHeaderTitle title="Endereço" subtitle="Preencha os dados do endereço" />
                </div>
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <InputLabel for="zip_code">CEP</InputLabel>
                        <TextInput
                            id="zip_code"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.zip_code"
                            :invalid="Boolean(localErrors.zip_code || form.errors.zip_code)"
                            autofocus
                            autocomplete="username"
                            placeholder="00000-000"
                            @input="form.zip_code = formatCep($event.target.value)"
                            @blur="validateField('zip_code')"
                        />
                        <InputError class="mt-1" :message="localErrors.zip_code || form.errors.zip_code" />
                    </div>
                    <div class="form-group">
                        <InputLabel for="street">Logradouro</InputLabel>
                        <TextInput
                            id="street"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.street"
                            :invalid="Boolean(localErrors.street || form.errors.street)"
                            autofocus
                            autocomplete="username"
                            maxlength="255"
                            placeholder="Logradouro"
                            @blur="validateField('street')"
                        />
                        <InputError class="mt-1" :message="localErrors.street || form.errors.street" />
                    </div>
                    <div class="form-group">
                        <InputLabel for="number">Número</InputLabel>
                        <TextInput
                            id="number"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.number"
                            :invalid="Boolean(localErrors.number || form.errors.number)"
                            autofocus
                            autocomplete="username"
                            maxlength="255"
                            placeholder="Número"
                            @blur="validateField('number')"
                        />
                        <InputError class="mt-1" :message="localErrors.number || form.errors.number" />
                    </div>
                    <div class="form-group">
                        <InputLabel for="city">Cidade</InputLabel>
                        <TextInput
                            id="city"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.city"
                            :invalid="Boolean(localErrors.city || form.errors.city)"
                            autofocus
                            autocomplete="username"
                            maxlength="255"
                            placeholder="Cidade"
                            @blur="validateField('city')"
                        />
                        <InputError class="mt-1" :message="localErrors.city || form.errors.city" />
                    </div>
                    <div class="form-group">
                        <InputLabel for="state">Estado</InputLabel>
                        <TextInput
                            id="state"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.state"
                            :invalid="Boolean(localErrors.state || form.errors.state)"
                            autofocus
                            autocomplete="username"
                            maxlength="255"
                            placeholder="Estado"
                            @blur="validateField('state')"
                        />
                        <InputError class="mt-1" :message="localErrors.state || form.errors.state" />
                    </div>
                </div>
            </div>
            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex gap-4">
                    <IconButton class="my-2" :icon="Cog" />
                    <SmallHeaderTitle title="Gestão" subtitle="Definições adicionais" />
                </div>
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <InputLabel for="accountant">Contador Responsável</InputLabel>
                        <FormSelect
                            id="accountant"
                            v-model="form.accountant_id"
                            :options="accountants"
                            :invalid="Boolean(localErrors.accountant_id || form.errors.accountant_id)"
                            autofocus
                            autocomplete="username"
                            @blur="validateField('accountant_id')"
                        />
                        <InputError class="mt-1" :message="localErrors.accountant_id || form.errors.accountant_id" />
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex justify-between gap-4">
                    <IconTextButton class="my-2 border-red-500" iconClass="text-red-500" :icon="X">
                        <span class="mt-1 text-red-500">Cancelar</span>
                    </IconTextButton>
                    <IconTextButton type="submit" class="my-2 bg-brand-accent" iconClass="text-white" :icon="Save">
                        <span class="mt-1 text-white">Salvar</span>
                    </IconTextButton>
                </div>
            </div>
        </form>
    </AuthenticatedLayout>
</template>