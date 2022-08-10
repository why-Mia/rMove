const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    darkMode:"class",

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'main': {
                  //100: '#0891b2',
                  //200: '#0891b2',
                  //300: '#0891b2',
                  //400: '#0891b2',
                  50:  '#dfe3ec',
                  100: '#c0c8d8',
                  150: '#a0adc5',
                  200: '#8192b1',
                  250: '#7185a8',
                  300: '#61779e',
                  350: '#576b8e',
                  400: '#4e607e',
                  450: '#465772',
                  500: '#3e4d65',
                  550: '#38465c',
                  600: '#323f52',
                  650: '#2d3849',
                  700: '#27313f',
                  750: '#232c39',
                  800: '#1f2632',
                  850: '#1b222c',
                  900: '#181d26',
                  950: '#14181f',
                },
              },
        },
        
    },
    
    important:true,

    plugins: [require('@tailwindcss/forms')],
};
