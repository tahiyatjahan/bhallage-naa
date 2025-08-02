import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    light: '#FFF9C4', // light yellow
                    DEFAULT: '#FFEB3B', // main yellow
                    dark: '#FBC02D', // dark yellow
                },
                accent: '#FFD600', // accent yellow
            },
            transitionProperty: {
                'height': 'height',
                'spacing': 'margin, padding',
            },
            animation: {
                'fade-in': 'fadeIn 0.7s ease-in',
                'slide-in': 'slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1)',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: 0 },
                    '100%': { opacity: 1 },
                },
                slideIn: {
                    '0%': { transform: 'translateY(20px)', opacity: 0 },
                    '100%': { transform: 'translateY(0)', opacity: 1 },
                },
            },
        },
    },

    plugins: [forms],
};
