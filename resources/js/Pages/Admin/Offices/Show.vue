<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Table from '@/Components/Table.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { ArrowLeft, Pencil, Trash2, UserCog } from 'lucide-vue-next';

const props = defineProps({
    office: Object,
    users: Array,
});

const showDeleteOfficeModal = ref(false);
const deletingOfficeLoading = ref(false);

function confirmDeleteOffice() {
    deletingOfficeLoading.value = true;
    router.delete(route('admin.offices.destroy', props.office.id), {
        onFinish: () => {
            deletingOfficeLoading.value = false;
            showDeleteOfficeModal.value = false;
        },
    });
}

const showDeleteUserModal = ref(false);
const deletingUserLoading = ref(false);
const deletingUserId = ref(null);

function requestDeleteUser(userId) {
    deletingUserId.value = userId;
    showDeleteUserModal.value = true;
}

function confirmDeleteUser() {
    if (!deletingUserId.value) return;
    deletingUserLoading.value = true;

    router.delete(route('admin.offices.users.destroy', { office: props.office.id, user: deletingUserId.value }), {
        onFinish: () => {
            deletingUserLoading.value = false;
            showDeleteUserModal.value = false;
            deletingUserId.value = null;
        },
    });
}
</script>

<template>
    <Head title="Escritório" />

    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <div class="flex gap-4">
                <Link :href="route('admin.offices.index')">
                    <SecondaryButton :icon="ArrowLeft">
                        <span class="mt-1">Voltar</span>
                    </SecondaryButton>
                </Link>
                <HeaderTitle :title="office.name" subtitle="Detalhes do escritório e usuários" />
            </div>

            <div class="flex gap-2 py-2">
                <Link :href="route('admin.offices.edit', office.id)">
                    <PrimaryButton :icon="Pencil">
                        <span class="mt-1">Editar</span>
                    </PrimaryButton>
                </Link>
                <SecondaryButton :icon="Trash2" @click="showDeleteOfficeModal = true">
                    <span class="mt-1">Excluir</span>
                </SecondaryButton>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg border p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <div class="text-text-secondary">CNPJ</div>
                    <div class="font-semibold">{{ office.cnpj }}</div>
                </div>
                <div>
                    <div class="text-text-secondary">Plano</div>
                    <div class="font-semibold">{{ office.current_plan || '—' }}</div>
                </div>
                <div>
                    <div class="text-text-secondary">Status</div>
                    <div>
                        <span :class="office.is_active ? 'bg-[#DBFCE7] text-[#10B981] font-semibold px-2 py-1 rounded' : 'bg-[#FFE2E2] text-[#EF4444] font-semibold px-2 py-1 rounded'">
                            {{ office.is_active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>
                <div>
                    <div class="text-text-secondary">Email</div>
                    <div class="font-semibold">{{ office.email || '—' }}</div>
                </div>
                <div>
                    <div class="text-text-secondary">Telefone</div>
                    <div class="font-semibold">{{ office.phone || '—' }}</div>
                </div>
                <div>
                    <div class="text-text-secondary">Local</div>
                    <div class="font-semibold">{{ [office.city, office.state].filter(Boolean).join(' / ') || '—' }}</div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-text-primary">Usuários</h2>
        </div>

        <Table>
            <template #header>
                <tr class="text-left text-xs">
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Nome</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Email</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Role</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Ações</th>
                </tr>
            </template>

            <template #body>
                <tr v-for="u in users" :key="u.id" class="hover:bg-surface">
                    <td class="p-4 align-middle whitespace-nowrap text-sm font-semibold">{{ u.name }}</td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">{{ u.email }}</td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">{{ u.role || '—' }}</td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">
                        <div class="flex gap-2">
                            <Link :href="route('admin.offices.users.edit', { office: office.id, user: u.id })">
                                <SecondaryButton :icon="UserCog">Editar</SecondaryButton>
                            </Link>
                            <SecondaryButton :icon="Trash2" @click="requestDeleteUser(u.id)">Excluir</SecondaryButton>
                        </div>
                    </td>
                </tr>
            </template>
        </Table>

        <ConfirmModal
            v-model:open="showDeleteOfficeModal"
            :danger="true"
            title="Excluir escritório"
            message="Tem certeza que deseja excluir este escritório? Todos os usuários dele também serão removidos."
            confirmLabel="Excluir"
            cancelLabel="Cancelar"
            :loading="deletingOfficeLoading"
            @confirm="confirmDeleteOffice"
        />

        <ConfirmModal
            v-model:open="showDeleteUserModal"
            :danger="true"
            title="Excluir usuário"
            message="Tem certeza que deseja excluir este usuário?"
            confirmLabel="Excluir"
            cancelLabel="Cancelar"
            :loading="deletingUserLoading"
            @confirm="confirmDeleteUser"
        />
    </AuthenticatedLayout>
</template>
