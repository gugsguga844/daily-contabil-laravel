<script setup>
</script>

<template>
    <header class="bg-[#1E293B] w-full h-16 flex justify-between px-6 sticky top-0 z-50">
        <div class="logo flex items-center">
            <img src="/images/daily-logo.png" alt="Logo" class="object-cover h-10 w-auto">
        </div>
        <div class="flex items-center space-x-4">
            <input type="text" placeholder="Procurar" class="rounded-lg w-full px-4 bg-base-50 placeholder-base-100 text-base-50">
        </div>
        <div class="flex items-center space-x-4">
            <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user w-4 h-4 text-white"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
        </div>
    </header>
</template>