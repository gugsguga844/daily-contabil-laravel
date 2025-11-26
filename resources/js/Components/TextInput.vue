<script setup>
import { onMounted, ref, useAttrs } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);
const attrs = useAttrs();

defineProps({
    invalid: {
        type: Boolean,
        default: false,
    },
});

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        :class="[
            'rounded-md border border-base-200 shadow-sm focus:border-primary focus:ring-primary placeholder:text-base-400',
            invalid && 'border-state-danger focus:border-state-danger focus:ring-state-danger ring-1 ring-state-danger/20'
        ]"
        :aria-invalid="invalid ? 'true' : 'false'"
        v-model="model"
        ref="input"
        v-bind="attrs"
    />
</template>
