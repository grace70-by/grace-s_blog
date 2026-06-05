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
                ig: {
                    purple: '#833AB4',
                    magenta: '#C13584',
                    pink: '#E1306C',
                    orange: '#F77737',
                    yellow: '#FCAF45',
                    blue: '#405DE6',
                    surface: '#FAFAFA',
                    card: '#FFFFFF',
                    dark: '#262626',
                    muted: '#8E8E8E',
                    border: '#DBDBDB',
                    hover: '#F2F2F2',
                },
            },
            backgroundImage: {
                'ig-gradient': 'linear-gradient(45deg, #833AB4 0%, #C13584 25%, #E1306C 50%, #F77737 75%, #FCAF45 100%)',
                'ig-gradient-soft': 'linear-gradient(135deg, #fdf2f8 0%, #faf5ff 35%, #fff7ed 70%, #fefce8 100%)',
                'ig-gradient-nav': 'linear-gradient(90deg, #833AB4, #C13584, #E1306C)',
            },
            boxShadow: {
                card: '0 1px 2px rgba(0,0,0,.08), 0 4px 12px rgba(131,58,180,.06)',
                'card-hover': '0 4px 16px rgba(225,48,108,.12)',
            },
            maxWidth: {
                feed: '680px',
            },
        },
    },

    plugins: [forms],
};
