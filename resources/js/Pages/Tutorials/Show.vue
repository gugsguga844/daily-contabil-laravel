<script setup>
import HeaderTitle from '@/Components/HeaderTitle.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref } from 'vue';
import VideoPlayer from '@/Components/Content/Viewers/VideoPlayer.vue';
import PdfViewer from '@/Components/Content/Viewers/PdfViewer.vue';
import ImageViewer from '@/Components/Content/Viewers/ImageViewer.vue';
import FileViewer from '@/Components/Content/Viewers/FileViewer.vue';

const props = defineProps({
    tutorial: Object,
})

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
        <div class="flex gap-4">
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
