/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./config/*.php",
        "./resources/**/*.blade.php",
    ],
    theme: {
        extend: {},
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
