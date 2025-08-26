import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
        colors: {
            primary: {
                DEFAULT: '#0D3D56', // Azul Sóbrio
                light: '#1A5A78',
                dark: '#072433',
            },
            accent: {
                DEFAULT: '#D97706', // Âmbar
                light: '#FBBF24',
                dark: '#B45309',
            },
            // Nossos cinzas personalizados
            base: {
                50:  '#F8FAFC', // Fundo Principal
                100: '#F1F5F9',
                200: '#E2E8F0', // Bordas
                300: '#CBD5E1',
                400: '#94A3B8',
                500: '#64748B', // Texto Secundário
                600: '#475569',
                700: '#334155',
                800: '#1E293B', // Texto Principal
                900: '#0F172A',
            }
        }
    },

    plugins: [forms],
};
