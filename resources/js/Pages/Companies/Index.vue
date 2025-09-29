<script setup>
    import HeaderTitle from '@/Components/HeaderTitle.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import SimpleIconCard from '@/Components/SimpleIconCard.vue';
    import { Building2, UserRoundCheck, UserRoundX, Funnel, Ellipsis } from 'lucide-vue-next';
    import SearchInput from '@/Components/SearchInput.vue';
    import IconTextButton from '@/Components/IconTextButton.vue';
    import Table from '@/Components/Table.vue';
    import IconButton from '@/Components/IconButton.vue';
    defineProps({
        companies: Object,
    });
</script>

<template>
    <Head title="Empresas" />

    <AuthenticatedLayout>
        <HeaderTitle title="Empresas" subtitle="Gerencie suas empresas clientes" />
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
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Nome</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">CNPJ</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Regime Tributário</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Status</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Responsável</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Ações</th>
                </tr>
            </template>
            <template #body>
                <tr v-for="company in companies.data" :key="company.id">
                    <td class="p-4 align-middle whitespace-nowrap text-sm">{{ company.name }}</td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">{{ company.cnpj }}</td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">
                        <span v-if="company.tax_regime === 'simples_nacional'" :class="company.tax_regime === 'simples_nacional' ? 'bg-[#F3E8FF] text-[#6e11b0] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ company.tax_regime_label }}</span>
                        <span v-else-if="company.tax_regime === 'lucro_presumido'" :class="company.tax_regime === 'lucro_presumido' ? 'bg-[#F3E8FF] text-[#6e11b0] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ company.tax_regime_label }}</span>
                        <span v-else-if="company.tax_regime === 'lucro_real'" :class="company.tax_regime === 'lucro_real' ? 'bg-[#F3E8FF] text-[#6e11b0] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ company.tax_regime_label }}</span>
                    </td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">
                        <span :class="company.is_active ? 'text-green-500' : 'text-red-500'">{{ company.is_active ? 'Ativa' : 'Inativa' }}</span>
                    </td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">
                        {{ company.accountant_name || '—' }}
                    </td>
                    <td class="p-4 align-middle whitespace-nowrap text-sm">
                        <IconButton :icon="Ellipsis" />
                    </td>
                </tr>
            </template>
        </Table>
    </AuthenticatedLayout>
</template>
