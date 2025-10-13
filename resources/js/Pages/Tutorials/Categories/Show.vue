<script setup>
import HeaderTitle from '@/Components/HeaderTitle.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Plus } from 'lucide-vue-next';

const props = defineProps({
    category: Object,
});

</script>

<template>
    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <HeaderTitle :title="category.name" :subtitle="category.description" />
            <div class="flex gap-4 py-2">
                <PrimaryButton :icon="Plus" @click="onCreate">
                    <span class="mt-1">Nova Categoria</span>
                </PrimaryButton>
            </div>
        </div>

        <div v-if="category.tutorials.length > 0">
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
                                <component :is="getIconComponent(tutorial.icon_name)" class="w-6 h-6" :style="{ color: tutorial.icon_color }" />
                                <p class="text-text-primary font-semibold">{{ tutorial.name }}</p>
                            </div>
                            <p class="bg-text-primary text-white px-2 py-1 rounded text-xs">{{ tutorial.tutorials_count }} tutoriais</p>
                        </div>
                        <p class="text-text-secondary">{{ tutorial.description }}</p>
                    </div>
                </Link>
            </div>
        </div>
        <div v-else class="border border-dashed border-opacity-50 rounded p-4">
            <p class="text-text-secondary">Nenhum tutorial cadastrado</p>
        </div>
    </AuthenticatedLayout>
</template>