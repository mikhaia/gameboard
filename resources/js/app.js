import './bootstrap';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import '../css/tailwind.css';

import { defineAsyncComponent } from 'vue'
const Toast = defineAsyncComponent(() => import('@/Pages/Inc/Toast.vue'))

createInertiaApp({
    resolve: (name) => {
        return resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));
    },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
      .mixin({ components: {Toast}})
      .use(plugin);
        // TODO: Move filters outside
        app.config.globalProperties.$filters = {
        console: (val, lvl = 1) => {
          switch(lvl) {
            case 2: console.warn(val); break;
            case 3: console.error(val); break;
            default: console.log(val); break;
          }
        },
      };

      app.mount(el);
  },
})
