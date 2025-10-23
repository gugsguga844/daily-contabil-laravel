<script setup>
import HeaderTitle from '@/Components/HeaderTitle.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref } from 'vue';
import VideoPlayer from '@/Components/Content/Viewers/VideoPlayer.vue';
import PdfViewer from '@/Components/Content/Viewers/PdfViewer.vue';
import ImageViewer from '@/Components/Content/Viewers/ImageViewer.vue';
import FileViewer from '@/Components/Content/Viewers/FileViewer.vue';
import ContentTypeIcon from '@/Components/ContentTypeIcon.vue';
import { Download, ArrowLeft } from 'lucide-vue-next';
import { useFormatters } from '@/Composables/useFormatters';

const props = defineProps({
    tutorial: Object,
})

const { formatBytes } = useFormatters();

const onBack = () => {
    window.history.back();
}

const activeStepIndex = ref(0);

const activeStep = computed(() => {
    return props.tutorial.steps[activeStepIndex.value] || null;
})

function setActiveStep(index) {
    activeStepIndex.value = index;
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8">
            <div class="py-2">
                <SecondaryButton @click="onBack" :icon="ArrowLeft">
                    <span class="mt-1">Voltar</span>
                </SecondaryButton>
            </div>
            <HeaderTitle :title="tutorial.title" :subtitle="tutorial.description" />
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div class="col-span-2">
                <div v-if="activeStep && activeStep.content" class="bg-white rounded-lg border p-6 shadow-md">
                    <h3 class="text-xl font-bold mb-4 text-text-primary">{{ activeStep.title }}</h3>
                    <VideoPlayer 
                        v-if="activeStep.content.type === 'video'" 
                        :content="activeStep.content" 
                    />
                    <ImageViewer 
                        v-else-if="activeStep.content.type === 'imagem'" 
                        :content="activeStep.content" 
                    />
                    <PdfViewer 
                        v-else-if="activeStep.content.type === 'documento' && activeStep.content.path_or_content.endsWith('.pdf')" 
                        :content="activeStep.content" 
                    />
                    <FileViewer 
                        v-else 
                        :content="activeStep.content" 
                    />
                    
                </div>
                <div v-else class="bg-gray-50 rounded-lg aspect-video flex items-center justify-center">
                    <p class="text-gray-500">Selecione uma etapa para ver o seu conteúdo.</p>
                </div>

                <div class="mt-8 bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-xl font-bold mb-4 text-text-primary">Descrição Detalhada</h3>
                    <div v-html="tutorial.long_description" class="prose max-w-none"></div>
                </div>

                <div v-if="tutorial.supporting_materials && tutorial.supporting_materials.length > 0" class="mt-8 bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-xl font-bold mb-4 text-text-primary">Materiais de Apoio</h3>
                    <p class="text-sm text-text-secondary mb-6">Planilhas, documentos e recursos complementares.</p>
                    
                    <div class="space-y-3">
                        <div 
                            v-for="material in tutorial.supporting_materials" 
                            :key="material.id" 
                            class="flex items-center justify-between p-3 border rounded-lg bg-gray-50"
                        >
                            <div class="flex items-center gap-3">
                                <ContentTypeIcon :type="material.type" size="w-6 h-6" />
                                <div>
                                    <span class="font-semibold text-sm">{{ material.title }}</span>
                                    <p v-if="material.size_bytes" class="text-xs text-gray-500">
                                        {{ formatBytes(material.size_bytes) }}
                                    </p>
                                </div>
                            </div>
                            <a 
                                :href="material.full_url" 
                                :download="material.title"
                                class="inline-flex items-center gap-1.5 text-sm font-medium text-brand-accent hover:underline"
                            >
                                <Download class="w-5 h-5" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-1">
                <div class="p-6 bg-white shadow-md rounded-lg">
                    <h3 class="font-semibold text-text-primary text-lg">Etapas do Tutorial</h3>
                    <div class="space-y-2 mt-4">
                        <div 
                            v-for="(step, index) in tutorial.steps"
                            :key="step.id"
                            @click="setActiveStep(index)"
                            class="p-4 rounded-lg cursor-pointer border-2"
                            :class="activeStepIndex === index ? 'border-brand-accent bg-brand-accentlight' : 'bg-gray-50'"
                        >
                            <p class="text-text-primary font-semibold">{{ index + 1 }}. {{ step.title }}</p>
                            <p class="text-text-secondary text-sm">{{ step.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
