<script setup>
import CategoryFormModal from '@/Components/CategoryFormModal.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import IconButton from '@/Components/IconButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Table from '@/Components/Table.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Ellipsis, Pen, Plus, Trash } from 'lucide-vue-next';
import * as Lucide from 'lucide-vue-next';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Paginator from '@/Components/Paginator.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

defineProps({
    categories: Object,
})

const isCategoryModalVisible = ref(false)
const editingCategory = ref(null)

function openCreateModal() {
    editingCategory.value = null
    isCategoryModalVisible.value = true
}

function openEditModal(category) {
    editingCategory.value = category
    isCategoryModalVisible.value = true
}

function closeModal() {
    isCategoryModalVisible.value = false
    editingCategory.value = null
}

function onCreate() {
    openCreateModal()
}

// Modal de confirmação de exclusão
const showDeleteModal = ref(false);
const deletingId = ref(null);
const deletingLoading = ref(false);

function requestDelete(id) {
    if (!id) return;
    deletingId.value = id;
    showDeleteModal.value = true;
}

function confirmDelete() {
    if (!deletingId.value) return;
    deletingLoading.value = true;
    router.delete(route('manage.categories.destroy', deletingId.value), {
        preserveScroll: true,
        onFinish: () => {
            deletingLoading.value = false;
            showDeleteModal.value = false;
            deletingId.value = null;
        },
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <HeaderTitle title="Gerenciamento de Categorias" subtitle="Gerencie as categorias do sistema" />
            <div class="flex gap-4 py-2">
                <PrimaryButton :icon="Plus" @click="onCreate">
                    <span class="mt-1">Nova Categoria</span>
                </PrimaryButton>
            </div>
        </div>

        <Table>
            <template #header>
                <tr class="text-left text-xs">
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Icone</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Nome</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Descrição</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Tutoriais</th>
                    <th scope="col" class="p-4 text-sm font-semibold text-text-secondary">Ações</th>
                </tr>
            </template>
            <template #body>
                <tr
                    v-for="category in categories.data"
                    :key="category.id"
                    class="hover:bg-surface cursor-pointer"
                    role="button"
                    tabindex="0"
                    @click="onView(category.id)"
                    @keydown.enter.prevent="onView(category.id)"
                    @keydown.space.prevent="onView(category.id)"
                >
                    <td class="p-4 text-sm font-medium text-text-primary">
                        <span class="inline-flex items-center gap-2">
                            <component
                                v-if="Lucide[category.icon_name]"
                                :is="Lucide[category.icon_name]"
                                class="w-5 h-5"
                                :style="{ color: category.icon_color || '#6b7280' }"
                            />
                            <span v-else
                                class="inline-block w-3 h-3 rounded-full"
                                :style="{ backgroundColor: category.icon_color || '#9ca3af' }"
                            />
                            <span class="text-xs text-text-secondary">{{ category.icon_name }}</span>
                        </span>
                    </td>
                    <td class="p-4 text-sm font-medium text-text-primary">{{ category.name }}</td>
                    <td class="p-4 text-sm font-medium text-text-primary">{{ category.description }}</td>
                    <td class="p-4 text-sm font-medium text-text-primary">{{ category.tutorials_count ?? 0 }}</td>
                    <td class="p-4 text-sm font-medium text-text-primary">
                        <Dropdown>
                            <template #trigger>
                                <IconButton :icon="Ellipsis" />
                            </template>
                            <template #content>
                                <DropdownLink as="button" type="button" @click.stop="openEditModal(category)"><div class="flex items-center gap-2"><Pen class="w-4 h-4"/><span class="mt-1">Editar</span></div></DropdownLink>
                                <DropdownLink as="button" type="button" @click.stop="requestDelete(category.id)"><div class="flex items-center gap-2"><Trash class="w-4 h-4"/><span class="mt-1">Excluir</span></div></DropdownLink>
                            </template>
                        </Dropdown>
                    </td>
                </tr>
            </template>
        </Table>
        <Paginator :links="categories.links" />

        <CategoryFormModal
            :show="isCategoryModalVisible"
            :category="editingCategory"
            @close="closeModal"
        />

        <!-- Modal de confirmação reutilizável -->
        <ConfirmModal
            v-model:open="showDeleteModal"
            :danger="true"
            title="Excluir categoria"
            message="Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita."
            confirmLabel="Excluir"
            cancelLabel="Cancelar"
            :loading="deletingLoading"
            @confirm="confirmDelete"
        />
    </AuthenticatedLayout>
</template>