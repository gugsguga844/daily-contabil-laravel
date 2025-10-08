<script setup>
import { ref } from 'vue';
import SearchInput from './SearchInput.vue';
import FormSelect from './FormSelect.vue';
import { Check, CheckCircle, File, FileArchive, FileSpreadsheet, FileSpreadsheetIcon, FileText, Funnel, Image, Plus, Upload, Video, X } from 'lucide-vue-next';
import PrimaryButton from './PrimaryButton.vue';
import IconBackground from './IconBackground.vue';
import SecondaryButton from './SecondaryButton.vue';

defineProps({
    show: Boolean,
    default: false,
    modalTitle: String,
    modalDescription: String
})

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

function addFiles(newFiles) {
    const newUploads = newFiles.map(file => ({
        id: Date.now() + Math.random(),
        file: file,
        progress: 0,
        status: 'pending'
    }));

    filesToUpload.value = [...filesToUpload.value, ...newUploads];

    newUploads.forEach(upload => simulateUpload(upload));
}

function handleFileSelection(event) {
    addFiles(Array.from(event.target.files));
    event.target.value = null;
}

function handleFileDrop(event) {
    isDragging.value = false;
    addFiles(Array.from(event.dataTransfer.files));
}

function simulateUpload(upload) {
    upload.status = 'uploading';

    const interval = setInterval(() => {
        upload.progress += 10;

        if (upload.progress >= 100) {
            clearInterval(interval);
            upload.progress = 100;
            upload.status = 'completed';
        }
    }, 200);
}

function removeFile(id) {
    filesToUpload.value = filesToUpload.value.filter(upload => upload.id !== id);
}

</script>

<template>
    <div v-if="show" @click="emit('close')" class="fixed inset-0 bg-black bg-opacity-50 z-[998]">
        <div @click.stop class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-xl z-[999] p-6 max-w-4xl w-full">
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

            <div class="flex-grow overflow-y-auto mt-4">
                <div v-if="activeTab == 'library'">
                    <p>Library</p>
                </div>
                <div v-else>
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
                            <div v-if="upload.file.type.startsWith('image/')">
                                <Image class="w-8 h-8" />
                            </div>
                            <div v-else-if="upload.file.type.startsWith('video/')">
                                <Video class="w-8 h-8" />
                            </div>
                            <div v-else-if="upload.file.type.startsWith('application/pdf')">
                                <FileText class="w-8 h-8 text-red-500" />
                            </div>
                            <div v-else-if="upload.file.type.startsWith('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')">
                                <FileSpreadsheet class="w-8 h-8 text-green-500" />
                            </div>
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
                            <button class="ml-auto" @click="removeFile(upload.id)"><X class="w-5 h-5" /></button>                           
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

                    <div class="flex justify-end border-t pt-4 gap-2">
                        <SecondaryButton @click="emit('close')">Cancelar</SecondaryButton>
                        <PrimaryButton type="button" @click="emit('confirmSelection')">Confirmar Seleção</PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
