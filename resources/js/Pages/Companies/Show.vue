<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HeaderTitle from '@/Components/HeaderTitle.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Empresa" />
    <AuthenticatedLayout>
        <HeaderTitle title="Empresa" subtitle="Detalhes da empresa" />
    </AuthenticatedLayout>
</template>
    