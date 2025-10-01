<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: false,
    },
    as: {
        type: String,
        default: 'a',
    },
});
</script>

<template>
    <Link
        v-if="as === 'a' || as === 'link'"
        :href="href"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
    >
        <slot />
    </Link>
    <button
        v-else
        :type="as === 'button' ? 'button' : 'submit'"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
        v-bind="$attrs"
    >
        <slot />
    </button>
</template>
