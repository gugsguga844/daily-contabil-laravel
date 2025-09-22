<script setup>
import AppHeader from './AppHeader.vue';
import TheSidebar from './TheSidebar.vue';

</script>

<template>
    <AppHeader />
    <div class="flex">
        <TheSidebar />
        <main class="flex-1 ml-64">
            <slot />
        </main>
    </div>
</template>
