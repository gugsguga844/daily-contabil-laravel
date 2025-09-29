<script setup>
import { Search } from 'lucide-vue-next';
defineProps({
    placeholder: String,
});
</script>

<template>
    <div class="relative w-96">
        <Search class="absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary w-5 h-5" />
        <input type="text" class="w-full bg-transparent pl-10 border border-surface-muted rounded-lg p-2" :placeholder="placeholder">
    </div>
</template>