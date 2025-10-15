<script setup>
import InputLabel from './InputLabel.vue';
import SecondaryButton from './SecondaryButton.vue';
import TextInput from './TextInput.vue';
import { ref } from 'vue';
import ContentManagerModal from './ContentManagerModal.vue';
import PrimaryButton from './PrimaryButton.vue';
import { FileMinus, Plus, Trash2, X } from 'lucide-vue-next';
import ContentTypeIcon from './ContentTypeIcon.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    contents: {
        type: Array,
        default: () => [],
    }
})

const emit = defineEmits(['close', 'save']);

const stepData = ref({
    title: '',
    description: '',
    content_id: null,
    content: null,
})

const isContentModalVisible = ref(false);

function handleContentSelection(selectedIds) {
    const selectedId = selectedIds[0];

    if(selectedId) {
        const selectedContentObject = props.contents.find(c => c.id === selectedId);
    
        if(selectedContentObject) {
            stepData.value.content_id = selectedContentObject.id;
            stepData.value.content = selectedContentObject;
        }
    }
    isContentModalVisible.value = false;
}

function removeContent() {
    stepData.value.content_id = null;
    stepData.value.content = null;
}

function resetForm() {
    stepData.value = {
        title: '',
        description: '',
        content_id: null,
        content: null,
    }
}

function saveStep() {
    // Emit a shallow copy to avoid external mutations
    emit('save', { ...stepData.value });
    resetForm();
}
</script>

<template>
    <div v-if="show" @click="emit('close')" class="fixed inset-0 bg-black bg-opacity-50 z-[998]">
        <div @click.stop class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-xl z-[999] p-6 2xl:max-w-5xl max-w-3xl w-full max-h-[calc(100vh-4rem)] flex flex-col">
            <div class="flex flex-col mb-4">
                <h2 class="text-xl text-text-primary font-semibold">Nova Etapa</h2>
                <p class="text-text-secondary">Adicione uma nova etapa ao tutorial</p>
            </div>

            <form @submit.prevent="saveStep" class="flex flex-col gap-4">
                <div>
                    <InputLabel for="title" value="Título" />
                    <TextInput 
                        id="title" 
                        v-model="stepData.title" 
                        class="w-full mt-1"
                        required 
                        autofocus 
                        autocomplete="title" />
                </div>
                <div>
                    <InputLabel for="description" value="Descrição (Opcional)" />
                    <TextInput 
                        id="description" 
                        v-model="stepData.description" 
                        class="w-full mt-1"
                        autofocus 
                        autocomplete="description" />
                </div>
                <div class="content">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-sm font-medium text-text-primary">Conteúdo da Etapa</h2>
                        <SecondaryButton :icon="Plus" @click="isContentModalVisible = true">Adicionar Conteúdo</SecondaryButton>
                    </div>    
                    <div v-if="!stepData.content_id" class="p-6 flex flex-col items-center gap-2 border border-dashed border-text-secondary rounded-lg mb-4">
                        <FileMinus class="w-12 h-12 text-text-secondary" />
                        <p class="text-text-secondary">Nenhum conteúdo adicionado</p>
                        <button type="button" @click="isContentModalVisible = true" class="mt-1 text-text-primary font-semibold">
                            + Adicionar Conteúdo
                        </button>    
                    </div>
                    <div v-else class="mt-2 p-3 border rounded-lg flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <ContentTypeIcon :type="stepData.content.type" />
                            <div>
                                <p class="font-semibold">{{ stepData.content.title }}</p>
                                <span class="text-xs text-text-secondary">{{ stepData.content.type }}</span>
                            </div>
                        </div>
                        <button type="button" @click="removeContent" class="text-text-danger">
                            <X class="w-6 h-6 text-red-600" />
                        </button>
                    </div>
                </div>
            </form>
            <div class="flex gap-4">
                <SecondaryButton @click="emit('close')">Cancelar</SecondaryButton>
                <PrimaryButton type="submit" @click="saveStep">Adicionar Etapa</PrimaryButton>
            </div>
        </div>
    </div>
    <ContentManagerModal 
        v-if="isContentModalVisible"
        :show="isContentModalVisible" 
        :contents="props.contents"
        :initial-selected-ids="stepData.content_id ? [stepData.content_id] : []"
        @close="isContentModalVisible = false" 
        @confirm="handleContentSelection"
        modalTitle="Selecionar Conteúdo para a Etapa"
        modalDescription="Escolha um vídeo, documento ou imagem da biblioteca"
        :attachmentUrl="null"
        :allowMultiple="false"
    />
</template>