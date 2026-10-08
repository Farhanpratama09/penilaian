/** @type {import('tailwindcss').Config} */
export default {
    corePlugins: {
        preflight: false,
    },
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};
