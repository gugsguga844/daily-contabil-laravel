<script setup>
import HeaderTitle from '@/Components/HeaderTitle.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Plus, Funnel, Ellipsis } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import IconTextButton from '@/Components/IconTextButton.vue';
import SearchInput from '@/Components/SearchInput.vue';
import Table from '@/Components/Table.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import IconButton from '@/Components/IconButton.vue';
import { computed, ref } from 'vue';
import UserFormModal from '@/Components/UserFormModal.vue';

const props = defineProps({
    users: Object,
    roles: Array,
});

function onCreate() {
    showCreateModal.value = true;
}

const search = ref('');
const showFilters = ref(false);
const rolesSelected = ref([]); // ['admin', 'user', 'office_owner']

const rolesOptions = [
    { value: 'admin', label: 'Admin' },
    { value: 'worker', label: 'Usuário' },
    { value: 'office_owner', label: 'Proprietário' },
];

const filteredUsers = computed(() => {
    const term = search.value.trim().toLowerCase();
    const source = (props.users?.data || []);
    return source.filter(u => {
        const name = String(u.name || '').toLowerCase();
        const email = String(u.email || '').toLowerCase();
        const role = String(u.role_value || '').toLowerCase();

        const matchesSearch = term === '' || name.includes(term) || email.includes(term);
        const matchesRole = rolesSelected.value.length === 0 || rolesSelected.value.includes(role);
        return matchesSearch && matchesRole;
    });
});

// Modal state
const showCreateModal = ref(false);
function onUserCreated() {
    router.reload({ only: ['users'] });
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <HeaderTitle title="Usuários" subtitle="Gerencie os usuários do sistema" />
            <div class="flex gap-4 py-2">
                <PrimaryButton :icon="Plus" @click="onCreate">
                    <span class="mt-1">Novo Usuário</span>
                </PrimaryButton>
            </div>
        </div>

        <div class="flex gap-4 mt-8 mb-8 relative">
            <div class="flex-1">
                <SearchInput v-model="search" placeholder="Buscar usuário" />
            </div>
            <IconTextButton :icon="Funnel" @click="showFilters = !showFilters">
                Filtros
            </IconTextButton>

            <!-- Filters dropdown -->
            <div v-if="showFilters" class="absolute right-0 top-full mt-2 z-10 w-full md:w-auto">
                <div class="bg-white border rounded-lg shadow-lg p-4 md:min-w-[420px]">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-semibold text-sm text-text-primary mb-2">Papel</h4>
                            <div class="space-y-2">
                                <label v-for="opt in rolesOptions" :key="opt.value" class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" :value="opt.value" v-model="rolesSelected" class="rounded border-gray-300 text-brand-accent focus:ring-brand-accent" />
                                    <span>{{ opt.label }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" class="text-sm text-text-secondary" @click="rolesSelected = []">Limpar</button>
                        <button type="button" class="text-sm text-white bg-brand-accent px-3 py-1.5 rounded" @click="showFilters = false">Aplicar</button>
                    </div>
                </div>
            </div>
        </div>

        <Table>
            <template #header>
                <tr class="text-left text-xs">
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Nome</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Email</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Papel</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Ações</th>
                </tr>
            </template>
            <template #body>
                <tr
                    v-for="user in filteredUsers"
                    :key="user.id"
                    class="hover:bg-surface cursor-pointer"
                    role="button"
                    tabindex="0"
                    @click="onView(user.id)"
                    @keydown.enter.prevent="onView(user.id)"
                    @keydown.space.prevent="onView(user.id)"
                >
                    <td class="p-4 align-middle whitespace-nowrap text-sm">{{ user.name }}</td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">{{ user.email }}</td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">
                        <span v-if="user.role_value === 'admin'" :class="user.role_value === 'admin' ? 'bg-[#E8F5E9] text-[#166534] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ user.role_label }}</span>
                        <span v-else-if="user.role_value === 'worker'" :class="user.role_value === 'worker' ? 'bg-[#FFF4E5] text-[#B45309] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ user.role_label }}</span>
                        <span v-else-if="user.role_value === 'office_owner'" :class="user.role_value === 'office_owner' ? 'bg-[#E6F0FF] text-[#1D4ED8] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ user.role_label }}</span>
                    </td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm" @click.stop>
                        <Dropdown>
                            <template #trigger>
                                <IconButton :icon="Ellipsis" />
                            </template>
                            <template #content>
                                <DropdownLink as="button" type="button" @click.stop="onView(user.id)">Ver</DropdownLink>
                                <DropdownLink as="button" type="button" @click.stop="onView(user.id)">VerTemporariamente</DropdownLink>
                            </template>
                        </Dropdown>
                    </td>
                </tr>
            </template>
        </Table>

        <UserFormModal :show="showCreateModal" :roles="roles" @close="showCreateModal = false" @created="onUserCreated" />
    </AuthenticatedLayout>
</template>
