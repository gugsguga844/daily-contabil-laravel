<script setup>
defineProps({
    content: Object,
})
</script>

<template>
    <div>
        <h2>Eu sou o leitor de arquivo</h2>
        <p>{{ content.title }}</p>
    </div>
</template>