<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';

const props = defineProps({
    office: Object,
    user: Object,
    roles: Array,
});

const form = useForm({
    name: props.user.name || '',
    email: props.user.email || '',
    role: props.user.role || 'worker',
    password: '',
});

function submit() {
    form.put(route('admin.offices.users.update', { office: props.office.id, user: props.user.id }));
}
</script>

<template>
    <Head title="Editar Usuário" />

    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <div class="flex gap-4">
                <Link :href="route('admin.offices.show', office.id)">
                    <SecondaryButton :icon="ArrowLeft">
                        <span class="mt-1">Voltar</span>
                    </SecondaryButton>
                </Link>
                <HeaderTitle :title="`Editar usuário (${office.name})`" subtitle="Atualize os dados do usuário do escritório" />
            </div>

            <div class="flex gap-2 py-2">
                <PrimaryButton :icon="Save" @click="submit" :disabled="form.processing">
                    <span class="mt-1">Salvar</span>
                </PrimaryButton>
            </div>
        </div>

        <form @submit.prevent="submit" class="bg-white shadow-sm rounded-lg border p-6 max-w-3xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nome*</label>
                    <input v-model="form.name" type="text" class="w-full border rounded p-2" />
                    <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Email*</label>
                    <input v-model="form.email" type="email" class="w-full border rounded p-2" />
                    <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Função*</label>
                    <select v-model="form.role" class="w-full border rounded p-2">
                        <option v-for="r in roles" :key="r.value" :value="r.value">{{ r.label }}</option>
                    </select>
                    <div v-if="form.errors.role" class="text-red-600 text-sm mt-1">{{ form.errors.role }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Nova senha (opcional)</label>
                    <input v-model="form.password" type="password" class="w-full border rounded p-2" autocomplete="new-password" />
                    <div v-if="form.errors.password" class="text-red-600 text-sm mt-1">{{ form.errors.password }}</div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <Link :href="route('admin.offices.show', office.id)" class="px-4 py-2 border rounded">Cancelar</Link>
                <button type="submit" class="px-4 py-2 bg-surface-accent text-white rounded" :disabled="form.processing">
                    {{ form.processing ? 'Salvando...' : 'Salvar' }}
                </button>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
