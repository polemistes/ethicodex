require('./bootstrap');

import { createApp, h } from 'vue'
import { createInertiaApp, Link } from '@inertiajs/vue3'
import Layout from "./Shared/Layout";

createInertiaApp({
  progress: {
    delay: 80,
    showSpinner: true,
    color: '#660',
    includeCSS: true,
  },
  resolve: name => {
    let page = require(`./Pages/${name}`).default;
   
    page.layout = Layout;
    
    return page;
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .component("Link", Link)
      .mount(el)
  },
})

