<script setup>
import { onMounted, onBeforeUnmount, watch, computed } from 'vue';

// v-model support for `open`
const open = defineModel('open', { type: Boolean, default: false });

const props = defineProps({
  title: { type: String, default: 'Confirmar ação' },
  message: { type: String, default: '' },
  confirmLabel: { type: String, default: 'Confirmar' },
  cancelLabel: { type: String, default: 'Cancelar' },
  danger: { type: Boolean, default: false },
  loading: { type: Boolean, default: false },
});

const emit = defineEmits(['confirm', 'cancel', 'update:open']);

function onCancel() {
  if (props.loading) return;
  emit('cancel');
  open.value = false;
}

function onConfirm() {
  if (props.loading) return;
  emit('confirm');
}

function onKeydown(e) {
  if (!open.value) return;
  if (e.key === 'Escape') {
    e.preventDefault();
    onCancel();
  }
  if (e.key === 'Enter') {
    e.preventDefault();
    onConfirm();
  }
}

onMounted(() => {
  window.addEventListener('keydown', onKeydown);
});

onBeforeUnmount(() => {
  window.removeEventListener('keydown', onKeydown);
});

const confirmBtnClasses = computed(() =>
  props.danger
    ? 'bg-state-danger hover:bg-red-600 focus:ring-state-danger text-white'
    : 'bg-brand-accent hover:bg-amber-600 focus:ring-brand-accent text-white'
);
</script>

<template>
  <teleport to="body">
    <div v-show="open" class="fixed inset-0 z-[100]" aria-modal="true" role="dialog">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-black/30" @click="onCancel" />

      <!-- Modal panel -->
      <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-xl bg-white shadow-2xl ring-1 ring-black/5 overflow-hidden">
          <div class="px-5 py-4">
            <h3 class="text-base font-semibold text-text-primary">{{ title }}</h3>
            <p v-if="message" class="mt-2 text-sm text-text-secondary">{{ message }}</p>
          </div>
          <div class="px-5 pb-4 pt-2 flex items-center justify-end gap-2">
            <button type="button"
                    class="px-3 py-2 text-sm rounded-md border border-base-200 text-text-secondary hover:bg-surface"
                    @click="onCancel"
                    :disabled="loading">
              {{ cancelLabel }}
            </button>
            <button type="button"
                    class="px-3 py-2 text-sm rounded-md focus:ring-2 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="confirmBtnClasses"
                    @click="onConfirm"
                    :disabled="loading">
              <span v-if="!loading">{{ confirmLabel }}</span>
              <span v-else class="inline-flex items-center gap-2">
                <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                </svg>
                Processando...
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </teleport>
</template>
