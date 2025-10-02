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

defineProps({
    companies: Object,
});

function onView(id) {
    router.visit(route('companies.show', id));
}
</script>

<template>
    <Head title="Empresas" />

    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <HeaderTitle title="Empresas" subtitle="Gerencie suas empresas clientes" />
            <div class="flex gap-4">
                <SecondaryButton :icon="Upload">
                    Importar lista CSV
                </SecondaryButton>
                <PrimaryButton :icon="Plus">
                    Nova Empresa
                </PrimaryButton>
            </div>
        </div>
        <div class="flex gap-4">
            <SimpleIconCard title="Total de empresas" subtitle="10" :icon="Building2" />
            <SimpleIconCard title="Empresas ativas" subtitle="10" :icon="UserRoundCheck" />
            <SimpleIconCard title="Empresas inativas" subtitle="10" :icon="UserRoundX" />
        </div>

        <div class="flex gap-4 mt-8 mb-8">
            <SearchInput placeholder="Buscar empresa" />
            <IconTextButton :icon="Funnel">
                Filtros
            </IconTextButton>
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
                    v-for="company in companies.data"
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
                        <span v-if="company.tax_regime === 'simples_nacional'" :class="company.tax_regime === 'simples_nacional' ? 'bg-[#F3E8FF] text-[#6e11b0] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ company.tax_regime_label }}</span>
                        <span v-else-if="company.tax_regime === 'lucro_presumido'" :class="company.tax_regime === 'lucro_presumido' ? 'bg-[#F3E8FF] text-[#6e11b0] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ company.tax_regime_label }}</span>
                        <span v-else-if="company.tax_regime === 'lucro_real'" :class="company.tax_regime === 'lucro_real' ? 'bg-[#F3E8FF] text-[#6e11b0] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ company.tax_regime_label }}</span>
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
                                <DropdownLink as="button" type="button" @click.stop="onView(company.id)">Ver</DropdownLink>
                                <DropdownLink as="button" type="button" @click.stop="onView(company.id)">VerTemporariamente</DropdownLink>
                            </template>
                        </Dropdown>
                    </td>
                </tr>
            </template>
        </Table>
        <Paginator :links="companies.links" />
    </AuthenticatedLayout>
</template>
