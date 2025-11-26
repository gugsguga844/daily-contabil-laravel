<script setup>
import { useAttrs } from 'vue';

defineProps({
    options: Array,
    invalid: {
        type: Boolean,
        default: false,
    },
});

const model = defineModel('modelValue');
const attrs = useAttrs();
</script>

<template>
    <select
        v-model="model"
        :class="[
            'mt-1 block w-full rounded-md shadow-sm border border-base-200 focus:border-primary focus:ring-primary',
            invalid && 'border-state-danger focus:border-state-danger focus:ring-state-danger ring-1 ring-state-danger/20'
        ]"
        :aria-invalid="invalid ? 'true' : 'false'"
        v-bind="attrs"
    >
        <option v-for="option in options" :key="option.value" :value="option.value">
            {{ option.label }}
        </option>
    </select>
</template>