<script setup>
import { computed } from 'vue';
import { Image, Video, FileText, FileSpreadsheet, File } from 'lucide-vue-next';

const props = defineProps({
  type: String,
  mimeType: String,
  size: { type: String, default: 'w-8 h-8' },
  color: { type: String, default: null },
  customClass: { type: String, default: '' },
});

function normalizeType({ type, mime }) {
  if (type) {
    if (type === 'imagem') return 'image';
    if (type === 'video') return 'video';
    if (type === 'documento') return 'document';
    if (type === 'planilha') return 'sheet';
    return 'other';
  }
  if (mime) {
    if (mime.startsWith('image/')) return 'image';
    if (mime.startsWith('video/')) return 'video';
    if ([
      'application/pdf',
      'application/msword',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ].includes(mime)) return 'document';
    if ([
      'application/vnd.ms-excel',
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      'text/csv',
    ].includes(mime)) return 'sheet';
    return 'other';
  }
  return 'other';
}

const effectiveType = computed(() => normalizeType({ type: props.type, mime: props.mimeType }));

const Icon = computed(() => {
  switch (effectiveType.value) {
    case 'image': return Image;
    case 'video': return Video;
    case 'document': return FileText;
    case 'sheet': return FileSpreadsheet;
    default: return File;
  }
});

const colorClass = computed(() => {
  if (props.color) return props.color;
  switch (effectiveType.value) {
    case 'document': return 'text-red-500';
    case 'sheet': return 'text-green-500';
    default: return 'text-gray-600';
  }
});

const finalClass = computed(() => [props.size, colorClass.value, props.customClass].filter(Boolean).join(' '));
</script>

<template>
  <component :is="Icon" :class="finalClass" />
</template>