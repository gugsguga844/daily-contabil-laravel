<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ArrowLeft, Building2 } from 'lucide-vue-next';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import IconButton from '@/Components/IconButton.vue';
import SmallHeaderTitle from '@/Components/SmallHeaderTitle.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import FormSelect from '@/Components/FormSelect.vue';

const form = useForm({
    name: '',
    email: '',
    cnpj: '',
    tax_regime: '',
    phone: '',
})

function formatCnpj(cnpj) {
    return cnpj.replace(/\D/g, '').replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
}

function formatPhone(phone) {
    return phone.replace(/\D/g, '').replace(/(\d{2})(\d{5})(\d{4})/, '$1 $2-$3');
}

function onSubmit() {
    form.post(route('companies.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
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

        <form @submit.prevent="onSubmit" class="mt-6 flex flex-col gap-6">
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
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </div>
                    <div class="form-group">
                        <InputLabel for="name">Nome Fantasia</InputLabel>
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </div>
                    <div class="form-group">
                        <InputLabel for="cnpj">CNPJ</InputLabel>
                        <TextInput
                            id="cnpj"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.cnpj"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="00.000.000/0000-00"
                            maxlength="14"
                            @input="form.cnpj = formatCnpj($event.target.value)"
                        />
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
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </div>
                    <div class="form-group">
                        <InputLabel for="phone">Telefone</InputLabel>
                        <TextInput
                            id="phone"
                            type="tel"
                            class="mt-1 block w-full"
                            v-model="form.phone"
                            required
                            autofocus
                            autocomplete="username"
                            maxlength="11"
                            placeholder="(00) 00000-0000"
                            @input="form.phone = formatPhone($event.target.value)"
                        />
                    </div>
                    <div class="form-group">
                        <InputLabel for="e-mail">E-mail</InputLabel>
                        <TextInput
                            id="e-mail"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex gap-4">
                    <IconButton class="my-2" :icon="Building2" />
                    <SmallHeaderTitle title="Endereço" subtitle="Preencha os dados do endereço" />
                </div>
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <InputLabel for="cep">CEP</InputLabel>
                        <TextInput
                            id="cep"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.cep"
                            required
                            autofocus
                            autocomplete="username"
                            maxlength="8"
                            placeholder="00000-000"
                            @input="form.cep = formatCep($event.target.value)"
                        />
                    </div>
                    <div class="form-group">
                        <InputLabel for="logradouro">Logradouro</InputLabel>
                        <TextInput
                            id="logradouro"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.logradouro"
                            required
                            autofocus
                            autocomplete="username"
                            maxlength="255"
                            placeholder="Logradouro"
                        />
                    </div>
                    <div class="form-group">
                        <InputLabel for="numero">Número</InputLabel>
                        <TextInput
                            id="numero"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.numero"
                            required
                            autofocus
                            autocomplete="username"
                            maxlength="255"
                            placeholder="Número"
                        />
                    </div>
                    <div class="form-group">
                        <InputLabel for="cidade">Cidade</InputLabel>
                        <TextInput
                            id="cidade"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.cidade"
                            required
                            autofocus
                            autocomplete="username"
                            maxlength="255"
                            placeholder="Cidade"
                        />
                    </div>
                    <div class="form-group">
                        <InputLabel for="estado">Estado</InputLabel>
                        <TextInput
                            id="estado"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.estado"
                            required
                            autofocus
                            autocomplete="username"
                            maxlength="255"
                            placeholder="Estado"
                        />
                    </div>
                </div>
            </div>
        </form>
    </AuthenticatedLayout>
</template>