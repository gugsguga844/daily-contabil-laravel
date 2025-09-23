import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Cerebri Sans', 'sans-serif'],
            },
            colors: {
                // Paleta semântica (use estas cores em vez de lembrar códigos/níveis)
                brand: {
                    DEFAULT: '#1E293B', // mapeando para primary.DEFAULT
                    light: '#1A5A78',   // mapeando para primary.light
                    dark: '#072433',    // mapeando para primary.dark
                    accent: '#D97706',  // alias prático para accent.DEFAULT
                },
                surface: {
                    // Planos de fundo por contexto
                    DEFAULT: '#F8FAFC', // base.50 - Fundo Principal
                    muted: '#E2E8F0',   // base.200 - Zonas sutis (content bg)
                    card: '#FFFFFF',    // cards e elementos elevados
                    menus: '#1E293B',   // navegação lateral/topbar escura
                    accent: '#D97706',  // alias prático para accent.DEFAULT
                },
                text: {
                    DEFAULT: '#fff',     // Texto principal
                    gray: '#cbd5e1',     // base.600
                    primary: '#1E293B',    // base.500
                    secondary: '#475569',  // texto sobre fundos escuros
                },
                border: {
                    DEFAULT: '#334155', // base.200 - Bordas e divisores
                },
                state: {
                    success: '#10B981', // verde
                    warning: '#FBBF24', // âmbar
                    danger:  '#EF4444', // vermelho
                    info:    '#3B82F6', // azul
                },
            },
        },
    },

    plugins: [forms],
};
