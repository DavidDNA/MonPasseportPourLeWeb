/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./assets/**/*.js",
        "./templates/**/*.html.twig",
    ],
    safelist: [
        "flex",
        "items-center",
        "mb-6"
    ],
    theme: {
        extend: {
            colors: {
                'p1': '#000000',
                'p2': '#ffffff',
                'p3': '#535E85',
                'p4': '#AEBCCB',
                'p5': '#B7D14E',
                'p6': '#EA5B5A',
                'p7': '#F1AAB4',
                'p8': '#f0e5df',
                'p9': '#F9BA52',
                'password-yellow': '#f7b951',
                'password-pink': '#fdabb5',
                'password-blue': '#adbbc9',
                'password-red': '#e85a59',
            }
        },
    },
    plugins: [],
}
