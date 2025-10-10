<script setup>
import { computed, ref, watch } from 'vue';
import SearchInput from './SearchInput.vue';
import FormSelect from './FormSelect.vue';
import { Calendar, CalendarPlus, Check, CheckCircle, Circle, Funnel, Plus, Upload, User, X } from 'lucide-vue-next';
import PrimaryButton from './PrimaryButton.vue';
import SecondaryButton from './SecondaryButton.vue';
import { router } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import ContentTypeIcon from '@/Components/ContentTypeIcon.vue';

const props = defineProps({
    show: Boolean,
    default: false,
    modalTitle: String,
    modalDescription: String,
    contentableId: Number,
    contentableType: String,
    attachmentUrl: String,
    contents: {
        type: Array,
        default: () => [],
    },
    initialSelectedIds: {
        type: Array,
        default: () => [],
    }
})

function toggleSelection(contentId) {
    const index = selectedContentIds.value.indexOf(contentId);
    if (index === -1) {
        selectedContentIds.value.push(contentId);
    } else {
        selectedContentIds.value.splice(index, 1);
    }
}

function isSelected(contentId) {
    return selectedContentIds.value.includes(contentId);
}

const selectedContentIds = ref([]);

const attachmentForm = useForm({
    content_ids: [],
});

const emit = defineEmits(['close'])

const activeTab = ref("library")

const filterOptions = [
    { value: "", label: "Todos os tipos" },
    { value: "videos", label: "Vídeos" },
    { value: "pdf", label: "PDFs" },
    { value: "sheets", label: "Planilhas" },
    { value: "documents", label: "Documentos"},
    { value: "images", label: "Imagens"}
]

const typeFilter = ref('');
const fileInputRef = ref(null);
const filesToUpload = ref([]);
const isDragging = ref(false);

const sortedContents = computed(() => {
    return [...props.contents].sort((a, b) => {
        const aIsSelected = isSelected(a.id);
        const bIsSelected = isSelected(b.id);
        
        if (aIsSelected && !bIsSelected) return -1;
        if (!aIsSelected && bIsSelected) return 1;
        return 0;
    })
})
    

watch(() => props.show, (isShown) => {
    if (isShown) {
        selectedContentIds.value = [...props.initialSelectedIds];
    }
}, { immediate: true })

function confirmSelection() {
    attachmentForm.content_ids = [...selectedContentIds.value];
    attachmentForm.post(props.attachmentUrl, {
        onSuccess: () => {
            emit('close')
        },
        preserveScroll: true,
    });
}

function addFiles(newFiles) {
    const newUploads = newFiles.map(file => {
        const form = useForm({
            file: file,
        })

        return {
            id: Date.now() + Math.random(),
            file: file,
            form: form,
            progress: 0,
            status: 'pending',
            db_id: null,
        }
    });

    filesToUpload.value = [...filesToUpload.value, ...newUploads];
    newUploads.forEach(upload => startRealUpload(upload));
}

function startRealUpload(upload) {
    upload.status = 'uploading';

    upload.form.post(route('contents.store'), {
        onProgress: progress => (upload.progress = progress.percentage),
        onError: () => (upload.status = 'error'),
        onSuccess: (page) => {
            console.log(page)
            const newContent = page.props.flash.newContent;

            if (newContent) {
                upload.status = 'completed';
                upload.db_id = newContent.id;
                selectedContentIds.value.push(newContent.id);
            } else {
                upload.status = 'error';
            }
        }
    });
}

function handleFileSelection(event) {
    addFiles(Array.from(event.target.files));
    event.target.value = null;
}

function handleFileDrop(event) {
    isDragging.value = false;
    addFiles(Array.from(event.dataTransfer.files));
}

function removeFile(upload) {
    filesToUpload.value = filesToUpload.value.filter(f => f.id !== upload.id);

    if (upload.db_id) {
        selectedContentIds.value = selectedContentIds.value.filter(id => id !== upload.db_id);
    }

    if (upload.status !== 'uploading' && upload.db_id) {
        router.delete(route('contents.destroy', upload.db_id), {
            preserveState: true,
        })
    }
}

function formatDate(isoString, timeZone = 'America/Sao_Paulo') {
    if(!isoString) return '';
    const d = new Date(isoString);
    if(isNaN(d)) return '';
    return new Intl.DateTimeFormat('pt-BR', {
        timeZone,
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    }).format(d);
}

function formatBytes(bytes, locale = 'pt-BR') {
    if(bytes === null || bytes === undefined) return '';
    const b = Number(bytes);
    if(!isFinite(b)) return '';
    const units = ['B', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(Math.max(b, 1)) / Math.log(1024));
    const value = b / Math.pow(1024, i);
    return `${value.toFixed(value < 10 && i > 0 ? 1 : 0)} ${units[i]}`;
}
</script>

<template>
    <div v-if="show" @click="emit('close')" class="fixed inset-0 bg-black bg-opacity-50 z-[998]">
        <div @click.stop class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-xl z-[999] p-6 2xl:max-w-6xl max-w-4xl w-full max-h-[calc(100vh-4rem)] flex flex-col">
            <div>
                <div class="flex flex-col mb-4">
                    <h2 class="text-xl text-text-primary font-semibold">{{ modalTitle }}</h2>
                    <p class="text-text-secondary">{{ modalDescription }}</p>
                </div>

                <nav class="flex p-1 bg-brand-accentlight rounded-lg font-semibold">
                    <button @click="activeTab = 'library'" :class="{ 'bg-brand-accent text-white': activeTab == 'library' }" class="w-full px-4 py-2 rounded-md">Biblioteca</button>
                    <button @click="activeTab = 'upload'" :class="{ 'bg-brand-accent text-white': activeTab == 'upload' }" class="w-full px-4 py-2 rounded-md">Fazer Upload</button>
                </nav>

                <div class="flex items-center gap-3 mt-4 justify-between">
                    <div class="w-full max-w-md">
                        <SearchInput placeholder="Buscar conteúdo" />
                    </div>
                    <div class="flex items-center gap-2 w-1/4">
                        <Funnel class="w-5 h-5 text-text-secondary" />
                        <FormSelect
                            class="h-10 px-2 border border-gray-300 rounded-md shadow-sm"
                            :options="filterOptions"
                            v-model="typeFilter"
                        />
                    </div>
                </div>
            </div>

            <div class="flex-grow overflow-y-auto mt-4 border-t pt-4 min-h-0">
                <div v-if="activeTab == 'library'">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 overflow-y-auto p-1">
                        <div
                            v-for="content in sortedContents"
                            :key="content.id"
                            @click="toggleSelection(content.id)"
                            class="border rounded-lg p-3 cursor-pointer relative transition-all hover:border-brand-accent"
                            :class="{ 'border-2 border-brand-accent bg-brand-accentlight': isSelected(content.id) }"
                        >
                            <div v-if="isSelected(content.id)" class="absolute top-2 right-2 bg-brand-accent text-white rounded-full p-0.5">
                                <Check class="w-4 h-4" />
                            </div>
                            <div v-else class="absolute top-2 right-2 text-brand-accent rounded-full p-0.5">
                                <Circle class="w-4 h-4" />
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0">
                                    <ContentTypeIcon :type="content.type" size="w-8 h-8" />
                                </div>
                                <div class="flex-grow min-w-0 max-w-[75%]">
                                    <h4 class="font-bold text-sm truncate" :title="content.title">{{ content.title }}</h4>
                                    <p class="text-xs text-gray-500 capitalize">{{ content.type }}</p>
                                </div>
                            </div>

                            <div class="flex justify-between items-center gap-3 text-xs mt-3">
                                <div v-if="content.uploader" class="flex items-center gap-1 text-text-secondary">
                                    <User class="w-4 h-4" />
                                    <span>{{ content.uploader.name }}</span>
                                </div>
                                <div v-if="content.created_at" class="flex items-center gap-1 text-text-secondary">
                                    <CalendarPlus class="w-4 h-4" />
                                    <span>{{ formatDate(content.created_at) }}</span>
                                </div>
                            </div>
                            <span v-if="content.size_bytes" class="text-text-secondary text-xs">{{ formatBytes(content.size_bytes) }}</span>
                        </div>
                    </div>
                </div>

                <div v-if="activeTab == 'upload'">
                    <input type="file" multiple ref="fileInputRef" @change="handleFileSelection" class="hidden" />
                    <div 
                        @dragover.prevent="isDragging = true" 
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleFileDrop"
                        :class="isDragging ? 'bg-brand-accentlight border-brand-accent' : 'bg-white border-gray-300'"
                        class="dropzone border-dashed border-2 rounded-lg py-8 flex flex-col items-center gap-4" 
                        >
                        <Upload class="w-5 h-5 text-text-secondary" />
                        <div class="flex flex-col items-center">
                            <div v-if="isDragging">
                                <h2 class="text-text-primary font-semibold text-lg text-center"> Solte os arquivos aqui! </h2>
                                <p class="text-text-secondary">Suporte para vídeos, PDFs, planilhas, documentos e imagens</p>
                            </div>
                            <div v-else>
                                <h2 class="text-text-primary font-semibold text-lg text-center"> Arraste arquivos aqui ou clique para selecionar </h2>
                                <p class="text-text-secondary">Suporte para vídeos, PDFs, planilhas, documentos e imagens</p>
                            </div>
                        </div>
                        <PrimaryButton type="button" :icon="Plus" @click="fileInputRef.click()">Selecionar arquivos</PrimaryButton>
                    </div>
                    <div v-if="filesToUpload.length > 0" class="mt-4">
                        <h3>Arquivos Selecionados ({{ filesToUpload.length }})</h3>

                        <div v-for="upload in filesToUpload" :key="upload.id" class="mt-4 flex items-center gap-4 p-4 rounded border border-gray-300">
                            <ContentTypeIcon :mimeType="upload.file.type" size="w-8 h-8" />
                            <div class="flex flex-col">
                                <h4 class="font-semibold text-text-primary text-md">{{ upload.file.name }}</h4>
                                <div v-if="upload.status === 'uploading'" class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                    <div class="bg-green-500 h-1.5 rounded-full" :style="{ width: upload.progress + '%' }"></div>
                                </div>
                                <div v-else-if="upload.status === 'completed'" class="flex items-center gap-2 text-green-500 text-sm font-semibold">
                                    <CheckCircle class="w-5 h-5" /> 
                                    <span class="mt-1">Upload Concluído</span>
                                </div>
                                <div v-else-if="upload.status === 'error'" class="flex items-center gap-2 text-red-500">
                                    <X class="w-5 h-5" /> Upload Falhou
                                </div>
                            </div>
                            <button class="ml-auto" @click="removeFile(upload)"><X class="w-5 h-5" /></button>                           
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 border border-gray-300 rounded-md p-4 mt-4">
                        <h4 class="font-semibold text-text-primary">Diretrizes para Upload</h4>
                        <ul class="list-disc list-inside marker:text-brand-accent marker:text-2xl marker:font-semibold text-sm text-text-secondary">
                            <li><span class="font-semibold">Vídeos:</span> MP4, MOV, AVI até 500MB. Recomendado: 1080p, 30fps</li>
                            <li><span class="font-semibold">Imagens:</span> JPG, PNG, GIF até 10MB</li>
                            <li><span class="font-semibold">Documentos:</span> PDF, DOC, DOCX até 50MB</li>
                            <li><span class="font-semibold">Planilhas:</span> XLS, XLSX, CSV até 25MB</li>
                        </ul>
                    </div>
                </div>
                <div class="flex justify-end border-t pt-4 mt-4 gap-2">
                    <SecondaryButton @click="emit('close')">Cancelar</SecondaryButton>
                    <PrimaryButton type="button" @click="confirmSelection">Confirmar Seleção</PrimaryButton>
                </div>
            </div>
        </div>
    </div>
</template>
