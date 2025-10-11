<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import { Head } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ArrowLeft, BookOpen, Building2, Download, MapPin, Phone, Play, Plus } from 'lucide-vue-next';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ContentManagerModal from '@/Components/ContentManagerModal.vue';
import { ref, computed } from 'vue';
import ContentTypeIcon from '@/Components/ContentTypeIcon.vue';
import { useFormatters } from '@/Composables/useFormatters';

const { formatDate } = useFormatters();

const props = defineProps({
    company: Object,
    libraryContents: Array,
});

const attachedContentIds = computed(() => {
    if (!props.company?.contents) return [];
    return props.company.contents.map(content => content.id);
});

function onBack() {
    window.history.back();
}

const isModalVisible = ref(false);
</script>

<template>
    <Head title="Empresa" />
    <AuthenticatedLayout>
        <div class="flex gap-4">
            <div class="py-2">
                <SecondaryButton @click="onBack" :icon="ArrowLeft">
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
                        <p>{{ company.accountant_name || '__' }}</p>
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

        <div class="p-6 bg-white shadow-md rounded-lg mt-6">
            <div class="flex justify-between mb-4">
                <div class="flex items-center gap-4">
                    <BookOpen class="w-6 h-6" />
                    <h2 class="font-semibold">Conteúdo</h2>
                </div>
                <PrimaryButton :icon="Plus" @click="isModalVisible = true">
                    <span class="mt-1">Adicionar</span>
                </PrimaryButton>
            </div>

            <div v-if="company.contents.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="content in company.contents" :key="content.id" class="p-6 flex gap-4 bg-white shadow-md rounded-lg">
                    <div class="flex items-center gap-4">
                        <ContentTypeIcon :type="content.type" size="w-8 h-8" />
                    </div>
                    <div class="flex justify-between items-center w-full">
                        <div class="flex-grow">
                            <h3 class="text-text-primary font-semibold">{{ content.title }}</h3>
                            <p class="text-text-secondary text-sm">{{ formatDate(content.created_at) }}</p>
                        </div>
                        <div class="flex gap-2">
                            <Download class="w-6 h-6" />
                            <Play v-if="content.type === 'video'" class="w-6 h-6" />
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <p>Nenhum conteúdo adicionado.</p>
            </div>
        </div>
    </AuthenticatedLayout>
    <ContentManagerModal
        modalTitle="Gerenciar Conteúdo"
        modalDescription="Gerencie o conteúdo da empresa"
        :attachment-url="route('companies.contents.store', { company: company.id })"
        :show="isModalVisible"
        @close="isModalVisible = false"
        :contents="libraryContents"
        :contentable-id="company.id"
        :contentable-type="'App\\Models\\Company'"
        :initial-selected-ids="attachedContentIds"
    />
</template>
