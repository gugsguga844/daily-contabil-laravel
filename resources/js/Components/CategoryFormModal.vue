<script setup>
import { ref, watch, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import * as Lucide from 'lucide-vue-next';

const props = defineProps({
    show: { type: Boolean, default: false },
    category: { type: Object, default: null },
});

const emit = defineEmits(['close']);

const form = useForm({
    name: '',
    description: '',
    icon_name: null,
    icon_color: '#000000',
});

// Curated set of lucide icons to pick from
const availableIcons = ref([
    'Calculator',
    'Landmark',
    'UsersRound',
    'UsersRoundCog',
    'BookOpen',
    'ClipboardList',
    'Briefcase',
    'FileText',
    'FileSpreadsheet',
    'FileBarChart',
    'Folder',
    'Building2',
    'Stamp',
    'ReceiptText',
    'Notebook',
    'Archive',
]);

// Nice preset colors for quick selection
const colorSwatches = [
    '#0D3D56', '#F97316', '#22C55E', '#8B5CF6', '#EF4444', '#3B82F6', '#F59E0B', '#2563EB', '#10B981', '#0EA5E9'
];

const SelectedIcon = computed(() => (form.icon_name && Lucide[form.icon_name]) ? Lucide[form.icon_name] : null);
const isEditing = computed(() => !!props.category);

function resetForm() {
    form.reset();
    form.clearErrors();
}

watch(() => props.show, (isShown) => {
    if (isShown) {
        if (isEditing.value) {
            form.defaults({ ...props.category });
            resetForm();
        } else {
            resetForm();
        }
    }
});

function submit() {
    const options = {
        onSuccess: () => emit('close'),
    };

    if (isEditing.value) {
        form.put(route('manage.categories.update', props.category.id), options);
    } else {
        form.post(route('manage.categories.store'), options);
    }
}

function handleCloseRequest() {
    emit('close');
}
</script>

<template>
    <div v-if="show" @click="handleCloseRequest" class="fixed inset-0 bg-black bg-opacity-50 z-[998]">

        <div @click.stop class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-xl z-[999] p-6 max-w-xl w-full max-h-[calc(100vh-4rem)] flex flex-col">

            <form @submit.prevent="submit" class="flex flex-col overflow-hidden h-full">

                <div>
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ isEditing ? 'Editar Categoria' : 'Nova Categoria' }}
                    </h2>
                    <hr class="my-4">
                </div>

                <div class="space-y-4 overflow-y-auto flex-grow pr-2">
                    <div>
                        <InputLabel for="cat-name" value="Nome *" />
                        <TextInput id="cat-name" v-model="form.name" type="text" class="mt-1 block w-full" required />
                        </div>
                    <div>
                        <InputLabel for="cat-desc" value="Descrição" />
                        <textarea id="cat-desc" v-model="form.description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    <div>
                        <InputLabel value="Ícone *" />
                        <div class="mt-2 grid grid-cols-7 gap-2">
                            <button
                                v-for="iconName in availableIcons"
                                :key="iconName"
                                type="button"
                                @click="form.icon_name = iconName"
                                class="flex items-center justify-center rounded-md border p-2 hover:bg-gray-50 transition"
                                :class="form.icon_name === iconName ? 'border-brand-accent ring-2 ring-brand-accent/30 bg-brand-accentlight' : 'border-gray-200'"
                                :title="iconName"
                            >
                                <component :is="Lucide[iconName]" class="w-5 h-5" :style="{ color: form.icon_color }" />
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Escolha um ícone para a categoria.</p>
                    </div>
                    <div>
                        <InputLabel value="Cor do ícone *" />
                        <div class="mt-2 flex flex-wrap gap-2">
                            <button
                                v-for="color in colorSwatches"
                                :key="color"
                                type="button"
                                @click="form.icon_color = color"
                                class="w-7 h-7 rounded-md border"
                                :class="form.icon_color === color ? 'border-gray-900 ring-2 ring-gray-400' : 'border-gray-200'"
                                :style="{ backgroundColor: color }"
                                :title="color"
                            />
                            <div class="ml-2 flex items-center gap-2">
                                <input id="cat-color" v-model="form.icon_color" type="color" class="border-gray-300 rounded-md shadow-sm" />
                                <span class="text-sm text-gray-600">{{ form.icon_color }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 rounded-md border p-3 bg-amber-50/70">
                        <p class="text-xs font-semibold text-gray-700 mb-2">Preview</p>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-md bg-white">
                                <component v-if="SelectedIcon" :is="SelectedIcon" class="w-6 h-6" :style="{ color: form.icon_color }" />
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ form.name || 'Nome da Categoria' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-4 border-t pt-4">
                    <SecondaryButton type="button" @click="handleCloseRequest">
                        Cancelar
                    </SecondaryButton>
                    <PrimaryButton type="submit" :disabled="form.processing">
                        {{ isEditing ? 'Salvar Alterações' : 'Criar Categoria' }}
                    </PrimaryButton>
                </div>

            </form>
        </div>
    </div>
</template>