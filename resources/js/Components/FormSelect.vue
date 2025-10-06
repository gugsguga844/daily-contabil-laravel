<script setup>
defineProps({
    options: Array,
});

const model = defineModel('modelValue');
</script>

<template>
    <select
        v-model="model"
        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    >
        <option v-for="option in options" :key="option.value" :value="option.value">
            {{ option.label }}
        </option>
    </select>
</template>