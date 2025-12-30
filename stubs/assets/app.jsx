import React from "react";
import { createRoot } from "react-dom/client";
import { createInertiaApp } from "@inertiajs/react";
import Layout from "./js/components/Layout";
import "./styles/app.css";

const appName = "Symfony ❤️ Inertia.js";

createInertiaApp({
    progress: {
        showSpinner: true,
    },
    title: (title) => (title ? `${title} | ${appName}` : appName),
    resolve: async (name) => {
        const pages = import.meta.glob('./js/pages/**/*.jsx');
        const path = `./js/pages/${name}.jsx`;
        if (!pages[path]) {
            throw new Error(`Page not found: ${path}`);
        }
        const module = await pages[path]();
        const page = module.default;
        if (page.layout === undefined) {
            page.layout = Layout;
        }

        return page;
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
});