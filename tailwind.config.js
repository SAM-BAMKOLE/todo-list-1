/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./index.php", "./**/*.php"],
    theme: {
        extend: {
            colors: {
                primary: "rgb(96 165 250)",
                base: "#001040",
                secondary: "#FB8500",
            },
        },
    },
    plugins: [],
};
