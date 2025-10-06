<script setup>
import { computed, onMounted, onUnmounted, ref, nextTick } from 'vue';

const props = defineProps({
    align: {
        type: String,
        default: 'right',
    },
    width: {
        type: String,
        default: '48',
    },
    contentClasses: {
        type: String,
        default: 'py-1 bg-white',
    },
});

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const widthClass = computed(() => {
    return {
        48: 'w-48',
    }[props.width.toString()];
});

const open = ref(false);
const dropdownRef = ref(null);
const menuRef = ref(null);
const openUpward = ref(false);
const menuStyle = ref({ top: '0px', left: '0px' });

const alignmentClasses = computed(() => {
    let classes = '';
    if (props.align === 'left') {
        classes = 'ltr:origin-top-left rtl:origin-top-right start-0';
    } else if (props.align === 'right') {
        classes = 'ltr:origin-top-right rtl:origin-top-left end-0';
    } else {
        classes = 'origin-top';
    }
    
    return classes;
});

const positionClasses = computed(() => '');

const toggleDropdown = () => {
    open.value = !open.value;
    
    if (open.value) {
        nextTick(() => {
            if (dropdownRef.value && menuRef.value) {
                const triggerRect = dropdownRef.value.getBoundingClientRect();
                const spaceBelow = window.innerHeight - triggerRect.bottom;
                const spaceAbove = triggerRect.top;
                const menuHeight = menuRef.value.offsetHeight || 0;
                const menuWidth = menuRef.value.offsetWidth || 0;
                const margin = 8;

                openUpward.value = menuHeight > spaceBelow && spaceAbove > spaceBelow;

                let x = props.align === 'right'
                    ? triggerRect.right - menuWidth
                    : triggerRect.left;
                let y = openUpward.value
                    ? triggerRect.top - menuHeight - margin
                    : triggerRect.bottom + margin;

                x = Math.min(Math.max(8, x), window.innerWidth - menuWidth - 8);
                y = Math.min(Math.max(8, y), window.innerHeight - menuHeight - 8);

                menuStyle.value = { top: `${y}px`, left: `${x}px` };
            }
        });
    }
};
</script>

<template>
    <div class="relative" ref="dropdownRef">
        <div @click.stop="toggleDropdown">
            <slot name="trigger" />
        </div>

        <div
            v-show="open"
            class="fixed inset-0 z-40"
            @click="open = false"
        ></div>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-show="open"
                class="fixed z-50 rounded-md shadow-lg"
                :class="[widthClass, alignmentClasses, positionClasses]"
                :style="menuStyle"
                @click="open = false"
                ref="menuRef"
            >
                <div
                    class="rounded-md ring-1 ring-black ring-opacity-5"
                    :class="contentClasses"
                >
                    <slot name="content" />
                </div>
            </div>
        </Transition>
    </div>
</template>
