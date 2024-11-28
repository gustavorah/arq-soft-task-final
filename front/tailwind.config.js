import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        // Add these lines to ensure components are scanned
        './app/View/Components/**/*.php',
        './resources/views/components/**/*.blade.php',
    ],

    safelist: [
        {
            pattern: /^(bg-|hover:bg-|focus:bg-|active:bg-)/, // This will catch all background color variations
        }
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};