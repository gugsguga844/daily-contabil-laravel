<script setup>
import VuePdfEmbed from 'vue-pdf-embed';

defineProps({
    content: Object,
})
</script>

<template>
    <div>
        <VuePdfEmbed 
            :source="content.full_url"
            class="w-full h-full" 
        />
    </div>
</template>