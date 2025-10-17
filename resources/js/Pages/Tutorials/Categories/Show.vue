<script setup>
import HeaderTitle from '@/Components/HeaderTitle.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Download, Play, Plus } from 'lucide-vue-next';
import { Link, router } from '@inertiajs/vue3';
import { useFormatters } from '@/Composables/useFormatters';

const props = defineProps({
    category: {
        type: Object,
        required: true,
    },
});

function levelLabel(level) {
    const key = String(level || '').toLowerCase();
    const map = {
        beginner: 'Iniciante',
        intermediate: 'Intermediário',
        advanced: 'Avançado',
    };
    return map[key] || level;
}

function levelClasses(level) {
    const key = String(level || '').toLowerCase();
    switch (key) {
        case 'beginner':
            return 'bg-green-100 text-green-800';
        case 'intermediate':
            return 'bg-yellow-100 text-yellow-800';
        case 'advanced':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

const { formatDate } = useFormatters();

function createTutorial() {
    router.get(route('tutorials.create', { category_id: props.category.id }));
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <HeaderTitle :title="category.name" :subtitle="category.description" />
            <div class="flex gap-4 py-2">
                <PrimaryButton :icon="Plus" @click="createTutorial">
                    <span class="mt-1">Novo Tutorial</span>
                </PrimaryButton>
            </div>
        </div>
        <div v-if="category.tutorials.length === 0">
            <p class="text-text-secondary">Nenhum tutorial cadastrado</p>
        </div>
        <div v-else>
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
            >
                <Link
                    v-for="tutorial in category.tutorials"
                    :key="tutorial.id"
                    :href="route('tutorials.show', tutorial.id)"
                    class="border shadow-sm bg-white border-opacity-50 rounded p-4"
                >
                    <div class="flex flex-col gap-6">
                        <div class="flex justify-between">
                            <div class="flex-grow gap-2">
                                <p class="text-text-primary font-semibold">{{ tutorial.title }}</p>
                                <p class="text-text-secondary">{{ tutorial.description }}</p>
                            </div>
                            <p class="bg-text-primary text-white px-2 py-1 flex items-center gap-2 rounded text-xs my-1"><Play class="w-5 h-5" /> </p>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between gap-2">
                                <p class="text-text-secondary text-sm">{{ tutorial.steps_count }} etapas</p>
                                <p class="text-text-secondary text-sm">{{ formatDate(tutorial.created_at) }}</p>
                            </div>
                            <div class="flex gap-2 items-center justify-between">
                                <span :class="`px-2 py-0.5 rounded text-xs ${levelClasses(tutorial.level)}`">{{ levelLabel(tutorial.level) }}</span>
                                <Download class="w-5 h-5 text-text-secondary" />
                            </div>
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
