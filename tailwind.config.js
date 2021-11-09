module.exports = {
    mode: 'jit',
    purge: ['./resources/**/*.{vue,js}'],
    darkMode: 'media',
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
