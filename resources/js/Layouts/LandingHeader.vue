<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';

</script>

<template>
    <header class="bg-[#1E293B] w-full h-16 flex justify-between px-6 fixed top-0 z-50">
        <nav class="flex items-center justify-between max-w-7xl w-full mx-auto">
            <div class="logo flex items-center">
                <img src="/images/daily-logo.png" alt="Logo" class="object-cover h-10 w-auto">
            </div>
            <div class="flex items-center space-x-4">
                <PrimaryButton :href="route('login')">Agendar Demonstração</PrimaryButton>
                <Link :href="route('login')" class="text-white px-6 py-2">Entrar</Link>
            </div>
        </nav>
    </header>
</template>