<script setup>
import ContentTypeIcon from '@/Components/ContentTypeIcon.vue';
import { Download } from 'lucide-vue-next';
import { useFormatters } from '@/Composables/useFormatters';

const { formatBytes } = useFormatters();
defineProps({
    content: Object,
})
</script>

<template>
    <div class="bg-gray-50 rounded-lg aspect-video flex items-center justify-center">
        <div class="text-center">
            <ContentTypeIcon :type="content.type" size="w-16 h-16" class="mx-auto text-gray-400" />
            <h3 class="mt-4 text-lg font-semibold text-gray-800">{{ content.title }}</h3>
            <p v-if="content.size_bytes" class="text-sm text-gray-500 mt-1">
                {{ formatBytes(content.size_bytes) }}
            </p>
            <a 
                :href="content.full_url"
                :download="content.title"
                class="mt-6 inline-flex items-center gap-2 bg-brand-accent text-white font-semibold px-4 py-2 rounded-lg hover:bg-brand-accent-dark transition-colors"
            >
                <Download class="w-4 h-4" />
                <span>Baixar Planilha</span>
            </a>
        </div>
    </div>
</template>