import './bootstrap';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import '../css/tailwind.css';

import Toast from './Pages/Inc/Toast.vue';

createInertiaApp({
  resolve: name => {
    // const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    // return pages[`./Pages/${name}.vue`]
    // Lazyload
    const pages = import.meta.glob('./Pages/**/*.vue')
    return pages[`./Pages/${name}.vue`]()
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
