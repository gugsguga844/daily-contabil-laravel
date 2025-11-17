<script setup>
import HeaderTitle from '@/Components/HeaderTitle.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import SimpleIconCard from '@/Components/SimpleIconCard.vue';
import { Building2, UserRoundCheck, UserRoundX, Funnel, Ellipsis, Plus, Upload } from 'lucide-vue-next';
import SearchInput from '@/Components/SearchInput.vue';
import IconTextButton from '@/Components/IconTextButton.vue';
import Table from '@/Components/Table.vue';
import IconButton from '@/Components/IconButton.vue';
import Paginator from '@/Components/Paginator.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { computed, ref } from 'vue';

const props = defineProps({
    companies: Object,
});

function onView(id) {
    router.visit(route('companies.show', id));
}

function onDelete(id) {
    if (!confirm('Tem certeza que deseja excluir esta empresa? Esta ação não pode ser desfeita.')) {
        return;
    }
    router.delete(route('companies.destroy', id));
}

function onCreate() {
    router.get(route('companies.create'));
}

// Filters state
const search = ref('');
const showFilters = ref(false);
const regimesSelected = ref([]); // ['simples_nacional', 'lucro_presumido', 'lucro_real']
const statusSelected = ref([]);  // ['active', 'inactive']

const regimeOptions = [
    { value: 'simples_nacional', label: 'Simples Nacional' },
    { value: 'lucro_presumido', label: 'Lucro Presumido' },
    { value: 'lucro_real', label: 'Lucro Real' },
];

const statusOptions = [
    { value: 'active', label: 'Ativa' },
    { value: 'inactive', label: 'Inativa' },
];

const filteredCompanies = computed(() => {
    const term = search.value.trim().toLowerCase();
    const source = (props.companies?.data || []);
    return source.filter(c => {
        const name = String(c.name || '').toLowerCase();
        const cnpj = String(c.cnpj || '').toLowerCase();
        const accountant = String(c.accountant_name || '').toLowerCase();
        const regime = String(c.tax_regime || '').toLowerCase();
        const isActive = !!c.is_active;

        const matchesSearch = term === '' || name.includes(term) || cnpj.includes(term) || accountant.includes(term);
        const matchesRegime = regimesSelected.value.length === 0 || regimesSelected.value.includes(regime);
        const matchesStatus = statusSelected.value.length === 0 || statusSelected.value.includes(isActive ? 'active' : 'inactive');
        return matchesSearch && matchesRegime && matchesStatus;
    });
});
</script>

<template>
    <Head title="Empresas" />

    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <HeaderTitle title="Empresas" subtitle="Gerencie suas empresas clientes" />
            <div class="flex gap-4 py-2">
                <SecondaryButton :icon="Upload">
                    <span class="mt-1">Importar lista CSV</span>
                </SecondaryButton>
                <PrimaryButton :icon="Plus" @click="onCreate">
                    <span class="mt-1">Nova Empresa</span>
                </PrimaryButton>
            </div>
        </div>
        <div class="flex gap-4">
            <SimpleIconCard title="Total de empresas" subtitle="10" :icon="Building2" />
            <SimpleIconCard title="Empresas ativas" subtitle="10" :icon="UserRoundCheck" />
            <SimpleIconCard title="Empresas inativas" subtitle="10" :icon="UserRoundX" />
        </div>

        <div class="flex gap-4 mt-8 mb-8 relative">
            <div class="flex-1">
                <SearchInput v-model="search" placeholder="Buscar empresa" />
            </div>
            <IconTextButton :icon="Funnel" @click="showFilters = !showFilters">
                Filtros
            </IconTextButton>

            <!-- Filters dropdown -->
            <div v-if="showFilters" class="absolute right-0 top-full mt-2 z-10 w-full md:w-auto">
                <div class="bg-white border rounded-lg shadow-lg p-4 md:min-w-[420px]">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-semibold text-sm text-text-primary mb-2">Regime Tributário</h4>
                            <div class="space-y-2">
                                <label v-for="opt in regimeOptions" :key="opt.value" class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" :value="opt.value" v-model="regimesSelected" class="rounded border-gray-300 text-brand-accent focus:ring-brand-accent" />
                                    <span>{{ opt.label }}</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm text-text-primary mb-2">Status</h4>
                            <div class="space-y-2">
                                <label v-for="opt in statusOptions" :key="opt.value" class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" :value="opt.value" v-model="statusSelected" class="rounded border-gray-300 text-brand-accent focus:ring-brand-accent" />
                                    <span>{{ opt.label }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" class="text-sm text-text-secondary" @click="regimesSelected = []; statusSelected = []">Limpar</button>
                        <button type="button" class="text-sm text-white bg-brand-accent px-3 py-1.5 rounded" @click="showFilters = false">Aplicar</button>
                    </div>
                </div>
            </div>
        </div>

        <Table>
            <template #header>
                <tr class="text-left text-xs">
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Razão Social</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">CNPJ</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Regime Tributário</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Status</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Responsável</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Ações</th>
                </tr>
            </template>
            <template #body>
                <tr
                    v-for="company in filteredCompanies"
                    :key="company.id"
                    class="hover:bg-surface cursor-pointer"
                    role="button"
                    tabindex="0"
                    @click="onView(company.id)"
                    @keydown.enter.prevent="onView(company.id)"
                    @keydown.space.prevent="onView(company.id)"
                >
                    <td class="p-4 align-middle whitespace-nowrap text-sm">{{ company.name }}</td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">{{ company.cnpj }}</td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">
                        <span v-if="company.tax_regime === 'simples_nacional'" :class="company.tax_regime === 'simples_nacional' ? 'bg-[#E8F5E9] text-[#166534] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ company.tax_regime_label }}</span>
                        <span v-else-if="company.tax_regime === 'lucro_presumido'" :class="company.tax_regime === 'lucro_presumido' ? 'bg-[#FFF4E5] text-[#B45309] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ company.tax_regime_label }}</span>
                        <span v-else-if="company.tax_regime === 'lucro_real'" :class="company.tax_regime === 'lucro_real' ? 'bg-[#E6F0FF] text-[#1D4ED8] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ company.tax_regime_label }}</span>
                    </td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">
                        <span :class="company.is_active ? 'bg-[#DBFCE7] text-[#10B981] font-semibold px-2 py-1 rounded' : 'bg-[#FFE2E2] text-[#EF4444] font-semibold px-2 py-1 rounded'">{{ company.is_active ? 'Ativa' : 'Inativa' }}</span>
                    </td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">
                        {{ company.accountant_name || '—' }}
                    </td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm" @click.stop>
                        <Dropdown>
                            <template #trigger>
                                <IconButton :icon="Ellipsis" />
                            </template>
                            <template #content>
                                <DropdownLink as="button" type="button" @click.stop="onView(company.id)">Editar</DropdownLink>
                                <DropdownLink as="button" type="button" class="text-red-600" @click.stop="onDelete(company.id)">Excluir</DropdownLink>
                            </template>
                        </Dropdown>
                    </td>
                </tr>
            </template>
        </Table>
        <Paginator :links="companies.links" />
    </AuthenticatedLayout>
</template>
