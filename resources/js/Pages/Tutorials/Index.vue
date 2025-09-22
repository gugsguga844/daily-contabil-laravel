<script setup>
    import HeaderTitle from '@/Components/HeaderTitle.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Tutoriais" />

    <AuthenticatedLayout>
        <HeaderTitle title="Tutoriais" subtitle="Aprenda a usar o sistema" />
    </AuthenticatedLayout>
</template>
