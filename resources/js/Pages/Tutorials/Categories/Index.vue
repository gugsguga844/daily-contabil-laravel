<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import { Head, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Plus } from 'lucide-vue-next';
import * as Lucide from 'lucide-vue-next';

defineProps({
    categories: Object,
});

function getIconComponent(iconName) {
    return Lucide[iconName] || null;
}
</script>

<template>
    <Head title="Categorias" />

    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <HeaderTitle title="Tutoriais" subtitle="Base de conhecimentos e tutoriais" />
        </div>

        <div v-if="categories.length > 0">
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
            >
                <Link 
                    v-for="category in categories" 
                    :key="category.id"
                    :href="route('tutorials.categories.show', category.id)"
                    class="border shadow-sm bg-white border-opacity-50 rounded p-4"
                >
                    <div class="flex flex-col gap-4">
                        <div class="flex justify-between">
                            <div class="flex gap-2">
                                <component
                                    v-if="getIconComponent(category.icon_name)"
                                    :is="getIconComponent(category.icon_name)"
                                    class="w-6 h-6"
                                    :style="{ color: category.icon_color }"
                                />
                                <span v-else class="inline-block w-3 h-3 rounded-full" :style="{ backgroundColor: category.icon_color || '#9ca3af' }" />
                                <p class="text-text-primary font-semibold">{{ category.name }}</p>
                            </div>
                            <p class="bg-text-primary text-white px-2 py-1 rounded text-xs">{{ category.tutorials_count }} tutoriais</p>
                        </div>
                        <p class="text-text-secondary">{{ category.description }}</p>
                    </div>
                </Link>
            </div>
        </div>
        <div v-else class="border border-dashed border-border border-opacity-50 rounded p-4">
            <p class="text-text-secondary">Nenhuma categoria cadastrada</p>
        </div>
    </AuthenticatedLayout>
</template>