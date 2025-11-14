<script setup>
import { usePage } from '@inertiajs/vue3';
import SidebarLink from '../Components/SidebarLink.vue';
import { computed } from 'vue';

const page = usePage();

const isAdmin = computed(() => {
    const user = page.props.auth.user;
    return user && (user.role === 'office_owner' || user.role === 'admin');
})
</script>

<template>
    <nav class="fixed top-16 left-0 bottom-0 w-64 z-50 bg-surface-menus overflow-y-auto border-t border-border flex flex-col justify-between">
        <div class="p-4 space-y-2">
            <SidebarLink :href="route('dashboard')" :active="$page.url.startsWith('/dashboard')">
                <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard mr-3 h-5 w-5"><rect width="7" height="9" x="3" y="3" rx="1"></rect><rect width="7" height="5" x="14" y="3" rx="1"></rect><rect width="7" height="9" x="14" y="12" rx="1"></rect><rect width="7" height="5" x="3" y="16" rx="1"></rect></svg>
                </template>
                <template #label>Dashboard</template>
            </SidebarLink>
            <SidebarLink :href="route('tutorials.categories.index')" :active="$page.url.startsWith('/tutorials')">
                <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-graduation-cap mr-3 h-5 w-5"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"></path><path d="M22 10v6"></path><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"></path></svg>
                </template>
                <template #label>Tutoriais</template>
            </SidebarLink>
            <SidebarLink :href="route('companies.index')" :active="$page.url.startsWith('/companies')">
                <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building2 mr-3 h-5 w-5"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>
                </template>
                <template #label>Empresas</template>
            </SidebarLink>
            <SidebarLink :href="route('dashboard')" :active="$page.url.startsWith('/reports')">
                <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart3 mr-3 h-5 w-5"><path d="M3 3v18h18"></path><path d="M18 17V9"></path><path d="M13 17V5"></path><path d="M8 17v-3"></path></svg>
                </template>
                <template #label>Relatórios</template>
            </SidebarLink>
        </div>
        <div v-if="isAdmin" class="p-4">
            <h3 class="px-3 mb-2 text-xs font-semibold uppercase text-gray-500 tracking-wider">
                Administração
            </h3>
            <div>
                <SidebarLink :href="route('manage.categories.index')" :active="$page.url.startsWith('/manage/categories')">
                     <template #icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check mr-3 h-5 w-5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
                     </template>
                     <template #label>Gerenciar Categorias</template>
                </SidebarLink>
                <SidebarLink :href="route('manage.users.index')" :active="$page.url.startsWith('/manage/users')">
                     <template #icon>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user mr-3 h-5 w-5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                     </template>
                     <template #label>Gerenciar Usuários</template>
                </SidebarLink>
            </div>
        </div>
        <div class="p-4 space-y-2">
            <SidebarLink :href="route('logout')" method="post" :active="$page.url.startsWith('/logout')">
                <template #icon>
                    <i class="fa-solid fa-right-from-bracket mr-3"></i>
                </template>
                <template #label>Sair</template>
            </SidebarLink>
        </div>
    </nav>
</template>
