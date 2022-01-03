module.exports = {
    content: ['./resources/**/*.{vue,js}'],
    darkMode: 'class',
    theme: {
        extend: {},
    },
    variants: {
        extend: {
            display: ['group-hover'],
        },
    },
    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/aspect-ratio')],
    important: true,
}
