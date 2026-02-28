import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import Alpine from 'alpinejs';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => {
        // Get app name from shared props if available, fallback to env/default
        return title ? `${title} - ${appName}` : appName;
    },
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        // Use appName from shared props if available
        const sharedAppName = props.initialPage?.props?.appName as string | undefined;
        const finalAppName = sharedAppName || appName;

        // Update favicon if provided in shared props
        const faviconUrl = props.initialPage?.props?.faviconUrl as string | undefined;
        if (faviconUrl) {
            updateFavicon(faviconUrl);
        }

        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

/**
 * Update the favicon dynamically
 */
function updateFavicon(url: string) {
    // Remove existing favicon links
    const existingLinks = document.querySelectorAll("link[rel*='icon']");
    existingLinks.forEach(link => link.remove());

    // Add new favicon
    const link = document.createElement('link');
    link.rel = 'icon';
    link.href = url;
    document.head.appendChild(link);

    // Also add shortcut icon for older browsers
    const shortcutLink = document.createElement('link');
    shortcutLink.rel = 'shortcut icon';
    shortcutLink.href = url;
    document.head.appendChild(shortcutLink);
}

// This will set light / dark mode on page load...
initializeTheme();
