<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import SearchInput from '@/Components/SearchInput.vue';
import IconTextButton from '@/Components/IconTextButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import IconButton from '@/Components/IconButton.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import Paginator from '@/Components/Paginator.vue';
import { Plus, Funnel, Ellipsis, BookOpen, CheckCheck, FilePenLine } from 'lucide-vue-next';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useFormatters } from '@/Composables/useFormatters';
import SimpleIconCard from '@/Components/SimpleIconCard.vue';
import Table from '@/Components/Table.vue';

const props = defineProps({
  tutorials: Object,
  categories: Array,
  metrics: Object,
});

const search = ref('');
const showFilters = ref(false);
const categorySelected = ref('');
const statusSelected = ref('');
const { formatDate } = useFormatters();

function onCreate() { router.get(route('tutorials.create')); }

const filteredItems = computed(() => {
  const term = search.value.trim().toLowerCase();
  const data = props.tutorials?.data || [];
  return data.filter(t => {
    const matchesTerm = term === '' || String(t.title || '').toLowerCase().includes(term);
    const matchesCat = !categorySelected.value || (t.category && String(t.category.id) === String(categorySelected.value));
    const matchesStatus = !statusSelected.value || t.status === statusSelected.value;
    return matchesTerm && matchesCat && matchesStatus;
  });
});

// Delete
const showDeleteModal = ref(false);
const deletingId = ref(null);
const deletingLoading = ref(false);
function requestDelete(id) { deletingId.value = id; showDeleteModal.value = true; }
function confirmDelete() {
  if (!deletingId.value) return;
  deletingLoading.value = true;
  router.delete(route('tutorials.destroy', deletingId.value), {
    onFinish: () => {
      deletingLoading.value = false;
      showDeleteModal.value = false;
      deletingId.value = null;
      router.reload({ only: ['tutorials', 'metrics'] });
    }
  });
}

// Navigate to tutorial details (same style as Companies table)
function onView(id) {
  router.visit(route('tutorials.show', id));
}

function onEdit(id) {
  router.get(route('tutorials.edit', id));
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Gerenciar Tutoriais" />

    <div class="flex items-center justify-between mb-6">
      <HeaderTitle title="Gerenciar Tutoriais" subtitle="Gerencie os tutoriais do seu escritório" />
      <div class="flex items-center gap-2">
        <PrimaryButton :icon="Plus" @click="onCreate">
          <span class="mt-1">Novo Tutorial</span>
        </PrimaryButton>
      </div>
    </div>

    <!-- Metrics -->
    <div class="flex gap-4 mb-6">
      <SimpleIconCard title="Total de tutoriais" :subtitle="String(metrics?.total ?? 0)" :icon="BookOpen" />
      <SimpleIconCard title="Publicados" :subtitle="String(metrics?.published ?? 0)" :icon="CheckCheck" />
      <SimpleIconCard title="Rascunhos" :subtitle="String(metrics?.drafts ?? 0)" :icon="FilePenLine" />
    </div>

    <div class="flex gap-2 items-end mb-4">
      <div class="flex-1 max-w-xl"><SearchInput v-model="search" placeholder="Buscar tutoriais..." /></div>
      <IconTextButton :icon="Funnel" @click="showFilters = !showFilters">Filtros</IconTextButton>
    </div>

    <div v-if="showFilters" class="bg-white rounded border p-4 mb-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="text-sm text-text-secondary">Categoria</label>
          <select v-model="categorySelected" class="mt-1 block w-full border rounded px-3 py-2">
            <option value="">Todas</option>
            <option v-for="c in categories" :key="c.value" :value="c.value">{{ c.label }}</option>
          </select>
        </div>
        <div>
          <label class="text-sm text-text-secondary">Status</label>
          <select v-model="statusSelected" class="mt-1 block w-full border rounded px-3 py-2">
            <option value="">Todos</option>
            <option value="published">Publicado</option>
            <option value="draft">Rascunho</option>
          </select>
        </div>
      </div>
    </div>

    <Table>
      <template #header>
        <tr class="text-left text-xs">
          <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Título</th>
          <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Categoria</th>
          <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Status</th>
          <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Criado em</th>
          <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Ações</th>
        </tr>
      </template>
      <template #body>
        <tr
          v-for="t in filteredItems"
          :key="t.id"
          class="hover:bg-surface cursor-pointer"
          role="button"
          tabindex="0"
          @click="onView(t.id)"
          @keydown.enter.prevent="onView(t.id)"
          @keydown.space.prevent="onView(t.id)"
        >
          <td class="p-4 align-middle">
            <div class="text-text-primary font-medium">{{ t.title }}</div>
            <div class="text-xs text-text-secondary">{{ t.description }}</div>
          </td>
          <td class="p-4 align-middle">{{ t.category?.name || '—' }}</td>
          <td class="p-4 align-middle">
            <span v-if="t.status === 'published'" class="bg-[#DBFCE7] text-[#10B981] font-semibold px-2 py-1 rounded">Publicado</span>
            <span v-else class="bg-[#FFF4E5] text-[#B45309] font-semibold px-2 py-1 rounded">Rascunho</span>
          </td>
          <td class="p-4 align-middle">{{ formatDate(t.created_at) }}</td>
          <td class="p-4 align-middle" @click.stop>
            <Dropdown>
              <template #trigger>
                <IconButton :icon="Ellipsis" />
              </template>
              <template #content>
                <DropdownLink as="button" type="button" @click.stop="onEdit(t.id)">Editar</DropdownLink>
                <DropdownLink as="button" type="button" class="text-red-600" @click.stop="requestDelete(t.id)">Excluir</DropdownLink>
              </template>
            </Dropdown>
          </td>
        </tr>
      </template>
    </Table>

    <div class="mt-6"><Paginator :links="tutorials.links" /></div>

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
