<script setup>
import HeaderTitle from '@/Components/HeaderTitle.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import SimpleIconCard from '@/Components/SimpleIconCard.vue';
import { Building2, UserRoundCheck, UserRoundX, Funnel, Ellipsis, Plus, Upload, Download } from 'lucide-vue-next';
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
import ConfirmModal from '@/Components/ConfirmModal.vue';

const props = defineProps({
    companies: Object,
    metrics: {
        type: Object,
        default: () => ({ total: 0, active: 0, inactive: 0 }),
    }
});

// Fallback metrics from paginator in case server metrics are missing or zeroed
const displayedMetrics = computed(() => {
    const srv = props.metrics || {};
    const metaTotal = props.companies?.meta?.total;
    const data = props.companies?.data || [];
    const total = (srv.total && srv.total > 0) ? srv.total : (typeof metaTotal === 'number' ? metaTotal : data.length);
    const active = (srv.active && srv.active >= 0) ? srv.active : data.filter(c => !!c.is_active).length;
    const inactive = (srv.inactive && srv.inactive >= 0) ? srv.inactive : Math.max(0, total - active);
    return { total, active, inactive };
});

function onView(id) {
    router.visit(route('companies.show', id));
}

// Modal de confirmação de exclusão
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
    router.delete(route('companies.destroy', deletingId.value), {
        onFinish: () => {
            deletingLoading.value = false;
            showDeleteModal.value = false;
            deletingId.value = null;
        },
    });
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

const page = usePage();
const importSummary = computed(() => page.props.flash?.importSummary);
const flashSuccess = computed(() => page.props.flash?.success);
const flashError = computed(() => page.props.flash?.error);

const fileInputRef = ref(null);
const importForm = useForm({
    file: null,
});

const showCnpjExcelWarning = ref(false);

try {
    showCnpjExcelWarning.value = localStorage.getItem('companies.csv.showCnpjWarning') === '1';
} catch {
    // ignore
}

function onDownloadTemplate() {
    showCnpjExcelWarning.value = true;
    try {
        localStorage.setItem('companies.csv.showCnpjWarning', '1');
    } catch {
        // ignore
    }
    window.location.href = route('companies.import.template');
}

function dismissCnpjWarning() {
    showCnpjExcelWarning.value = false;
    try {
        localStorage.removeItem('companies.csv.showCnpjWarning');
    } catch {
        // ignore
    }
}

function triggerImport() {
    fileInputRef.value?.click();
}

function onFileSelected(e) {
    const file = e?.target?.files?.[0];
    if (!file) return;

    importForm.file = file;
    importForm.post(route('companies.import'), {
        forceFormData: true,
        preserveScroll: true,
        onFinish: () => {
            importForm.reset('file');
            if (fileInputRef.value) fileInputRef.value.value = '';
        },
    });
}
</script>

<template>
    <Head title="Empresas" />

    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <HeaderTitle title="Empresas" subtitle="Gerencie suas empresas clientes" />
            <div class="flex gap-4 py-2">
                <SecondaryButton :icon="Download" @click="onDownloadTemplate">
                    <span class="mt-1">Baixar modelo CSV</span>
                </SecondaryButton>
                <SecondaryButton :icon="Upload" @click="triggerImport" :disabled="importForm.processing">
                    <span class="mt-1">Importar lista CSV</span>
                </SecondaryButton>
                <PrimaryButton :icon="Plus" @click="onCreate">
                    <span class="mt-1">Nova Empresa</span>
                </PrimaryButton>
            </div>
        </div>

        <input
            ref="fileInputRef"
            type="file"
            accept=".csv,text/csv"
            class="hidden"
            @change="onFileSelected"
        />

        <div v-if="showCnpjExcelWarning" class="mt-2 mb-4 bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-800 relative">
            <button type="button" class="absolute top-2 right-2 text-amber-700 hover:text-amber-900" @click="dismissCnpjWarning" aria-label="Fechar">
                X
            </button>
            <div class="font-semibold">Atenção ao CNPJ no Excel</div>
            <div class="mt-1">
                O Excel pode transformar CNPJs em notação científica (ex.: <span class="font-mono">8,16359E+13</span>) e isso perde dígitos.
                Para evitar:
            </div>
            <div class="mt-2">
                <div>1) Formate a coluna <strong>CNPJ</strong> como <strong>Texto</strong> antes de preencher</div>
                <div>2) Ou digite o CNPJ com apóstrofo no início: <span class="font-mono">'81635898000176</span></div>
            </div>
        </div>

        <div v-if="importForm.processing" class="mt-2 mb-4 bg-white border rounded-lg p-4">
            <div class="text-sm font-semibold">Importação CSV</div>
            <div class="text-sm text-text-secondary mt-1">
                Importando arquivo... Aguarde.
                <span v-if="importForm.progress"> ({{ importForm.progress.percentage }}%)</span>
            </div>
        </div>

        <div v-if="flashSuccess" class="mt-2 mb-4 bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-700">
            {{ flashSuccess }}
        </div>

        <div v-if="flashError" class="mt-2 mb-4 bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-700">
            {{ flashError }}
        </div>

        <div v-if="importSummary" class="mt-2 mb-4 bg-white border rounded-lg p-4">
            <div class="text-sm font-semibold">Importação CSV</div>
            <div class="text-sm text-text-secondary mt-1">
                Criadas: {{ importSummary.created }}
                | Atualizadas: {{ importSummary.updated }}
                | Ignoradas: {{ importSummary.skipped }}
                | Erros: {{ importSummary.errors_count }}
            </div>

            <div v-if="importSummary.errors_count > 0" class="mt-3">
                <div class="text-sm font-semibold">Erros (primeiros {{ importSummary.errors?.length || 0 }})</div>
                <div class="mt-2 space-y-1 text-sm text-red-600">
                    <div v-for="(err, idx) in importSummary.errors" :key="idx">
                        Linha {{ err.line }} ({{ err.field }}): {{ err.message }}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex gap-4">
            <SimpleIconCard title="Total de empresas" :subtitle="String(displayedMetrics.total)" :icon="Building2" />
            <SimpleIconCard title="Empresas ativas" :subtitle="String(displayedMetrics.active)" :icon="UserRoundCheck" />
            <SimpleIconCard title="Empresas inativas" :subtitle="String(displayedMetrics.inactive)" :icon="UserRoundX" />
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
                                <DropdownLink as="button" type="button" class="text-red-600" @click.stop="requestDelete(company.id)">Excluir</DropdownLink>
                            </template>
                        </Dropdown>
                    </td>
                </tr>
            </template>
        </Table>
        <Paginator :links="companies.links" />

        <!-- Modal de confirmação reutilizável -->
        <ConfirmModal
            v-model:open="showDeleteModal"
            :danger="true"
            title="Excluir empresa"
            message="Tem certeza que deseja excluir esta empresa? Esta ação não pode ser desfeita."
            confirmLabel="Excluir"
            cancelLabel="Cancelar"
            :loading="deletingLoading"
            @confirm="confirmDelete"
        />
    </AuthenticatedLayout>
</template>
