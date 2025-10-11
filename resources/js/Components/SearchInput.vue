<script setup>
import { Search } from 'lucide-vue-next';
defineProps({
    placeholder: String,
});
</script>

<template>
    <div class="relative">
        <Search class="absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary w-5 h-5" />
        <input type="text" class="w-full bg-white pl-10 border border-surface-muted shadow-sm rounded-lg p-2" :placeholder="placeholder">
    </div>
</template>