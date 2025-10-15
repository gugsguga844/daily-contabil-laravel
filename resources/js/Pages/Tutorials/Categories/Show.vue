<script setup>
import HeaderTitle from '@/Components/HeaderTitle.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Plus } from 'lucide-vue-next';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    category: {
        type: Object,
        required: true,
    },
});

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
                    <div class="flex flex-col gap-4">
                        <div class="flex justify-between">
                            <div class="flex gap-2">
                                <p class="text-text-primary font-semibold">{{ tutorial.title }}</p>
                            </div>
                            <p class="bg-text-primary text-white px-2 py-1 rounded text-xs">{{ tutorial.steps_count }} tutoriais</p>
                        </div>
                        <p class="text-text-secondary">{{ tutorial.description }}</p>
                    </div>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>