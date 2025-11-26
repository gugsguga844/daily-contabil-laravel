<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import SearchInput from '@/Components/SearchInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import IconButton from '@/Components/IconButton.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import Paginator from '@/Components/Paginator.vue';
import ContentTypeIcon from '@/Components/ContentTypeIcon.vue';
import { useFormatters } from '@/Composables/useFormatters';
import { Grid2x2, List, Upload, Ellipsis } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
  contents: Object, // pagination object
  metrics: Object,
});

// UI state
const viewMode = ref('grid'); // 'grid' | 'list'
const search = ref('');
const typeSelected = ref(''); // '', 'videos', 'pdf', 'images', 'sheets', 'documents'

const typeFilters = [
  { value: '', label: 'Todos' },
  { value: 'videos', label: 'Vídeos' },
  { value: 'pdf', label: 'PDFs' },
  { value: 'images', label: 'Imagens' },
  { value: 'sheets', label: 'Planilhas' },
  { value: 'documents', label: 'Documentos' },
];

// Formatting helpers
const { formatDate, formatBytes } = useFormatters();

// Filters
const filteredItems = computed(() => {
  const term = search.value.trim().toLowerCase();
  const data = props.contents?.data || [];
  return data.filter(i => {
    const title = String(i.title || '').toLowerCase();
    const matchesTerm = term === '' || title.includes(term);
    const matchesType = !typeSelected.value || i.type === typeSelected.value;
    return matchesTerm && matchesType;
  });
});

// Upload logic (single or multiple)
const fileInput = ref(null);
const uploads = ref([]);

function openUpload() {
  fileInput.value?.click();
}

function onFilesChosen(e) {
  const files = Array.from(e.target.files || []);
  if (files.length === 0) return;
  files.forEach(f => startUpload(f));
  e.target.value = null;
}

function startUpload(file) {
  const form = useForm({ file });
  const item = { id: Date.now() + Math.random(), name: file.name, progress: 0, status: 'uploading' };
  uploads.value.push(item);
  form.post(route('contents.store'), {
    onProgress: p => item.progress = p.percentage,
    onError: () => item.status = 'error',
    onSuccess: () => item.status = 'completed',
    onFinish: () => {
      // refresh list to include new file
      router.reload({ only: ['contents', 'metrics'] });
      setTimeout(() => uploads.value = uploads.value.filter(u => u !== item), 1000);
    },
    preserveScroll: true,
  });
}

// Deletion
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
  router.delete(route('contents.destroy', deletingId.value), {
    onFinish: () => {
      deletingLoading.value = false;
      showDeleteModal.value = false;
      deletingId.value = null;
      router.reload({ only: ['contents', 'metrics'] });
    }
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Biblioteca" />

    <div class="flex items-center justify-between mb-6">
      <HeaderTitle title="Biblioteca" subtitle="Gerencie todos os arquivos e mídias do sistema" />
      <div class="flex items-center gap-2">
        <input ref="fileInput" type="file" class="hidden" multiple @change="onFilesChosen" />
        <PrimaryButton :icon="Upload" @click="openUpload">
          <span class="mt-1">Fazer Upload</span>
        </PrimaryButton>
      </div>
    </div>

    <!-- Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="flex p-4 w-full rounded-lg bg-white shadow">
        <div class="mr-4 w-10 h-10 rounded-xl flex items-center justify-center bg-brand-dark text-surface-accent">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15V6a2 2 0 0 0-2-2h-5l-2 2H5a2 2 0 0 0-2 2v7"/><path d="M3 15v2a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2"/></svg>
        </div>
        <div>
          <div class="text-xs text-text-secondary">Total de Arquivos</div>
          <div class="text-base font-semibold text-text-primary">{{ metrics?.total ?? 0 }}</div>
        </div>
      </div>
      <div class="flex p-4 w-full rounded-lg bg-white shadow">
        <div class="mr-4 w-10 h-10 rounded-xl flex items-center justify-center bg-brand-dark text-surface-accent">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/></svg>
        </div>
        <div>
          <div class="text-xs text-text-secondary">Espaço Utilizado</div>
          <div class="text-base font-semibold text-text-primary">{{ formatBytes(metrics?.used_bytes || 0) }}</div>
        </div>
      </div>
      <div class="flex p-4 w-full rounded-lg bg-white shadow">
        <div class="mr-4 w-10 h-10 rounded-xl flex items-center justify-center bg-brand-dark text-surface-accent">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
        </div>
        <div>
          <div class="text-xs text-text-secondary">Vídeos</div>
          <div class="text-base font-semibold text-text-primary">{{ metrics?.videos ?? 0 }}</div>
        </div>
      </div>
    </div>

    <!-- Filters and view toggle -->
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-2 w-full max-w-xl">
        <SearchInput v-model="search" placeholder="Buscar arquivos..." />
      </div>
      <div class="flex items-center gap-2">
        <div class="hidden md:flex items-center gap-1">
          <button v-for="opt in typeFilters" :key="opt.value" type="button"
                  class="px-3 py-1.5 rounded text-sm border"
                  :class="typeSelected === opt.value ? 'bg-brand-accent text-white border-brand-accent' : 'border-base-200 text-text-secondary hover:bg-surface'"
                  @click="typeSelected = opt.value">
            {{ opt.label }}
          </button>
        </div>
        <div class="flex items-center gap-1">
          <IconButton :icon="Grid2x2" @click="viewMode = 'grid'" />
          <IconButton :icon="List" @click="viewMode = 'list'" />
        </div>
      </div>
    </div>

    <!-- Uploads in progress -->
    <div v-if="uploads.length" class="mb-4 space-y-2">
      <div v-for="u in uploads" :key="u.id" class="flex items-center gap-3 p-3 bg-white rounded border">
        <div class="w-40 truncate text-sm">{{ u.name }}</div>
        <div class="flex-1 h-2 bg-gray-200 rounded">
          <div class="h-2 bg-brand-accent rounded" :style="{ width: (u.progress||0)+'%' }"></div>
        </div>
        <div class="text-xs text-text-secondary">{{ u.progress || 0 }}%</div>
      </div>
    </div>

    <!-- Grid view -->
    <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="item in filteredItems" :key="item.id" class="border shadow-sm bg-white rounded p-4 flex flex-col gap-3">
        <div class="flex items-center gap-3">
          <ContentTypeIcon :type="item.type" class="w-8 h-8" />
          <div class="min-w-0 flex-1">
            <p class="font-semibold text-text-primary truncate" :title="item.title">{{ item.title }}</p>
            <p class="text-xs text-text-secondary">{{ item.type.toUpperCase() }} • {{ formatBytes(item.size_bytes) }}</p>
          </div>
          <Dropdown>
            <template #trigger>
              <IconButton :icon="Ellipsis" />
            </template>
            <template #content>
              <DropdownLink :href="item.full_url" target="_blank">Baixar</DropdownLink>
              <DropdownLink as="button" type="button" class="text-red-600" @click.stop="requestDelete(item.id)">Excluir</DropdownLink>
            </template>
          </Dropdown>
        </div>
        <div class="flex items-center justify-between text-xs text-text-secondary">
          <span v-if="item.uploader">Enviado por {{ item.uploader.name }}</span>
          <span>{{ formatDate(item.created_at) }}</span>
        </div>
      </div>
    </div>

    <!-- List view -->
    <div v-else class="bg-white rounded border overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-surface">
          <tr class="text-left text-text-secondary">
            <th class="px-4 py-3">Arquivo</th>
            <th class="px-4 py-3">Tipo</th>
            <th class="px-4 py-3">Tamanho</th>
            <th class="px-4 py-3">Enviado por</th>
            <th class="px-4 py-3">Data</th>
            <th class="px-4 py-3">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in filteredItems" :key="item.id" class="border-t">
            <td class="px-4 py-3">
              <div class="flex items-center gap-2 min-w-0">
                <ContentTypeIcon :type="item.type" class="w-6 h-6" />
                <a :href="item.full_url" target="_blank" class="text-text-primary hover:underline truncate">
                  {{ item.title }}
                </a>
              </div>
            </td>
            <td class="px-4 py-3 capitalize">{{ item.type }}</td>
            <td class="px-4 py-3">{{ formatBytes(item.size_bytes) }}</td>
            <td class="px-4 py-3">{{ item.uploader?.name || '—' }}</td>
            <td class="px-4 py-3">{{ formatDate(item.created_at) }}</td>
            <td class="px-4 py-3">
              <Dropdown>
                <template #trigger>
                  <IconButton :icon="Ellipsis" />
                </template>
                <template #content>
                  <DropdownLink :href="item.full_url" target="_blank">Baixar</DropdownLink>
                  <DropdownLink as="button" type="button" class="text-red-600" @click.stop="requestDelete(item.id)">Excluir</DropdownLink>
                </template>
              </Dropdown>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-6">
      <Paginator :links="contents.links" />
    </div>

    <ConfirmModal
      v-model:open="showDeleteModal"
      :danger="true"
      title="Excluir arquivo"
      message="Tem certeza que deseja excluir este arquivo? Esta ação não pode ser desfeita."
      confirmLabel="Excluir"
      cancelLabel="Cancelar"
      :loading="deletingLoading"
      @confirm="confirmDelete"
    />
  </AuthenticatedLayout>
</template>
