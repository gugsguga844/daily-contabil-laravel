<script setup>
import { computed } from 'vue';

const props = defineProps({
    content: Object,
})

const videoUrl = computed(() => {
    if (props.content.full_url) {
        return props.content.full_url;
    }
    return `s3://daily-contabil-uploads/contents/${props.content.path_or_content}`;
});
</script>

<template>
    <div class="bg-black rounded-lg aspect-video">
        <video
            :src="videoUrl"
            controls
            class="w-full h-full object-contain rounded-lg"
        >
            <p>Seu navegador não suporta o elemento de vídeo.</p>
        </video>
    </div>
</template>