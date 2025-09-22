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
                sans: ['Cerebri Sans', 'sans-serif'],
            },
        },
        colors: {
            primary: {
                DEFAULT: '#1E293B', // Azul Sóbrio
                light: '#1A5A78',
                dark: '#072433',
            },
            secondary: {
                DEFAULT: '#475569',
            },
            accent: {
                DEFAULT: '#D97706', // Âmbar
                light: '#FBBF24',
                dark: '#B45309',
            },
            gray: {
                DEFAULT: '#cbd5e1', // Texto Secundário
            },
            white: {
                DEFAULT: '#FFFFFF',
            },
            // Nossos cinzas personalizados
            base: {
                50:  '#334155', // Fundo Principal
                100: '#94A3B8',
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
