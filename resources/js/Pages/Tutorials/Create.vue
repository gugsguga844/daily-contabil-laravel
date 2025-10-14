<script setup>
import HeaderTitle from '@/Components/HeaderTitle.vue';
import IconButton from '@/Components/IconButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SmallHeaderTitle from '@/Components/SmallHeaderTitle.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Building2, GripHorizontal, GripVertical, Plus, SquarePen, Trash, Trash2 } from 'lucide-vue-next';
import InputLabel from '@/Components/InputLabel.vue';
import FormSelect from '@/Components/FormSelect.vue';
import { useForm } from '@inertiajs/vue3';
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import StepEditorModal from '@/Components/StepEditorModal.vue';
import { ref } from 'vue';
import draggable from 'vuedraggable';

defineProps({
    categories: Array,
    libraryContents: {
        type: Array,
        default: () => [],
    },
})

const isStepModalVisible = ref(false);

const form = useForm({
    title: '',
    description: '',
    long_description: '',
    level: null,
    category_id: null,
    level: null,
    steps: [],
    supporting_material_ids: [],
    status: 'draft',
})

const editor = useEditor({
    content: form.long_description,
    extensions: [
        StarterKit,
    ],
    onUpdate: ({ editor }) => {
        form.long_description = editor.getHTML();
    },
})

function handleStepSave(newStepFromModal) {
    newStepFromModal.temp_id = Date.now();
    form.steps.push(newStepFromModal);
    isStepModalVisible.value = false;
}
    

function addStep() {
    form.steps.push({
        temp_id: Date.now(),
        title: '',
        description: '',
        content_id: null,
        content: null,
    })
}

function removeStep(index) {
    form.steps.splice(index, 1)
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <HeaderTitle title="Tutoriais" subtitle="Base de conhecimentos e tutoriais" />
            <div class="flex gap-4 py-2">
                <PrimaryButton :icon="Plus" @click="onCreate">
                    <span class="mt-1">Nova Categoria</span>
                </PrimaryButton>
            </div>
        </div>

        <form class="flex flex-col gap-6">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex gap-4">
                    <IconButton class="my-2" :icon="Building2" />
                    <SmallHeaderTitle title="Informações Básicas" subtitle="Preencha os dados do tutorial" />
                </div>
                <div class="grid grid-cols-3 gap-4 mt-6">
                    <div class="col-span-3">
                        <InputLabel for="title">Nome</InputLabel>
                        <TextInput
                            id="title"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.title"
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </div>
                    <div class="col-span-3">
                        <InputLabel for="description">Descrição Resumida</InputLabel>
                        <TextInput
                            id="description"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.description"
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </div>
                    <div class="col-span-3">
                        <InputLabel for="long_description">Descrição Detalhada</InputLabel>
                        <div v-if="editor" class="border border-gray-300 rounded-t-lg p-2 flex items-center gap-2">
                            <button type="button" @click="editor.chain().focus().toggleBold().run()" :class="{ 'bg-gray-200': editor.isActive('bold') }" class="p-1 rounded">
                                Negrito
                            </button>
                            <button type="button" @click="editor.chain().focus().toggleItalic().run()" :class="{ 'bg-gray-200': editor.isActive('italic') }" class="p-1 rounded">
                                Itálico
                            </button>
                            <button type="button" @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'bg-gray-200': editor.isActive('bulletList') }" class="p-1 rounded">
                                Lista
                            </button>
                        </div>
                        <EditorContent :editor="editor" />
                    </div>
                    <div>
                        <InputLabel for="category_id">Categoria</InputLabel>
                        <FormSelect
                            id="category_id"
                            v-model="form.category_id"
                            :options="categories"
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </div>
                    <div>
                        <InputLabel for="status">Status</InputLabel>
                        <FormSelect
                            id="status"
                            v-model="form.status"
                            :options="[
                                { value: 'draft', label: 'Rascunho' },
                                { value: 'published', label: 'Publicado' },
                            ]"
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </div>
                    <div>
                        <InputLabel for="level">Nível</InputLabel>
                        <FormSelect
                            id="level"
                            v-model="form.level"
                            :options="[
                                { value: 'beginner', label: 'Iniciante' },
                                { value: 'intermediate', label: 'Intermediário' },
                                { value: 'advanced', label: 'Avançado' },
                            ]"
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6 flex flex-col gap-6">
                <div class="flex justify-between items-center gap-4">
                    <div class="flex gap-2">
                        <IconButton class="my-2" :icon="Building2" />
                        <SmallHeaderTitle title="Etapas do Tutorial" subtitle="Organize as aulas deste tutorial (arraste para reordenar)" />
                    </div>
                    <PrimaryButton :icon="Plus" @click="isStepModalVisible = true">
                        <span class="mt-1">Adicionar Etapa</span>
                    </PrimaryButton>
                </div>

                <div v-if="form.steps.length === 0" class="text-center p-8 border-2 border-dashed rounded-lg mt-4">
                    <p class="text-gray-500">Nenhuma etapa adicionada</p>
                    <button type="button" @click="addStep" class="mt-2 text-blue-600 font-semibold">
                        Comece adicionando a primeira etapa
                    </button>
                </div>

                <div v-else class="space-y-4">
                    <draggable v-model="form.steps"></draggable>
                    <div v-for="(step, index) in form.steps" :key="step.temp_id" class="flex items-center justify-between gap-2 mb-4 bg-surface border border-text-secondary border-opacity-50 p-4 rounded-lg">
                        <div class="flex items-center gap-2">
                            <GripVertical class="w-4 h-4" />
                            <span class="text-text-secondary text-sm bg-gray-200 px-2 py-1 rounded">{{ 'Etapa: ' + (index + 1) }}</span>
                            <div>
                                <p class="font-semibold">{{ step.title }}</p>
                                <p class="text-xs text-gray-500">{{ step.description }}</p>
                                <p v-if="step.content_id" class="text-xs text-gray-500">1 material anexado: {{ step.content.title }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="flex items-center gap-2 mt-1">
                                <SquarePen class="w-4 h-4" />
                                <button type="button" @click="isStepModalVisible = true" class="text-text-primary font-semibold">
                                    Editar
                                </button>
                            </div>
                            <button type="button" @click="removeStep(step.temp_id)" class="text-red-600 font-semibold">
                                <Trash2 class="w-4 h-4 text-red-600" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <StepEditorModal
            :show="isStepModalVisible" 
            :contents="libraryContents"
            @close="isStepModalVisible = false" 
            @save="handleStepSave"
        />
    </AuthenticatedLayout>
</template>

<style scoped>
.ProseMirror {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-top: none;
    border-radius: 0 0 0.5rem 0.5rem;
    min-height: 150px;
}

.ProseMirror:focus {
    outline: 2px solid #2563eb;
    outline-offset: -1px;
}
</style>

