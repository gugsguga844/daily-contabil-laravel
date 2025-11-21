<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-xs md:text-sm text-state-danger">
            {{ message }}
        </p>
    </div>
</template>
