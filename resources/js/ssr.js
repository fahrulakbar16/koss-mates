import { createSSRApp, h } from 'vue';
import { renderToString } from '@vue/server-renderer';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import FlowbiteVuePlugin from '@/Composables/flowbite';
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import ToastService from 'primevue/toastservice';
import { definePreset } from '@primevue/themes';
import VueApexCharts from 'vue3-apexcharts';
import FullCalendar from '@fullcalendar/vue3';
import Multiselect from 'vue-multiselect';

const AuraBlue = definePreset(Aura, {
    semantic: {
        primary: {
            50: '{blue.50}',
            100: '{blue.100}',
            200: '{blue.200}',
            300: '{blue.300}',
            400: '{blue.400}',
            500: '{blue.500}',
            600: '{blue.600}',
            700: '{blue.700}',
            800: '{blue.800}',
            900: '{blue.900}',
            950: '{blue.950}',
        },
        colorScheme: {
            light: {
                primary: {
                    color: '{primary.500}',
                    contrastColor: '#ffffff',
                    hoverColor: '{primary.600}',
                    activeColor: '{primary.700}',
                },
                surface: {
                    0: '#ffffff',
                },
            },
        },
    },
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

export default function render(page) {
    return createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob('./Pages/**/*.vue', { eager: false })
            ),
        setup({ App, props, plugin }) {
            const app = createSSRApp({ render: () => h(App, props) });

            app.use(plugin);
            app.use(FlowbiteVuePlugin);
            
            if (props.initialPage.props.ziggy) {
                app.use(ZiggyVue, {
                    ...props.initialPage.props.ziggy,
                    location: new URL(props.initialPage.props.ziggy.location),
                });
            }
            
            app.use(
                PrimeVue,
                {
                    theme: {
                        preset: AuraBlue,
                        options: {
                            darkModeSelector: false,
                            cssLayer: {
                                name: 'primevue',
                                order: 'tailwind-base, primevue, tailwind-utilities',
                            },
                        },
                    },
                }
            );
            app.use(ToastService);
            app.use(VueApexCharts);

            app.component('apexchart', VueApexCharts);
            app.component('Multiselect', Multiselect);
            app.component('FullCalendar', FullCalendar);

            return app;
        },
    });
}

