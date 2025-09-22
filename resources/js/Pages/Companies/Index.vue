<script setup>
    import HeaderTitle from '@/Components/HeaderTitle.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Empresas" />

    <AuthenticatedLayout>
        <HeaderTitle title="Empresas" subtitle="Gerencie suas empresas" />
    </AuthenticatedLayout>
</template>
