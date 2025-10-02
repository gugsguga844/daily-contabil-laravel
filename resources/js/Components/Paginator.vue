<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({
    links: Array,
})

const decodeHtml = (str) => {
    if (!str) return '';
    const txt = document.createElement('textarea');
    txt.innerHTML = str;
    return txt.value;
}
</script>

<template>
    <!-- <div class="bg-white" v-if="links.length > 1">
        <div class="flex flex-wrap -mb-1 p-4 border-t">
            <template v-for="(link, key) in links" :key="key">
                <Link v-if="link.url" class="mr-1 px-4 py-3 text-sm leading-4 border rounded" :class="{ 'bg-surface-muted text-text-white': link.active }" :href="link.url" v-html="link.label"></Link>
                <span v-else class="mr-1 px-4 py-3 text-sm leading-4 border rounded" v-html="link.label"></span>
            </template>
        </div>
    </div> -->

    <div v-if="links.length > 3">
        <div class="flex justify-between items-center bg-white p-4 border-t shadow-lg">
            
            <Link
                v-if="links[0].url"
                :href="links[0].url"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
            >{{ decodeHtml(links[0].label) }}</Link>
            <span
                v-else
                class="px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md cursor-not-allowed"
            >{{ decodeHtml(links[0].label) }}</span>

            <div class="flex items-center">
                <template v-for="(link, key) in links.slice(1, -1)" :key="key">
                    <Link
                        :href="link.url"
                        class="px-4 py-2 text-sm font-medium border rounded-md mx-1"
                        :class="{
                            'bg-surface-accent text-white': link.active,
                            'text-gray-700 bg-white hover:bg-gray-50': !link.active,
                        }"
                    >{{ decodeHtml(link.label) }}</Link>
                </template>
            </div>

            <Link
                v-if="links[links.length - 1].url"
                :href="links[links.length - 1].url"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
            >{{ decodeHtml(links[links.length - 1].label) }}</Link>
            <span
                v-else
                class="px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md cursor-not-allowed"
            >{{ decodeHtml(links[links.length - 1].label) }}</span>
        </div>
    </div>
</template>
