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
import { Download, ArrowLeft, CheckCircle, Circle, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { useFormatters } from '@/Composables/useFormatters';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    tutorial: Object,
})

const { formatBytes } = useFormatters();

const onBack = () => {
    window.history.back();
}

const activeStepIndex = ref(0);
// Local mutable steps to reflect UI changes immediately (optimistic updates)
const steps = ref(props.tutorial.steps.map(s => ({ ...s })));
const isProcessing = ref(false);

const activeStep = computed(() => {
    return steps.value[activeStepIndex.value] || null;
})

const canGoPrevious = computed(() => activeStepIndex.value > 0);
const canGoNext = computed(() => activeStepIndex.value < steps.value.length - 1);

function goToPreviousStep() {
    if (canGoPrevious.value) {
        activeStepIndex.value--;
    }
}

function goToNextStep() {
    if (!canGoNext.value || !activeStep.value || isProcessing.value) return;

    const current = activeStep.value;
    const wasCompleted = !!current.is_completed;

    if (!wasCompleted) {
        optimisticToggleCompletion(current, true, () => {
            activeStepIndex.value++;
        });
    } else {
        activeStepIndex.value++;
    }
}

const completedStepsCount = computed(() => {
    return steps.value.filter(step => step.is_completed).length;
})

const totalStepsCount = computed(() => steps.value.length);

const progressPercentage = computed(() => {
    if (totalStepsCount.value === 0) return 0;
    return (completedStepsCount.value / totalStepsCount.value) * 100;
})

function toggleStepCompletion(stepArg) {
    const step = stepArg || activeStep.value;
    if (!step || isProcessing.value) return;
    const targetState = !step.is_completed;
    optimisticToggleCompletion(step, targetState);
}

function optimisticToggleCompletion(step, targetState, onStarted) {
    isProcessing.value = true;
    const original = step.is_completed;
    step.is_completed = targetState;

    onStarted && onStarted();

    router.post(route('steps.toggle-completion', step.id), {}, {
        preserveScroll: true,
        onFinish: () => {
            isProcessing.value = false;
        },
        onError: () => {
            // Revert UI on error
            step.is_completed = original;
        },
    });
}

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
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="text-xl font-bold text-text-primary">{{ activeStep.title }}</h3>
                        <span v-if="activeStep.is_completed" class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-700 border border-green-300">
                            <CheckCircle class="w-4 h-4" /> Concluída
                        </span>
                    </div>
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
                    
                    <div class="mt-6 flex justify-between items-center border-t pt-4">
                        <button 
                            @click="goToPreviousStep" 
                            :disabled="!canGoPrevious || isProcessing"
                            class="inline-flex items-center gap-2 px-4 py-2 border rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed border-gray-300"
                        >
                            <ChevronLeft class="w-5 h-5" />
                            Anterior
                        </button>

                        <button 
                            @click="goToNextStep" 
                            :disabled="!canGoNext || isProcessing"
                            class="inline-flex items-center gap-2 px-4 py-2 border rounded-md shadow-sm text-sm font-medium text-white bg-brand-accent hover:brightness-95 disabled:opacity-50 disabled:cursor-not-allowed border-transparent"
                        >
                            Próximo
                            <ChevronRight class="w-5 h-5" />
                        </button>
                    </div>
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
                            v-for="(step, index) in steps"
                            :key="step.id"
                            @click="setActiveStep(index)"
                            class="p-4 rounded-lg cursor-pointer border-2 flex items-start gap-4 transition-colors"
                            :class="[
                                activeStepIndex === index ? 'border-brand-accent' : 'border-transparent',
                                step.is_completed ? 'bg-green-50' : (activeStepIndex === index ? 'bg-brand-accentlight' : 'bg-gray-50')
                            ]"
                        >
                            <button
                                class="flex-shrink-0 mt-1"
                                @click.stop="toggleStepCompletion(step)"
                                :title="step.is_completed ? 'Desmarcar conclusão' : 'Marcar como concluída'"
                                aria-label="Alternar conclusão da etapa"
                            >
                                <CheckCircle v-if="step.is_completed" class="w-5 h-5 text-green-500" />
                                <Circle v-else class="w-5 h-5 text-gray-400 hover:text-gray-600" />
                            </button>
                            <div>
                                <p class="text-text-primary font-semibold">{{ index + 1 }}. {{ step.title }}</p>
                                <p class="text-text-secondary text-sm">{{ step.description }}</p>
                                <span v-if="step.is_completed" class="inline-flex items-center mt-1 text-xs font-medium text-green-700 bg-green-100 px-2 py-0.5 rounded-full border border-green-300">Concluída</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                    <p class="text-sm font-medium text-gray-700 mb-1">
                        Progresso: {{ completedStepsCount }} / {{ totalStepsCount }} etapas
                    </p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div 
                            class="bg-brand-accent h-2.5 rounded-full transition-all duration-300 ease-out" 
                            :style="{ width: progressPercentage + '%' }"
                        ></div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
