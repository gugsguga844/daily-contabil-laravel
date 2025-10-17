<script setup>
import HeaderTitle from '@/Components/HeaderTitle.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    tutorial: Object,
})
</script>

<template>
    <AuthenticatedLayout>
        <HeaderTitle title="Tutoriais" subtitle="Base de conhecimentos e tutoriais" />

        <p>{{ tutorial.title }}</p>
    </AuthenticatedLayout>
</template>
