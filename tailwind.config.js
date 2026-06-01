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
                sans: ['Outfit', 'sans-serif'],
            },
            colors: {
                primary: {
                    DEFAULT: '#2563EB',
                    50: '#EFF6FF',
                    100: '#DBEAFE',
                    200: '#BFDBFE',
                    300: '#93C5FD',
                    400: '#60A5FA',
                    500: '#2563EB',
                    600: '#2563EB',
                    700: '#1D4ED8',
                    800: '#1E40AF',
                    900: '#1E3A8A',
                },
                secondary: {
                    DEFAULT: '#081C3A',
                    50: '#F0F4F8',
                    100: '#D9E2EC',
                    200: '#BCCCDC',
                    300: '#9FB3C8',
                    400: '#627D98',
                    500: '#486581',
                    600: '#334E68',
                    700: '#243B53',
                    800: '#102A43',
                    900: '#081C3A',
                },
                tertiary: '#00B4D8',
                surface: '#F8FAFC',
                'surface-alt': '#FFFFFF',
                border: '#E2E8F0',
                'text-primary': '#0F172A',
                'text-secondary': '#64748B',
                'text-muted': '#94A3B8',
            },
            borderRadius: {
                sm: '10px',
                md: '16px',
                lg: '20px',
                xl: '28px',
            },
            boxShadow: {
                'card-sm': '0 4px 12px rgba(15,23,42,0.05)',
                'card-md': '0 10px 30px rgba(15,23,42,0.08)',
                'card-lg': '0 20px 40px rgba(15,23,42,0.12)',
            },
            spacing: {
                '4.5': '18px',
            },
        },
    },

    plugins: [forms],
};
