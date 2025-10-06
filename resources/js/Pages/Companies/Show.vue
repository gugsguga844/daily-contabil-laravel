<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import { Head } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ArrowLeft, Building2, Cog, MapPin, Phone } from 'lucide-vue-next';
import IconTextButton from '@/Components/IconTextButton.vue';

const props = defineProps({
    company: Object,
});
</script>

<template>
    <Head title="Empresa" />
    <AuthenticatedLayout>
        <div class="flex gap-4">
            <div class="py-2">
                <SecondaryButton :icon="ArrowLeft">
                    <span class="mt-1">Voltar</span>
                </SecondaryButton>
            </div>
            <HeaderTitle title="Empresa" subtitle="Detalhes da empresa" />
        </div>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <div>
                <div class="p-6 h-full flex flex-col gap-4 bg-white shadow-md rounded-lg">
                    <div class="flex items-center gap-4 mb-4">
                        <Building2 class="w-6 h-6" />
                        <h2 class="font-semibold">Dados Cadastrais</h2>
                    </div>
                    <div class="info-group">
                        <h3 class="text-text-secondary font-semibold">Razão Social</h3>
                        <p>{{ company.name }}</p>
                    </div>
                    <div class="info-group">
                        <h3 class="text-text-secondary font-semibold">Nome Fantasia</h3>
                        <p>{{ company.fantasy_name }}</p>
                    </div>
                    <div class="info-group">
                        <h3 class="text-text-secondary font-semibold">CNPJ</h3>
                        <p>{{ company.cnpj }}</p>
                    </div>
                    <div class="info-group">
                        <h3 class="text-text-secondary font-semibold">Regime Tributário</h3>
                        <span v-if="company.tax_regime === 'simples_nacional'" :class="company.tax_regime === 'simples_nacional' ? 'bg-[#F3E8FF] text-[#6e11b0] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ company.tax_regime_label }}</span>
                        <span v-else-if="company.tax_regime === 'lucro_presumido'" :class="company.tax_regime === 'lucro_presumido' ? 'bg-[#F3E8FF] text-[#6e11b0] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ company.tax_regime_label }}</span>
                        <span v-else-if="company.tax_regime === 'lucro_real'" :class="company.tax_regime === 'lucro_real' ? 'bg-[#F3E8FF] text-[#6e11b0] font-semibold px-2 py-1 rounded' : 'text-red-500'">{{ company.tax_regime_label }}</span>
                    </div>
                    <div class="info-group">
                        <h3 class="text-text-secondary font-semibold">Responsável</h3>
                        <p>{{ company.accountant_name }}</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-4">
                <div class="p-6 flex flex-col gap-4 bg-white shadow-md rounded-lg">
                    <div class="flex items-center gap-4 mb-4">
                        <Phone class="w-6 h-6" />
                        <h2 class="font-semibold">Contato</h2>
                    </div>
                    <div class="info-group">
                        <h3 class="text-text-secondary font-semibold">Telefone</h3>
                        <p>{{ company.phone }}</p>
                    </div>
                    <div class="info-group">
                        <h3 class="text-text-secondary font-semibold">Email</h3>
                        <p>{{ company.email }}</p>
                    </div>
                </div>
                <div class="p-6 flex flex-col gap-4 bg-white shadow-md rounded-lg">
                    <div class="flex items-center gap-4 mb-4">
                        <MapPin class="w-6 h-6" />
                        <h2 class="font-semibold">Endereço</h2>
                    </div>
                    <p>{{ company.state }}</p>
                    <p>{{ company.city }}</p>
                    <p>{{ company.zip_code }}</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
    