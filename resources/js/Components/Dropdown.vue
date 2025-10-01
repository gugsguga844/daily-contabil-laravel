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
const openUpward = ref(false);

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

const positionClasses = computed(() => {
    return openUpward.value ? 'bottom-full mb-2' : 'top-full mt-2';
});

const toggleDropdown = () => {
    open.value = !open.value;
    
    if (open.value) {
        nextTick(() => {
            if (dropdownRef.value) {
                const rect = dropdownRef.value.getBoundingClientRect();
                const spaceBelow = window.innerHeight - rect.bottom;
                const spaceAbove = rect.top;
                
                // Open upward if there's not enough space below but there is space above
                openUpward.value = spaceBelow < 200 && spaceAbove > spaceBelow;
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

        <!-- Full Screen Dropdown Overlay -->
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
                class="absolute z-50 rounded-md shadow-lg"
                :class="[widthClass, alignmentClasses, positionClasses]"
                @click="open = false"
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
