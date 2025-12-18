<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Table from '@/Components/Table.vue';
import Paginator from '@/Components/Paginator.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Plus, Eye, Pencil, Trash2 } from 'lucide-vue-next';

const props = defineProps({
    offices: Object,
});

const showDeleteModal = ref(false);
const deletingId = ref(null);
const deletingLoading = ref(false);

function requestDelete(id) {
    deletingId.value = id;
    showDeleteModal.value = true;
}

function confirmDelete() {
    if (!deletingId.value) return;
    deletingLoading.value = true;
    router.delete(route('admin.offices.destroy', deletingId.value), {
        onFinish: () => {
            deletingLoading.value = false;
            showDeleteModal.value = false;
            deletingId.value = null;
        },
    });
}

const officesData = computed(() => props.offices?.data || []);
</script>

<template>
    <Head title="Escritórios" />

    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <HeaderTitle title="Escritórios" subtitle="Visão geral de todos os escritórios (tenants)" />
            <div class="flex gap-4 py-2">
                <Link :href="route('admin.offices.create')">
                    <PrimaryButton :icon="Plus">
                        <span class="mt-1">Novo Escritório</span>
                    </PrimaryButton>
                </Link>
            </div>
        </div>

        <Table>
            <template #header>
                <tr class="text-left text-xs">
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Nome</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">CNPJ</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Plano</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Usuários</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Status</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Ações</th>
                </tr>
            </template>

            <template #body>
                <tr v-for="office in officesData" :key="office.id" class="hover:bg-surface">
                    <td class="p-4 align-middle whitespace-nowrap text-sm">
                        <div class="font-semibold text-text-primary">{{ office.name }}</div>
                        <div class="text-xs text-text-secondary">{{ office.fantasy_name || '—' }}</div>
                    </td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">{{ office.cnpj }}</td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">{{ office.current_plan || '—' }}</td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">{{ office.users_count ?? '—' }}</td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">
                        <span :class="office.is_active ? 'bg-[#DBFCE7] text-[#10B981] font-semibold px-2 py-1 rounded' : 'bg-[#FFE2E2] text-[#EF4444] font-semibold px-2 py-1 rounded'">
                            {{ office.is_active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">
                        <div class="flex gap-2">
                            <Link :href="route('admin.offices.show', office.id)">
                                <SecondaryButton :icon="Eye">Ver</SecondaryButton>
                            </Link>
                            <Link :href="route('admin.offices.edit', office.id)">
                                <SecondaryButton :icon="Pencil">Editar</SecondaryButton>
                            </Link>
                            <SecondaryButton :icon="Trash2" @click="requestDelete(office.id)">Excluir</SecondaryButton>
                        </div>
                    </td>
                </tr>
            </template>
        </Table>

        <div class="mt-6">
            <Paginator :links="offices.links" />
        </div>

        <ConfirmModal
            v-model:open="showDeleteModal"
            :danger="true"
            title="Excluir escritório"
            message="Tem certeza que deseja excluir este escritório? Todos os usuários dele também serão removidos."
            confirmLabel="Excluir"
            cancelLabel="Cancelar"
            :loading="deletingLoading"
            @confirm="confirmDelete"
        />
    </AuthenticatedLayout>
</template>
