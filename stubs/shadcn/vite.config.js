import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";
import react from '@vitejs/plugin-react';
import path from "path"
import tailwindcss from "@tailwindcss/vite"

export default defineConfig({
    plugins: [
        react(), // if you're using React */
        symfonyPlugin(),
        tailwindcss()
    ],
    build: {
        rollupOptions: {
            input: {
                app: "./assets/app.jsx"
            },
        }
    },
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "./assets/js"),
        },
    },
});
