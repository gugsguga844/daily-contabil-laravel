<script setup>
import { ref, watch, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { icons } from 'lucide-vue-next';

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

const availableIcons = ref(Object.keys(icons).slice(0, 50));
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
                        <InputLabel for="cat-icon" value="Ícone" />
                        <select id="cat-icon" v-model="form.icon_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option :value="null">-- Selecione --</option>
                            <option v-for="iconName in availableIcons" :key="iconName" :value="iconName">{{ iconName }}</option>
                        </select>
                    </div>
                    <div>
                        <InputLabel for="cat-color" value="Cor do Ícone" />
                        <input id="cat-color" v-model="form.icon_color" type="color" class="mt-1 block border-gray-300 rounded-md shadow-sm" />
                        <span class="ml-2 text-sm text-gray-600">{{ form.icon_color }}</span>
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