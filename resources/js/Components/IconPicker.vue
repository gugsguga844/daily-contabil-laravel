<script setup>
import { computed } from 'vue';
// Importe os ícones que você quer oferecer da biblioteca que já usa
import { BookOpen, Briefcase, Landmark, FileText, BarChart2 } from 'lucide-vue-next';

const props = defineProps({
    modelValue: String, // Recebe o nome do ícone selecionado via v-model
});

const emit = defineEmits(['update:modelValue']);

// Nossa paleta de ícones pré-definidos
const availableIcons = [
    { name: 'BookOpen', component: BookOpen },
    { name: 'Briefcase', component: Briefcase },
    { name: 'Landmark', component: Landmark },
    { name: 'FileText', component: FileText },
    { name: 'BarChart2', component: BarChart2 },
    // Adicione quantos ícones quiser...
];

function selectIcon(iconName) {
    emit('update:modelValue', iconName);
}
</script>

<template>
    <div>
        <label class="block font-medium text-sm text-gray-700">Escolha um Ícone</label>
        <div class="mt-2 flex flex-wrap gap-2 border p-3 rounded-md">
            <button
                v-for="icon in availableIcons"
                :key="icon.name"
                type="button"
                @click="selectIcon(icon.name)"
                class="p-3 rounded-lg border-2 transition-transform duration-150 ease-in-out hover:scale-110"
                :class="modelValue === icon.name ? 'border-brand-accent bg-brand-accentlight' : 'border-gray-300 bg-gray-100'"
            >
                <component :is="icon.component" class="w-6 h-6 text-gray-600" />
            </button>
        </div>
    </div>
</template>