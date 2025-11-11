<script setup>
import { Search } from 'lucide-vue-next';

defineProps({
    placeholder: String,
});

// Enable v-model binding on this input
const model = defineModel({
    type: String,
    default: '',
});
</script>

<template>
    <div class="relative">
        <Search class="absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary w-5 h-5" />
        <input
            type="text"
            class="w-full bg-white pl-10 border border-surface-muted shadow-sm rounded-lg p-2"
            :placeholder="placeholder"
            v-model="model"
        >
    </div>
</template>