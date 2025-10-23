<script setup>
const model = defineModel({
    type: String,
    required: true,
})
</script>

<template>
    <textarea
        v-model="model"
        class="mt-1 block w-full"
        required
        autofocus
    ></textarea>
</template>
    