<script setup>
defineProps({
    columns: Array,
    rows: Array,
});
</script>

<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-surface-white">
                    <slot name="header" />
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <slot name="body" />
                </tbody>
            </table>
        </div>
    </div>
</template>