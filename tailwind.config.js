/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./config/*.php",
        "./resources/css/*.css",
        "./resources/views/**/*.blade.php",
    ],
    theme: {
        extend: {
            gridTemplateColumns: {
                'article': 'minmax(20%, 1fr) 60% 1fr',
            }
        },
    },
    safelist: [
        {
            pattern: /(bg|border|text)-\w+-\d+/,
            variants: [
                'hover',
            ],
        }
    ],
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
