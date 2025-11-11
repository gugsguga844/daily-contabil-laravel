<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SimpleIconCard from '@/Components/SimpleIconCard.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Building2, BookOpen, FileText, Plus } from 'lucide-vue-next';

// If in the future backend provides counts, we can accept them here
const props = defineProps({
    stats: {
        type: Object,
        default: () => ({ companies: null, tutorials: null, contents: null })
    }
});

const userName = (typeof window !== 'undefined' && window?.app?.page?.props?.auth?.user?.name) || '';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="flex gap-4 mb-8 justify-between">
            <HeaderTitle title="Dashboard" :subtitle="userName ? `Bem-vindo(a), ${userName}` : 'Visão geral do sistema'" />
            <div class="flex gap-4 py-2">
                <Link :href="route('companies.create')">
                    <PrimaryButton :icon="Plus">
                        <span class="mt-1">Nova Empresa</span>
                    </PrimaryButton>
                </Link>
                <Link :href="route('tutorials.categories.index')">
                    <PrimaryButton :icon="BookOpen">
                        <span class="mt-1">Tutoriais</span>
                    </PrimaryButton>
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <SimpleIconCard :icon="Building2" title="Empresas" :subtitle="props.stats.companies ?? '—'" />
            <SimpleIconCard :icon="BookOpen" title="Tutoriais" :subtitle="props.stats.tutorials ?? '—'" />
            <SimpleIconCard :icon="FileText" title="Conteúdos" :subtitle="props.stats.contents ?? '—'" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-8">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="font-semibold text-text-primary text-lg mb-3">Atalhos Rápidos</h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <Link :href="route('companies.index')" class="text-brand-accent hover:underline">Ver empresas</Link>
                    </li>
                    <li>
                        <Link :href="route('tutorials.categories.index')" class="text-brand-accent hover:underline">Categorias de tutoriais</Link>
                    </li>
                    <li>
                        <Link :href="route('tutorials.index')" class="text-brand-accent hover:underline">Todos os tutoriais</Link>
                    </li>
                </ul>
            </div>
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="font-semibold text-text-primary text-lg mb-3">Novidades</h3>
                <p class="text-text-secondary text-sm">Sem novidades por enquanto. Continue explorando o sistema.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
