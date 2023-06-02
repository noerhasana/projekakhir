const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
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
            colors: {
                'prime': {
                    500: '#9565A3',
                    400: '#A673BA',
                    300: '#B682D1',
                    200: '#C790E8',
                    100: '#D59EFF',
                }
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
