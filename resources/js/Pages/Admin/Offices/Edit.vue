<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';

const props = defineProps({
    office: Object,
});

const form = useForm({
    name: props.office.name || '',
    fantasy_name: props.office.fantasy_name || '',
    cnpj: props.office.cnpj || '',
    phone: props.office.phone || '',
    email: props.office.email || '',
    street: props.office.street || '',
    number: props.office.number || '',
    city: props.office.city || '',
    state: props.office.state || '',
    zip_code: props.office.zip_code || '',
    is_active: !!props.office.is_active,
    current_plan: props.office.current_plan || '',
});

function submit() {
    form.put(route('admin.offices.update', props.office.id));
}
</script>

<template>
    <Head title="Editar Escritório" />

    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <div class="flex gap-4">
                <Link :href="route('admin.offices.show', office.id)">
                    <SecondaryButton :icon="ArrowLeft">
                        <span class="mt-1">Voltar</span>
                    </SecondaryButton>
                </Link>
                <HeaderTitle title="Editar Escritório" subtitle="Atualize os dados do escritório" />
            </div>

            <div class="flex gap-2 py-2">
                <PrimaryButton :icon="Save" @click="submit" :disabled="form.processing">
                    <span class="mt-1">Salvar</span>
                </PrimaryButton>
            </div>
        </div>

        <form @submit.prevent="submit" class="bg-white shadow-sm rounded-lg border p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Nome*</label>
                <input v-model="form.name" type="text" class="w-full border rounded p-2" />
                <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nome Fantasia</label>
                <input v-model="form.fantasy_name" type="text" class="w-full border rounded p-2" />
                <div v-if="form.errors.fantasy_name" class="text-red-600 text-sm mt-1">{{ form.errors.fantasy_name }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">CNPJ*</label>
                <input v-model="form.cnpj" type="text" class="w-full border rounded p-2" />
                <div v-if="form.errors.cnpj" class="text-red-600 text-sm mt-1">{{ form.errors.cnpj }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Plano Atual</label>
                <input v-model="form.current_plan" type="text" class="w-full border rounded p-2" />
                <div v-if="form.errors.current_plan" class="text-red-600 text-sm mt-1">{{ form.errors.current_plan }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input v-model="form.email" type="email" class="w-full border rounded p-2" />
                <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Telefone</label>
                <input v-model="form.phone" type="text" class="w-full border rounded p-2" />
                <div v-if="form.errors.phone" class="text-red-600 text-sm mt-1">{{ form.errors.phone }}</div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Rua</label>
                <input v-model="form.street" type="text" class="w-full border rounded p-2" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Número</label>
                <input v-model="form.number" type="text" class="w-full border rounded p-2" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Cidade</label>
                <input v-model="form.city" type="text" class="w-full border rounded p-2" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Estado</label>
                <input v-model="form.state" type="text" class="w-full border rounded p-2" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">CEP</label>
                <input v-model="form.zip_code" type="text" class="w-full border rounded p-2" />
            </div>

            <div class="flex items-center gap-2">
                <input id="is_active" v-model="form.is_active" type="checkbox" class="h-4 w-4" />
                <label for="is_active" class="text-sm">Escritório Ativo</label>
            </div>

            <div class="md:col-span-2 flex justify-end gap-3 pt-2">
                <Link :href="route('admin.offices.show', office.id)" class="px-4 py-2 border rounded">Cancelar</Link>
                <button type="submit" class="px-4 py-2 bg-surface-accent text-white rounded" :disabled="form.processing">
                    {{ form.processing ? 'Salvando...' : 'Salvar' }}
                </button>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
