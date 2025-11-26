<script setup>
import HeaderTitle from '@/Components/HeaderTitle.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Download, Funnel, Play, Plus, Pencil, Trash } from 'lucide-vue-next';
import { Link, router } from '@inertiajs/vue3';
import { useFormatters } from '@/Composables/useFormatters';
import SearchInput from '@/Components/SearchInput.vue';
import IconTextButton from '@/Components/IconTextButton.vue';
import { computed, ref } from 'vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

const props = defineProps({
    category: {
        type: Object,
        required: true,
    },
});

function levelLabel(level) {
    const key = String(level || '').toLowerCase();
    const map = {
        beginner: 'Iniciante',
        intermediate: 'Intermediário',
        advanced: 'Avançado',
    };
    return map[key] || level;
}

function levelClasses(level) {
    const key = String(level || '').toLowerCase();
    switch (key) {
        case 'beginner':
            return 'bg-green-100 text-green-800';
        case 'intermediate':
            return 'bg-yellow-100 text-yellow-800';
        case 'advanced':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

const { formatDate } = useFormatters();

function createTutorial() {
    router.get(route('tutorials.create', { category_id: props.category.id }));
}

function onEdit(tutorialId) {
    // For now, navigate to show (edit page not implemented yet)
    router.visit(route('tutorials.show', tutorialId));
}

// Modal de confirmação para excluir tutorial
const showDeleteModal = ref(false);
const deletingId = ref(null);
const deletingLoading = ref(false);

function requestDelete(tutorialId) {
    deletingId.value = tutorialId;
    showDeleteModal.value = true;
}

function confirmDelete() {
    if (!deletingId.value) return;
    deletingLoading.value = true;
    router.delete(route('tutorials.destroy', deletingId.value), {
        onFinish: () => {
            deletingLoading.value = false;
            showDeleteModal.value = false;
            deletingId.value = null;
        },
    });
}

// Filters state
const search = ref('');
const showFilters = ref(false);
const levelSelected = ref([]); // multi-select via checkboxes
const statusSelected = ref([]);

const levelOptions = [
    { value: '', label: 'Todos os níveis' },
    { value: 'beginner', label: 'Iniciante' },
    { value: 'intermediate', label: 'Intermediário' },
    { value: 'advanced', label: 'Avançado' },
];

const statusOptions = [
    { value: 'published', label: 'Publicado' },
    { value: 'draft', label: 'Rascunho' },
];

const filteredTutorials = computed(() => {
    const term = search.value.trim().toLowerCase();
    return (props.category.tutorials || [])
        .filter(t => {
            const title = String(t.title || '').toLowerCase();
            const desc = String(t.description || '').toLowerCase();
            const level = String(t.level || '').toLowerCase();
            const status = String(t.status || '').toLowerCase();

            const matchesSearch = term === '' || title.includes(term) || desc.includes(term);
            const matchesLevel = levelSelected.value.length === 0
                || levelSelected.value.includes('')
                || levelSelected.value.includes(level);
            const matchesStatus = statusSelected.value.length === 0 || statusSelected.value.includes(status);
            return matchesSearch && matchesLevel && matchesStatus;
        });
});

function onToggleLevel(val) {
    // If 'Todos os níveis' (value: '') is selected, clear other selections
    if (val === '') {
        levelSelected.value = [''];
        return;
    }
    // If selecting a specific level, make sure 'Todos' is not selected
    levelSelected.value = levelSelected.value.filter(v => v !== '');
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <HeaderTitle :title="category.name" :subtitle="category.description" />
        </div>

        <div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-4 mt-8 mb-8 relative">
            <div class="flex-1 min-w-0">
                <SearchInput v-model="search" placeholder="Buscar tutorial" />
            </div>
            <div class="flex items-center gap-2">
                <IconTextButton :icon="Funnel" @click="showFilters = !showFilters">
                    Filtros
                </IconTextButton>
            </div>

            <!-- Filters dropdown -->
            <div v-if="showFilters" class="absolute right-0 top-full mt-2 w-full md:w-auto z-10">
                <div class="bg-white border rounded-lg shadow-lg p-4 w-full md:min-w-[420px]">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-semibold text-sm text-text-primary mb-2">Nível</h4>
                            <div class="space-y-2">
                                <label v-for="opt in levelOptions" :key="opt.value" class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" :value="opt.value" v-model="levelSelected" @change="onToggleLevel(opt.value)" class="rounded border-gray-300 text-brand-accent focus:ring-brand-accent" />
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
                        <button type="button" class="text-sm text-text-secondary" @click="levelSelected = []; statusSelected = []">Limpar</button>
                        <button type="button" class="text-sm text-white bg-brand-accent px-3 py-1.5 rounded" @click="showFilters = false">Aplicar</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="(category.tutorials || []).length === 0">
            <p class="text-text-secondary">Nenhum tutorial cadastrado</p>
        </div>
        <div v-else>
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4"
            >
                <Link
                    v-for="tutorial in filteredTutorials"
                    :key="tutorial.id"
                    :href="route('tutorials.show', tutorial.id)"
                    class="border shadow-sm bg-white border-opacity-50 rounded p-4"
                >
                    <div class="flex flex-col gap-6">
                        <div class="flex justify-between">
                            <div class="flex-grow gap-2 min-w-0">
                                <p class="text-text-primary font-semibold truncate break-words">{{ tutorial.title }}</p>
                                <p class="text-text-secondary line-clamp-2 break-words">{{ tutorial.description }}</p>
                            </div>
                            <p class="text-text-primary px-2 py-1 flex items-center gap-2 rounded text-xs my-1"><Play class="w-6 h-6" /></p>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between gap-2">
                                <p class="text-text-secondary text-sm">{{ tutorial.steps_count }} etapas</p>
                                <p class="text-text-secondary text-sm">{{ formatDate(tutorial.created_at) }}</p>
                            </div>
                            <div class="flex gap-2 items-center justify-between">
                                <span :class="`px-2 py-0.5 rounded text-xs ${levelClasses(tutorial.level)}`">{{ levelLabel(tutorial.level) }}</span>
                                <div class="flex items-center gap-3">
                                    <button type="button" class="text-text-secondary hover:text-text-primary" @click.prevent.stop="onEdit(tutorial.id)">
                                        <Pencil class="w-5 h-5" />
                                    </button>
                                    <button type="button" class="text-red-600 hover:text-red-700" @click.prevent.stop="requestDelete(tutorial.id)">
                                        <Trash class="w-5 h-5" />
                                    </button>
                                    <Download class="w-5 h-5 text-text-secondary" />
                                </div>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>
        </div>

        <!-- Modal de confirmação reutilizável -->
        <ConfirmModal
            v-model:open="showDeleteModal"
            :danger="true"
            title="Excluir tutorial"
            message="Tem certeza que deseja excluir este tutorial? Esta ação não pode ser desfeita."
            confirmLabel="Excluir"
            cancelLabel="Cancelar"
            :loading="deletingLoading"
            @confirm="confirmDelete"
        />
    </AuthenticatedLayout>
</template>

<style scoped>
/* Two-line clamp without Tailwind plugin */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2; /* standard property for compatibility */
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
